<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    $hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        
        // Hizkuntza baten datuak itzuli behar dira.
        if ($hurrengoa != "") {
            
            $h_id = (int) $hurrengoa;
            
            if ($h_id > 0) {
                
                $sql = "SELECT id, izena, orden
                        FROM hizkuntzak
                        WHERE id = " . $h_id;
                
                if ($dbo->query($sql)) {
                    
                    $emaitza = $dbo->emaitza();
                    
                    $erantzuna->id = $emaitza["id"];
                    $erantzuna->izena = $emaitza["izena"];
                    $erantzuna->orden = $emaitza["orden"];
                    
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da datu-basetik hizkuntzaren datuak eskuratzean.";
                    
                }
                
            }  else {
                
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Hizkuntzaren datuen eskaera ez da behar bezala egin.";
                
            }
            
        // Hizkuntza guztien datuak itzuli behar dira.
        } else {
            
            $sql = "SELECT id, izena, orden
                    FROM hizkuntzak";
            
            if ($dbo->query($sql)) {
                
                $erantzuna->hizkuntzak = array();
                
                while($emaitza = $dbo->emaitza()) {
                    
                    $tmp_hizkuntza = new stdClass();
                    
                    $tmp_hizkuntza->id = $emaitza["id"];
                    $tmp_hizkuntza->izena = $emaitza["izena"];
                    $tmp_hizkuntza->orden = $emaitza["orden"];
                    
                    $erantzuna->hizkuntzak[] = $tmp_hizkuntza;
                    
                }
                
                // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            }  else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da datu-basetik hizkuntzen datuak eskuratzean.";
                
            }
            
        }
    }
    
    echo json_encode($erantzuna);
    
    exit();

?>