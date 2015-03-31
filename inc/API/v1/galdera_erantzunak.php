<?php
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    // Hau gabe ezin nuen zerrenda eskuratu AngularJS aplikaziotik, URL desberdinak baitzituzten.
	// Errore mezu hau agertzen zen Chromium-en:
	// Failed to load resource: the server responded with a status of 404 (Not Found) 
	// XMLHttpRequest cannot load http://192.168.2.174/argia-multimedia-zerbitzaria/zerrenda. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access.
	header('Access-Control-Allow-Origin: *');
    
    $url_hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        
        $id_ariketa = $url_hurrengoa;
        
        $id_hizkuntza = isset($_GET["id_hizkuntza"]) ? (int) $_GET["id_hizkuntza"] : 0;
        
        $erantzuna->galdera_erantzunak = new stdClass();
        
        if ($id_ariketa > 0 && $id_hizkuntza > 0) {
            
            $sql = "SELECT B.izena, B.azalpena
                    FROM ariketak AS A
                    JOIN ariketak_hizkuntzak AS B
                    ON A.id = B.fk_elem
                    WHERE A.id = " . $id_ariketa . " AND B.fk_hizkuntza = " . $id_hizkuntza . "";
            
            if ($dbo->query($sql)) {
                
                $row = $dbo->emaitza();
                
                $erantzuna->galdera_erantzunak->izena = $row["izena"];
                $erantzuna->galdera_erantzunak->azalpena = $row["azalpena"];
                
                $sql = "SELECT B.mota, B.bideo_path, B.bideo_mp4, bideo_webm, B.audio_path, B.audio_mp3, B.audio_ogg, C.hipertranskribapena
                        FROM ariketak AS A
                        INNER JOIN ikus_entzunezkoak AS B
                        ON A.fk_ikus_entzunezkoa = B.id
                        INNER JOIN ikus_entzunezkoak_hizkuntzak AS C
                        ON B.id = C.fk_elem
                        WHERE A.id = $id_ariketa AND C.fk_hizkuntza = $id_hizkuntza";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                if ($row = $dbo->emaitza()) {
                    
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa = new stdClass();
                    
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->mota = $row["mota"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->bideo_path = $row["bideo_path"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->bideo_mp4 = $row["bideo_mp4"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->bideo_webm = $row["bideo_webm"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->audio_path = $row["audio_path"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->audio_mp3 = $row["audio_mp3"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->audio_ogg = $row["audio_ogg"];
                    $erantzuna->galdera_erantzunak->ikus_entzunezkoa->hipertranskribapena = $row["hipertranskribapena"];
                    
                }
                
                $lag = get_query ("SELECT B.denbora, B.galdera as galdera, B.fk_elem
                                   FROM galdera_erantzunak_galderak AS A, galdera_erantzunak_galderak_hizkuntzak as B
                                   WHERE A.fk_ariketa = '$id_ariketa'
                                   AND B.fk_elem = A.id
                                   AND B.fk_hizkuntza = '$id_hizkuntza'
                                   ORDER BY B.denbora ASC"
                );
                
                // Galdera objektuentzako arraya sortu
                $erantzuna->galdera_erantzunak->galderak = array();
                
                // Amaierako galderen objekuentzako arraya sortu.
                $erantzuna->galdera_erantzunak->amaierako_galderak = array();
                
                if (count ($lag) > 0) {
                    
                    foreach ($lag as $l) {
                        
                        // Galdera bakoitzaren datuak (galdera eta erantzunak) objektu batean gordeko ditugu.
                        $tmp_galdera = new stdClass();
                        
                        // Galderaren testua eta noiz bistaratu behar den.
                        $tmp_galdera->galdera = $l["galdera"];
                        $tmp_galdera->denbora = $l["denbora"];
                        
                        // Galdera honi dagozkion erantzunak eskuratu
                        $erantzunak_emaitza = get_query ("SELECT A.id, A.zuzena as zuzena, B.erantzuna as erantzuna " .
                                                         "FROM galdera_erantzunak_galdera_erantzunak AS A, galdera_erantzunak_galdera_erantzunak_hizkuntzak AS B " .
                                                         "WHERE A.fk_galdera = '$l[fk_elem]' " .
                                                         "AND A.id = B.fk_elem " .
                                                         "AND B.fk_hizkuntza = '$id_hizkuntza'"
                        );
                        
                        if (count ($erantzunak_emaitza) > 0) {
                            
                            // Erantzunak bilduko dituen arraya
                            $tmp_galdera->erantzunak = array();
                            
                            foreach ($erantzunak_emaitza as $erantzuna_emaitza) {
                                
                                $tmp_erantzuna = new stdClass();
                                
                                $tmp_erantzuna->id = $erantzuna_emaitza["id"];
                                $tmp_erantzuna->erantzuna = $erantzuna_emaitza["erantzuna"];
                                $tmp_erantzuna->zuzena = $erantzuna_emaitza["zuzena"];
                                
                                array_push($tmp_galdera->erantzunak, $tmp_erantzuna);
                            }
                        }
                        
                        // Hasi aurreko galderak baleude $galdera->denbora == '0'.
                        
                        // Amaierako galdera bada...
                        if ($tmp_galdera->denbora == '-1') {
                            
                            array_push($erantzuna->galdera_erantzunak->amaierako_galderak, $tmp_galdera);
                            
                        // Galdera arrunta bada...
                        } else {
                            
                            // Galdera array-ra gehitu
                            array_push($erantzuna->galdera_erantzunak->galderak, $tmp_galdera);
                            
                        }
                        
                    }
                }
                
                // Bezeroari eskaera ondo joan dela jakinarazi.
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da datu-basetik datuak eskuratzean.";
                
            }
            
        }  else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Ezin izan dira datuak eskuratu, eskaera ez da behar bezala egin.";
            
        }
        
    } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
        
        $erantzuna->arrakasta = true;
        
        $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
        $id_ikasgaia = isset($_POST["id_ikasgaia"]) ? (int) $_POST["id_ariketa"] : 0;
        $id_ikaslea = isset($_POST["id_ikaslea"]) ? (int) $_POST["id_ikaslea"] : 0;
        $zuzenak = isset($_POST["zuzenak"]) ? $_POST["zuzenak"] : array();
        $okerrak = isset($_POST["okerrak"]) ? $_POST["okerrak"] : array();
        
        $datuak['id_ikasgaia'] = $id_ikasgaia;
        $datuak['id_ariketa'] = $id_ariketa;
        $datuak['id_ikaslea'] = $id_ikaslea;
        $datuak['zuzenak'] = $zuzenak;
        $datuak['okerrak'] = $okerrak;
        
        if ($id_ariketa > 0 && $id_ikasgaia > 0 && $id_ikaslea > 0) {
            
            if(!gorde_emaitzak($datuak)) {
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da emaitza datu-basean gordetzean.";
                
            } else {
                
                http_response_code(200);
                
            }
            
        } else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
            
        }
        
    } else {
        
        // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
        // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
        http_response_code(400);
        
        $erantzuna->arrakasta = false;
        $erantzuna->mezua = "Ezin izan dira datuak eskuratu, eskaera ez da behar bezala egin.";
        
    }
    
    echo json_encode($erantzuna);
    
    exit();
    
?>