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
    
    if ($url_hurrengoa == "hutsunea") {
        
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            
            $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
            $id_hizkuntza = isset($_POST["id_hizkuntza"]) ? (int) $_POST["id_hizkuntza"] : 0;
            $hitzak = isset($_POST["hitzak"]) ? json_decode($_POST["hitzak"]) : "";
            
            if ($id_ariketa > 0 && $id_hizkuntza > 0 && $hitzak) {
                
                $sql = "INSERT INTO hutsuneak_bete_hutsuneak (fk_ariketa, fk_hizkuntza)
                        VALUES ($id_ariketa, " . $id_hizkuntza . ")";
                
                if ($dbo->query($sql)) {
                    
                    $id_hutsunea = db_taula_azken_id("hutsuneak_bete_hutsuneak");
                    
                    foreach($hitzak as $hitza) {
                        
                        $sql = "INSERT INTO hutsuneak_bete_hutsunea_hitzak (denbora, testua, fk_hutsunea)
                                VALUES (" . $hitza->denbora . ", '" . $hitza->testua . "', $id_hutsunea)";
                        
                        if ($dbo->query($sql)) {
                            
                            // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                            http_response_code(200);
                            
                            $erantzuna->arrakasta = true;
                            $erantzuna->id_hutsunea = $id_hutsunea;
                            
                        } else {
                            
                            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                            http_response_code(500);
                            
                            $erantzuna->arrakasta = false;
                            $erantzuna->mezua = "Errore bat gertatu da arazoa datu-basean gordetzean.";
                            
                        }
                    }
                    
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
            
        } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
            
            $id_hutsunea = (int) $url->hurrengoa();
            
            if ($id_hutsunea > 0) {
                    
                // Hutsunearen hitzak ezabatuko ditugu.
                $sql = "DELETE A, B
                        FROM hutsuneak_bete_hutsuneak A
                        INNER JOIN hutsuneak_bete_hutsunea_hitzak B
                        ON A.id = B.fk_hutsunea
                        WHERE A.id = $id_hutsunea";
                
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
            
            $erantzuna->hutsuneak_bete = new stdClass();
            
            if ($id_ariketa > 0 && $id_hizkuntza > 0) {
                
                $sql = "SELECT B.izena, B.azalpena, C.bideo_path, C.bideo_mp4, C.bideo_webm, D.path_azpitituluak, D.azpitituluak, D.hipertranskribapena
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
                    
                    $erantzuna->hutsuneak_bete->izena = $row["izena"];
                    $erantzuna->hutsuneak_bete->azalpena = $row["azalpena"];
                    
                    $erantzuna->hutsuneak_bete->bideo_path = $row["bideo_path"];
                    $erantzuna->hutsuneak_bete->bideo_mp4 = $row["bideo_mp4"];
                    $erantzuna->hutsuneak_bete->bideo_webm = $row["bideo_webm"];
                    
                    $erantzuna->hutsuneak_bete->path_azpitituluak = $row["path_azpitituluak"];
                    $erantzuna->hutsuneak_bete->azpitituluak = $row["azpitituluak"];
                    $erantzuna->hutsuneak_bete->hipertranskribapena = $row["hipertranskribapena"];
                    
                    $erantzuna->hutsuneak_bete->hutsuneak = array();
                    
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
                        
                        $erantzuna->hutsuneak_bete->hutsuneak[] = $tmp_hutsunea;
                        
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