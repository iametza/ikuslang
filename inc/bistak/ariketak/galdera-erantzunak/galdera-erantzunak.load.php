<?php
    
    if ($id_ariketa > 0) {
        
        $sql = "SELECT B.izena, B.azalpena
                FROM ariketak AS A
                JOIN ariketak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE A.id = " . $id_ariketa . " AND B.fk_hizkuntza = " . $hizkuntza['id'] . "";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        $galdera_erantzuna = new stdClass();
        
        if ($row = $dbo->emaitza()) {
            
            $galdera_erantzuna->izena = $row["izena"];
            $galdera_erantzuna->azalpena = $row["azalpena"];
        }
        
        $sql = "SELECT B.mota, B.bideo_path, B.bideo_mp4, bideo_webm, B.audio_path, B.audio_mp3, B.audio_ogg, C.hipertranskribapena
                FROM ariketak AS A
                INNER JOIN ikus_entzunezkoak AS B
                ON A.fk_ikus_entzunezkoa = B.id
                INNER JOIN ikus_entzunezkoak_hizkuntzak AS C
                ON B.id = C.fk_elem
                WHERE A.id = $id_ariketa AND C.fk_hizkuntza = " . $hizkuntza["id"] . "";
        
        $emaitza = get_query($sql);
        
        $galdera_erantzuna->ikus_entzunezkoa = new stdClass();
        
        $galdera_erantzuna->ikus_entzunezkoa->mota = $emaitza[0]["mota"];
        $galdera_erantzuna->ikus_entzunezkoa->bideo_path = $emaitza[0]["bideo_path"];
        $galdera_erantzuna->ikus_entzunezkoa->bideo_mp4 = $emaitza[0]["bideo_mp4"];
        $galdera_erantzuna->ikus_entzunezkoa->bideo_webm = $emaitza[0]["bideo_webm"];
        $galdera_erantzuna->ikus_entzunezkoa->audio_path = $emaitza[0]["audio_path"];
        $galdera_erantzuna->ikus_entzunezkoa->audio_mp3 = $emaitza[0]["audio_mp3"];
        $galdera_erantzuna->ikus_entzunezkoa->audio_ogg = $emaitza[0]["audio_ogg"];
        $galdera_erantzuna->ikus_entzunezkoa->hipertranskribapena = json_encode($emaitza[0]["hipertranskribapena"]);
        
        $lag = get_query ("SELECT B.denbora, B.galdera as galdera, B.fk_elem
                           FROM galdera_erantzunak_galderak AS A, galdera_erantzunak_galderak_hizkuntzak as B
                           WHERE A.fk_ariketa = '$id_ariketa'
                           AND B.fk_elem = A.id
                           AND B.fk_hizkuntza = '$hizkuntza[id]'
                           ORDER BY B.denbora ASC"
        );
        
        // Galdera objektuentzako arraya sortu
        $galdera_erantzuna->galderak = array();
        
        // Amaierako galderen objekuentzako arraya sortu.
        $galdera_erantzuna->amaierako_galderak = array();
        
        if (count ($lag) > 0) {
            foreach ($lag as $l) {
                // Galdera bakoitzaren datuak (galdera eta erantzunak) objektu batean gordeko ditugu.
                $galdera = new stdClass();
                
                // Galderaren testua eta noiz bistaratu behar den.
                $galdera->galdera = $l["galdera"];
                $galdera->denbora = $l["denbora"];
                
                // Galdera honi dagozkion erantzunak eskuratu
                $erantzunak_emaitza = get_query ("SELECT A.id, A.zuzena as zuzena, B.erantzuna as erantzuna " .
                                                 "FROM galdera_erantzunak_galdera_erantzunak AS A, galdera_erantzunak_galdera_erantzunak_hizkuntzak AS B " .
                                                 "WHERE A.fk_galdera = '$l[fk_elem]' " .
                                                 "AND A.id = B.fk_elem " .
                                                 "AND B.fk_hizkuntza = '$hizkuntza[id]'"
                );
                
                if (count ($erantzunak_emaitza) > 0) {
                    // Erantzunak bilduko dituen arraya
                    $galdera->erantzunak = array();
                    
                    foreach ($erantzunak_emaitza as $erantzuna_emaitza) {
                        $erantzuna = new stdClass();
                        
                        $erantzuna->id = $erantzuna_emaitza["id"];
                        $erantzuna->erantzuna = $erantzuna_emaitza["erantzuna"];
                        $erantzuna->zuzena = $erantzuna_emaitza["zuzena"];
                        
                        array_push($galdera->erantzunak, $erantzuna);
                    }
                }
                
                // Hasi aurreko galderak baleude $galdera->denbora == 0.
                
                // Amaierako galdera bada...
                if ($galdera->denbora == -1) {
                    
                    array_push($galdera_erantzuna->amaierako_galderak, $galdera);
                    
                // Galdera arrunta bada...
                } else {
                    
                    // Galdera array-ra gehitu
                    array_push($galdera_erantzuna->galderak, $galdera);
                    
                }
                
            }
            
        }
        
        $sql = "SELECT C.izenburua, B.path_dokumentua, B.dokumentua
                FROM ariketa_dokumentua AS A
                INNER JOIN dokumentuak AS B
                ON A.fk_dokumentua = B.id
                INNER JOIN dokumentuak_hizkuntzak AS C
                ON B.id = C.fk_elem
                WHERE A.fk_ariketa = $id_ariketa AND C.fk_hizkuntza = " . $hizkuntza["id"];
        
        $galdera_erantzuna->dokumentuak = array();
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        while ($row = $dbo->emaitza()) {
            
            $tmp_dokumentua = new stdClass();
            
            $tmp_dokumentua->izenburua = $row["izenburua"];
            $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
            $tmp_dokumentua->dokumentua = $row["dokumentua"];
            
            $galdera_erantzuna->dokumentuak[] = $tmp_dokumentua;
        }
        
        // Aldagaiak suntsitu badaezpada ere.
        unset($lag);
        unset($l);
        unset($erantzunak_emaitza);
        unset($erantzuna_emaitza);
        unset($galdera);
        unset($erantzuna);
        
        $content = "inc/bistak/ariketak/galdera-erantzunak/galdera-erantzunak.php";
        
    }
?>