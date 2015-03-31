<?php
    
    if ($id_ariketa > 0) {
        
        $hitzak_markatu = new stdClass();
        
        $sql = "SELECT B.izena, B.azalpena, C.id AS id_ikus_entzunezkoa, C.mota, C.bideo_path, C.bideo_mp4, C.bideo_webm, C.audio_path, C.audio_mp3, C.audio_ogg, D.path_azpitituluak, D.azpitituluak, D.hipertranskribapena
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
            
            $hitzak_markatu->izena = $row["izena"];
            $hitzak_markatu->azalpena = $row["azalpena"];
            
            $hitzak_markatu->id_ikus_entzunezkoa = $row["id_ikus_entzunezkoa"];
            
            $hitzak_markatu->mota = $row["mota"];
            
            $hitzak_markatu->audio_path = $row["audio_path"];
            $hitzak_markatu->audio_mp3 = $row["audio_mp3"];
            $hitzak_markatu->audio_ogg = $row["audio_ogg"];
            
            $hitzak_markatu->bideo_path = $row["bideo_path"];
            $hitzak_markatu->bideo_mp4 = $row["bideo_mp4"];
            $hitzak_markatu->bideo_webm = $row["bideo_webm"];
            
            $hitzak_markatu->path_azpitituluak = $row["path_azpitituluak"];
            $hitzak_markatu->azpitituluak = $row["azpitituluak"];
            $hitzak_markatu->hipertranskribapena = json_encode($row["hipertranskribapena"]);
            
            $hitzak_markatu->akatsak = array();
            
            $sql = "SELECT id
                    FROM hitzak_markatu_akatsak
                    WHERE fk_ariketa = $id_ariketa AND fk_hizkuntza = " . $hizkuntza["id"];
            
            $emaitza = get_query($sql);
            
            foreach($emaitza as $errenkada) {
                
                $tmp_akatsa = new stdClass();
                
                $tmp_akatsa->id = $errenkada['id'];
                
                // Hasiera batean akats bakoitzak hitz bat baino gehiago eduki zitzazkeen,
                // horregatik daude akatsak eta hitzak taula desberdinetan,
                // baina hitzak arrastatu eta jareginez gehitzekotan hitzak banaka hartzen direnez
                // orain ez du zentzu handirik.
                // Badaezpada ere mantendu egingo dugu.
                $tmp_akatsa->hitzak = array();
                
                $sql = "SELECT denbora, zuzena, okerra
                        FROM hitzak_markatu_akatsa_hitzak
                        WHERE fk_akatsa = " . $errenkada['id'];
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                while ($errenkada = $dbo->emaitza()) {
                    
                    $tmp_hitza = new stdClass();
                    
                    $tmp_hitza->denbora = $errenkada["denbora"];
                    $tmp_hitza->zuzena = $errenkada["zuzena"];
                    $tmp_hitza->okerra = $errenkada["okerra"];
                    
                    $tmp_akatsa->hitzak[] = $tmp_hitza;
                    
                }
                
                $hitzak_markatu->akatsak[] = $tmp_akatsa;
                
            }
            
            $sql = "SELECT C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $id_ariketa AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $hitzak_markatu->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $hitzak_markatu->dokumentuak[] = $tmp_dokumentua;
            }
            
        }
        
        $content = "inc/bistak/ariketak/hitzak-markatu/hitzak-markatu.php";
        
    }
?>