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
    
    $erantzuna->arrakasta = true;
    
    if ($url_hurrengoa == "akatsa") {
        
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            
            $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
            $id_hizkuntza = isset($_POST["id_hizkuntza"]) ? (int) $_POST["id_hizkuntza"] : 0;
            $hitzak = isset($_POST["hitzak"]) ? json_decode($_POST["hitzak"]) : "";
            
            if ($id_ariketa > 0 && $id_hizkuntza > 0 && $hitzak) {
                
                $sql = "INSERT INTO hitzak_markatu_akatsak (fk_ariketa, fk_hizkuntza)
                        VALUES ($id_ariketa, " . $id_hizkuntza . ")";
                
                if ($dbo->query($sql)) {
                    
                    $id_akatsa = db_taula_azken_id("hitzak_markatu_akatsak");
                    
                    $i = 0;
                    
                    foreach($hitzak as $hitza) {
                        
                        $sql = "INSERT INTO hitzak_markatu_akatsa_hitzak (denbora, zuzena, okerra, fk_akatsa)
                                VALUES (" . $hitza->denbora . ", '" . $hitza->testua . "', '" . $hitza->testua . "', $id_akatsa)";
                        
                        if (!$dbo->query($sql)) {
                            
                            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                            http_response_code(500);
                            
                            $erantzuna->arrakasta = false;
                            $erantzuna->mezua = "Errore bat gertatu da akatsa datu-basean gordetzean.";
                            
                        }
                        
                        $i++;
                        
                    }
                    
                    // SQL guztiak ondo joan badira (bestela arrakasta false izango da).
                    if ($erantzuna->arrakasta) {
                        
                        // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                        http_response_code(200);
                        
                        $erantzuna->arrakasta = true;
                        $erantzuna->id_akatsa = $id_akatsa;
                        
                    }
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da akatsa datu-basean gordetzean.";
                    
                }
                
            } else {
            	
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
                
            }
            
            echo json_encode($erantzuna);
            
            exit();
            
        } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {
            
            parse_str(file_get_contents('php://input'), $para);
            
            $id_ariketa = isset($para["id_ariketa"]) ? (int) $para["id_ariketa"] : 0;
            $id_akatsa = isset($para["id_akatsa"]) ? (int) $para["id_akatsa"] : 0;
            $id_hizkuntza = isset($para["id_hizkuntza"]) ? (int) $para["id_hizkuntza"] : 0;
            
            $zuzeneko_testuak = isset($para["zuzeneko_testuak"]) ? json_decode($para["zuzeneko_testuak"]) : "";
            $okerreko_testuak = isset($para["okerreko_testuak"]) ? json_decode($para["okerreko_testuak"]) : "";
            $denborak = isset($para["denborak"]) ? json_decode($para["denborak"]) : "";
            
            if ($id_ariketa > 0 && $id_hizkuntza > 0 && $id_akatsa > 0 && $zuzeneko_testuak && $okerreko_testuak && $denborak) {
                
                // Akats honen hitz zaharrak ezabatuko ditugu.
                $sql = "DELETE
                        FROM hitzak_markatu_akatsa_hitzak
                        WHERE fk_akatsa = '$id_akatsa'";
                
                if ($dbo->query($sql)) {
                    
                    // Honek arazoak emango ditu testu okerrak eta zuzenak hitz kopuru desberdina dutenean.
                    for ($i = 0; $i < count($okerreko_testuak); $i++) {
                        
                        $sql = "INSERT INTO hitzak_markatu_akatsa_hitzak (denbora, zuzena, okerra, fk_akatsa)
                                VALUES (" . $denborak[$i] . ", '" . $zuzeneko_testuak[$i] . "', '" . $okerreko_testuak[$i] . "', " . $id_akatsa . ")";
                        
                        if (!$dbo->query($sql)) {
                            
                            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                            http_response_code(500);
                            
                            $erantzuna->arrakasta = false;
                            $erantzuna->mezua = "Errore bat gertatu da akatsa datu-basean gordetzean.";
                            
                        }
                        
                    }
                    
                    // SQL guztiak ondo joan badira (bestela arrakasta false izango da).
                    if ($erantzuna->arrakasta) {
                        
                        // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                        http_response_code(200);
                        
                        $erantzuna->arrakasta = true;
                        $erantzuna->id_akatsa = $id_akatsa;
                        
                    }
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da akatsa datu-basean gordetzean.";
                    
                }
                
            } else {
            	
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
                
            }
            
            echo json_encode($erantzuna);
            
            exit();
            
        } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
            
            $id_akatsa = $url->hurrengoa();
            
            if ($id_akatsa) {
                
                // Akatsa eta bere hitz guztiak ezabatu.
                $sql = "DELETE A, B
                        FROM hitzak_markatu_akatsak A
                        INNER JOIN hitzak_markatu_akatsa_hitzak B
                        ON A.id = B.fk_akatsa
                        WHERE A.id = $id_akatsa";
                
                if ($dbo->query($sql)) {
                    
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da arazoa datu-basean gordetzean.";
                    
                }
                
            } else {
            	
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
                
            }
            
            echo json_encode($erantzuna);
            
            exit();
            
        }
        
    } else {
        
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            
            $id_ariketa = $url_hurrengoa;
            
            $id_hizkuntza = isset($_GET["id_hizkuntza"]) ? (int) $_GET["id_hizkuntza"] : 0;
            
            $erantzuna->hitzak_markatu = new stdClass();
            
            if ($id_ariketa > 0 && $id_hizkuntza > 0) {
                
                $sql = "SELECT B.izena, B.azalpena, C.mota, C.bideo_path, C.bideo_mp4, C.bideo_webm, C.audio_path, C.audio_mp3, C.audio_ogg, D.path_azpitituluak, D.azpitituluak, D.hipertranskribapena
                        FROM ariketak AS A
                        INNER JOIN ariketak_hizkuntzak AS B
                        ON A.id = B.fk_elem
                        INNER JOIN ikus_entzunezkoak AS C
                        ON A.fk_ikus_entzunezkoa = C.id
                        INNER JOIN ikus_entzunezkoak_hizkuntzak AS D
                        ON C.id = D.fk_elem
                        WHERE A.id = $id_ariketa AND B.fk_hizkuntza = " . $id_hizkuntza . " AND D.fk_hizkuntza = " . $id_hizkuntza;
                
                $dbo->query($sql);
                
                if ($row = $dbo->emaitza()) {
                    
                    $erantzuna->hitzak_markatu->izena = $row["izena"];
                    $erantzuna->hitzak_markatu->azalpena = $row["azalpena"];
                    
                    $erantzuna->hitzak_markatu->mota = $row["mota"];
                    
                    $erantzuna->hitzak_markatu->bideo_path = $row["bideo_path"];
                    $erantzuna->hitzak_markatu->bideo_mp4 = $row["bideo_mp4"];
                    $erantzuna->hitzak_markatu->bideo_webm = $row["bideo_webm"];
                    
                    $erantzuna->hitzak_markatu->audio_path = $row["audio_path"];
                    $erantzuna->hitzak_markatu->audio_mp3 = $row["audio_mp3"];
                    $erantzuna->hitzak_markatu->audio_ogg = $row["audio_ogg"];
                    
                    $erantzuna->hitzak_markatu->path_azpitituluak = $row["path_azpitituluak"];
                    $erantzuna->hitzak_markatu->azpitituluak = $row["azpitituluak"];
                    $erantzuna->hitzak_markatu->hipertranskribapena = $row["hipertranskribapena"];
                    
                    $erantzuna->hitzak_markatu->akatsak = array();
                    
                    $sql = "SELECT id
                            FROM hitzak_markatu_akatsak
                            WHERE fk_ariketa = $id_ariketa AND fk_hizkuntza = " . $hizkuntza["id"];
                    
                    $emaitza = get_query($sql);
                    
                    foreach ($emaitza as $errenkada) {
                        
                        $tmp_akatsa =  new stdClass();
                        
                        $tmp_akatsa->id = $errenkada['id'];
                        
                        // Hasiera batean akats bakoitzak hitz bat baino gehiago eduki zitzazkeen,
                        // horregatik daude akatsak eta hitzak taula desberdinetan,
                        // baina hitzak arrastatu eta jareginez gehitzekotan hitzak banaka hartzen direnez
                        // orain ez du zentzu handirik.
                        // Badaezpada ere mantendu egingo dugu.
                        $tmp_akatsa->hitzak = array();
                        
                        $sql = "SELECT denbora, zuzena, okerra
                                FROM hitzak_markatu_akatsa_hitzak
                                WHERE fk_akatsa = " . $errenkada['id'] . "
                                ORDER BY denbora ASC";
                        
                        $dbo->query($sql) or die($dbo->ShowError());
                        
                        while ($errenkada = $dbo->emaitza()) {
                            
                            $tmp_hitza = new stdClass();
                            
                            $tmp_hitza->denbora = $errenkada["denbora"];
                            $tmp_hitza->zuzena = $errenkada["zuzena"];
                            $tmp_hitza->okerra = $errenkada["okerra"];
                            
                            $tmp_akatsa->hitzak[] = $tmp_hitza;
                            
                        }
                        
                        $erantzuna->hitzak_markatu->akatsak[] = $tmp_akatsa;
                        
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
            
            $id_ikasgaia = isset($_POST["id_ikasgaia"]) ? (int) $_POST["id_ikasgaia"] : 0;
            $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
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
        
    }
    
?>