<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    // Hau gabe ezin nuen zerrenda eskuratu AngularJS aplikaziotik, URL desberdinak baitzituzten.
	// Errore mezu hau agertzen zen Chromium-en:
	// Failed to load resource: the server responded with a status of 404 (Not Found) 
	// XMLHttpRequest cannot load http://192.168.2.174/argia-multimedia-zerbitzaria/zerrenda. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access.
	header('Access-Control-Allow-Origin: *');
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
        
        $e_posta = isset($_POST["e_posta"]) ? testu_formatua_sql($_POST["e_posta"]) : "";
        $pasahitza = isset($_POST["pasahitza"]) ? testu_formatua_sql($_POST["pasahitza"]) : "";
        
        $sql = "SELECT id, izena, abizenak, gatza, pasahitza
                FROM ikasleak
                WHERE TRIM(pasahitza) <> '' AND TRIM(e_posta) <> '' AND e_posta = '" . $e_posta . "'";
        
		$dbo->query($sql) or die($dbo->ShowError());
		
        if ($dbo->emaitza_kopurua() == 1){
            
			$row = $dbo->emaitza();
            
			if ($row["pasahitza"] == hash("sha256", $row["gatza"] . $pasahitza)) {
                
                $erantzuna->arrakasta = true;
                
				$erantzuna->id_erabiltzailea = $row["id"];
                $erantzuna->izena = $row["izena"];
                $erantzuna->abizenak = $row["abizenak"];
                
                http_response_code(200);
                
			} else {
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "E-posta edo pasahitza ez dira zuzenak.";
                
                http_response_code(400);
                
            }
            
		}  else {
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "E-posta edo pasahitza ez dira zuzenak.";
            
            http_response_code(400);
            
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