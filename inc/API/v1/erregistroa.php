<?php
	// Hau gabe ezin nuen zerrenda eskuratu AngularJS aplikaziotik, URL desberdinak baitzituzten.
	// Errore mezu hau agertzen zen Chromium-en:
	// Failed to load resource: the server responded with a status of 404 (Not Found) 
	// XMLHttpRequest cannot load http://192.168.2.174/argia-multimedia-zerbitzaria/zerrenda. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access.
	header('Access-Control-Allow-Origin: *');
	
	$erantzuna = new stdClass();
	
    $mota = isset($_POST["mota"]) ? testu_formatua_sql($_POST["mota"]) : "";
	$id_gailua = isset($_POST["id_gailua"]) ? testu_formatua_sql($_POST["id_gailua"]) : "";
	$id_ikaslea = isset($_POST["id_ikaslea"]) ? testu_formatua_sql($_POST["id_ikaslea"]) : "";
    
    if ($mota && $id_gailua && $id_ikaslea) {
		
        $sql = "SELECT id
                FROM alerta_eskaerak
                WHERE id_ikaslea = '$id_ikaslea'";
        
        if ($dbo->query($sql)) {
            
            if ($errenkada = $dbo->emaitza()) {
                
                $id_alerta_eskaera = $errenkada["id"];
                
                // Azken erregistroaren data gorde.
                $sql = "UPDATE alerta_eskaerak
                        SET azken_data = '" . date("Y-m-d H:i:s") . "'
                        WHERE id_ikaslea = '$id_ikaslea'";
                
                if ($dbo->query($sql)) {
                    
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da alerta-eskaera datu-basean gordetzean.";
                    
                }
                
            // Bestela alerta_eskaerak taulan gorde behar dugu ikaslea.
            } else {
                
                $sql  = "INSERT INTO alerta_eskaerak (lehen_data, mota, id_gailua, id_ikaslea)
                         VALUES ('" . date("Y-m-d H:i:s") . "', '$mota', '$id_gailua', '$id_ikaslea')";
                
                if ($dbo->query($sql)) {
                   
                    // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                    http_response_code(200);
                    
                    $erantzuna->arrakasta = true;
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da alerta-eskaera datu-basean gordetzean.";
                    
                }
                
            }
            
        } else {
            
            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
            http_response_code(500);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Errore bat gertatu da alerta-eskaera datu-basean gordetzean.";
            
        }
        
	} else {
		
		// Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
		// http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
		http_response_code(400);
		
		$erantzuna->arrakasta = false;
		$erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
		
	}
	
	echo json_encode($erantzuna);
?>
