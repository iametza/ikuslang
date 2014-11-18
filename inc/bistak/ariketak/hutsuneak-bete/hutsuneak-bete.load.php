<?php
    
    if ($id_ariketa > 0) {
        
        $hutsuneak_bete = new stdClass();
        
        $sql = "SELECT A.id, B.izena, B.azalpena, C.bideo_path, C.bideo_mp4, C.bideo_webm, D.path_azpitituluak, D.azpitituluak, D.hipertranskribapena
                FROM ariketak AS A
                INNER JOIN ariketak_hizkuntzak AS B
                ON A.id = B.fk_elem
                INNER JOIN ikus_entzunezkoak AS C
                ON A.fk_ikus_entzunezkoa = C.id
                INNER JOIN ikus_entzunezkoak_hizkuntzak AS D
                ON C.id = D.fk_elem
                WHERE A.id = $id_ariketa AND B.fk_hizkuntza = " . $hizkuntza["id"] . " AND D.fk_hizkuntza = " . $hizkuntza["id"];
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($row = $dbo->emaitza()) {
            
            $hutsuneak_bete->id = $row["id"];
            $hutsuneak_bete->izena = $row["izena"];
            $hutsuneak_bete->azalpena = $row["azalpena"];
            
            $hutsuneak_bete->bideo_path = $row["bideo_path"];
            $hutsuneak_bete->bideo_mp4 = $row["bideo_mp4"];
            $hutsuneak_bete->bideo_webm = $row["bideo_webm"];
            
            $hutsuneak_bete->path_azpitituluak = $row["path_azpitituluak"];
            $hutsuneak_bete->azpitituluak = $row["azpitituluak"];
            $hutsuneak_bete->hipertranskribapena = json_encode($row["hipertranskribapena"]);
            
            $hutsuneak_bete->hutsuneak = array();
            
            $sql = "SELECT id
                    FROM hutsuneak_bete_hutsuneak
                    WHERE fk_ariketa = $id_ariketa AND fk_hizkuntza = " . $hizkuntza["id"];
            
            $emaitza = get_query($sql);
            
            foreach ($emaitza as $errenkada) {
                
                $tmp_hutsunea = new stdClass();
                
                $tmp_hutsunea->id = $errenkada["id"];
                
                $tmp_hutsunea->hitzak = array();
                
                $sql = "SELECT denbora, testua
                        FROM hutsuneak_bete_hutsunea_hitzak
                        WHERE fk_hutsunea = " . $errenkada['id'] . "
                        ORDER BY denbora ASC";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                while ($errenkada = $dbo->emaitza()) {
                    
                    $tmp_hitza = new stdClass();
                    
                    $tmp_hitza->denbora = $errenkada["denbora"];
                    $tmp_hitza->testua = $errenkada["testua"];
                    
                    $tmp_hutsunea->hitzak[] = $tmp_hitza;
                    
                }
                
                $hutsuneak_bete->hutsuneak[] = $tmp_hutsunea;
                
            }
            
            $sql = "SELECT C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $id_ariketa AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $hutsuneak_bete->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $hutsuneak_bete->dokumentuak[] = $tmp_dokumentua;
            }
        }
        
        $content = "inc/bistak/ariketak/hutsuneak-bete/hutsuneak-bete.php";
        
    }
?>