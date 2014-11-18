<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    // PHP < 5.4.0 -> Ez dago http_response_code funtziorik.
    require_once("http_response_code.php");
    
    // Funtzio hau ere behar dut baina alde publikoan ez dago, adminean bakarrik.
    require_once("hizkuntza_idak.php");
    
    $url_hurrengoa = $url->hurrengoa();
    
    switch ($url_hurrengoa) {
        
        case "v1":
            
            require("inc/API/v1/api.v1.load.php");
            break;
        
        default:
            
            // Bezeroari eskaera gaizki osatuta zegoela jakinarazi.
            http_response_code(400);
            exit();
            
            break;
    }
?>