<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    $hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        
        if ($hurrengoa != "") {
            
            if ((int) $hurrengoa > 0) {
                
                $sql = "SELECT A.mota, A.bideo_path, A.bideo_jatorrizkoa, A.bideo_mp4, A.bideo_webm, A.audio_path, A.audio_jatorrizkoa, A.audio_mp3, A.audio_ogg
                        FROM ikus_entzunezkoak AS A
                        WHERE id = " . $hurrengoa;
                
                if ($dbo->query($sql)) {
                    
                    $emaitza = $dbo->emaitza();
                    
                    $erantzuna->mota = $emaitza["mota"];
                    $erantzuna->bideo_path = $emaitza["bideo_path"];
                    $erantzuna->bideo_jatorrizkoa = $emaitza["bideo_jatorrizkoa"];
                    $erantzuna->bideo_mp4 = $emaitza["bideo_mp4"];
                    $erantzuna->bideo_webm = $emaitza["bideo_webm"];
                    $erantzuna->audio_path = $emaitza["audio_path"];
                    $erantzuna->audio_jatorrizkoa = $emaitza["audio_jatorrizkoa"];
                    $erantzuna->audio_mp3 = $emaitza["audio_mp3"];
                    $erantzuna->audio_ogg = $emaitza["audio_ogg"];
                    
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                }  else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da datu-basetik bideoaren datuak eskuratzean.";
                    
                }
                
            }  else {
                
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Bideoaren datuen eskaera ez da behar bezala egin.";
                
            }
            
        } else {
            
            $bilaketa = isset($_GET["testua"]) ? testu_formatua_sql($_GET["testua"]) : "";
            
            // array_filter gabe etiketarik ez zegoenean [""] itzultzen zuen explode-k.
            $etiketak = isset($_GET["etiketak"]) ? array_filter(explode(",", testu_formatua_sql($_GET["etiketak"]))) : null;
            
            $etiketa_kopurua = count($etiketak);
            
            $etiketak_baldintza = " ";
            $etiketak_join = " ";
            
            $etiketak_having = " ";
            
            if ($etiketa_kopurua > 0) {
                
                $etiketak_baldintza = " AND D.izena IN (";
                
                for ($i = 0; $i < $etiketa_kopurua; $i++) {
                    
                    $etiketak_baldintza = $etiketak_baldintza . "'" . $etiketak[$i] . "'";
                    
                    if ($i < $etiketa_kopurua - 1) {
                        
                        $etiketak_baldintza = $etiketak_baldintza . ", ";
                        
                    }
                    
                }
                
                $etiketak_join = " INNER JOIN ikus_entzunezkoak_etiketak AS C
                                   ON A.id = C.fk_ikus_entzunezkoa
                                   INNER JOIN etiketak AS D
                                   ON C.fk_etiketa = D.id ";
                
                $etiketak_baldintza = $etiketak_baldintza . ") ";
                
                $etiketak_having = " HAVING COUNT(DISTINCT D.izena) = " . $etiketa_kopurua;
            }
            
            $sql = "SELECT A.id, B.izenburua
                    FROM ikus_entzunezkoak AS A
                    INNER JOIN ikus_entzunezkoak_hizkuntzak AS B
                    ON A.id = B.fk_elem
                    $etiketak_join
                    WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
                    AND B.izenburua LIKE '%" . $bilaketa . "%'
                    $etiketak_baldintza
                    GROUP BY A.id
                    $etiketak_having
                    ORDER BY B.izenburua ASC";
            
            //var_dump($sql);
            
            if ($dbo->query($sql)) {
                
                $erantzuna->ikus_entzunezkoak = array();
                
                while ($emaitza = $dbo->emaitza()) {
                    
                    $tmp_ikus_entzunezkoa = new stdClass();
                    
                    $tmp_ikus_entzunezkoa->id = $emaitza["id"];
                    $tmp_ikus_entzunezkoa->izenburua = $emaitza["izenburua"];
                    
                    $erantzuna->ikus_entzunezkoak[] = $tmp_ikus_entzunezkoa;
                    
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                }
                
                // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                http_response_code(200);
                    
                $erantzuna->arrakasta = true;
                
            } else {
                
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da datu-basetik ikus-entzunezkoen datuak eskuratzean.";
                
            }
        }
        
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>