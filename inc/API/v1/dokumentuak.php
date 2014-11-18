<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    $hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        
        if ($hurrengoa != "") {
            
            if ((int) $hurrengoa > 0) {
                
                $sql = "SELECT A.path_dokumentua, A.dokumentua
                        FROM dokumentuak AS A
                        WHERE id = " . $hurrengoa;
                
                if ($dbo->query($sql)) {
                    
                    $emaitza = $dbo->emaitza();
                    
                    $erantzuna->path_dokumentua = $emaitza["path_dokumentua"];
                    $erantzuna->dokumentua = $emaitza["dokumentua"];
                    
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                }  else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da datu-basetik dokumentuaren datuak eskuratzean.";
                    
                }
                
            }  else {
                
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Dokumentuaren datuen eskaera ez da behar bezala egin.";
                
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
                
                $etiketak_join = " INNER JOIN dokumentuak_etiketak AS C
                                   ON A.id = C.fk_dokumentua
                                   INNER JOIN etiketak AS D
                                   ON C.fk_etiketa = D.id ";
                
                $etiketak_baldintza = $etiketak_baldintza . ") ";
                
                $etiketak_having = " HAVING COUNT(DISTINCT D.izena) = " . $etiketa_kopurua;
            }
            
            $sql = "SELECT A.id, A.path_dokumentua, A.dokumentua, B.izenburua
                    FROM dokumentuak AS A
                    INNER JOIN dokumentuak_hizkuntzak AS B
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
                
                $erantzuna->dokumentuak = array();
                
                while ($emaitza = $dbo->emaitza()) {
                    
                    $tmp_dokumentua = new stdClass();
                    
                    $tmp_dokumentua->id = $emaitza["id"];
                    $tmp_dokumentua->path_dokumentua = $emaitza["path_dokumentua"];
                    $tmp_dokumentua->dokumentua = $emaitza["dokumentua"];
                    $tmp_dokumentua->izenburua = $emaitza["izenburua"];
                    
                    $erantzuna->dokumentuak[] = $tmp_dokumentua;
                    
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
                    $erantzuna->mezua = "Errore bat gertatu da datu-basetik dokumentuen datuak eskuratzean.";
                
            }
        }
        
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>