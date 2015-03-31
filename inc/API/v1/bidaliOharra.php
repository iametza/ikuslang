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
        $gaia = isset($_POST["gaia"]) ? testu_formatua_sql($_POST["gaia"]) : "";
        $mezua= isset($_POST["mezua"]) ? testu_formatua_sql($_POST["mezua"]) : "";
        
        
        if (strlen($e_posta) > 0 && strlen($gaia) && strlen($mezua) > 0) {
            
            if (mail($e_posta, $gaia, $mezua)) {
                
                // Bezeroari eskaera ondo joan dela jakinarazi.
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da e-posta bidaltzean.";
                
            }
            
        } else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Ezin izan da mezua bidali, eskaera ez da behar bezala egin.";
            
        }
        
    } else {
        
        // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
        // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
        http_response_code(400);
        
        $erantzuna->arrakasta = false;
        $erantzuna->mezua = "Ezin izan da mezua bidali, eskaera ez da behar bezala egin.";
        
    }
    
    echo json_encode($erantzuna);
    
    exit();
    
?>