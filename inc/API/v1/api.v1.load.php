<?php

	// Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
	
	$url_hurrengoa = $url->hurrengoa();
    
    switch ($url_hurrengoa) {
        
        case "dokumentuak":
            
            require("inc/API/v1/dokumentuak.php");
            break;
        
        case "etiketak":
            
            require("inc/API/v1/etiketak.php");
            break;
        
        case "hipertranskribapenak":
            
            require("inc/API/v1/hipertranskribapenak.php");
            break;
        
        case "hitzak-markatu":
            
            require("inc/API/v1/hitzak_markatu.php");
            break;
        
        case "hizkuntzak":
            
            require("inc/API/v1/hizkuntzak.php");
            break;
        
        case "hutsuneak-bete":
            
            require("inc/API/v1/hutsuneak_bete.php");
            break;
        
        case "galdera-erantzunak":
            
            require("inc/API/v1/galdera_erantzunak.php");
            break;
        
        case "ikus-entzunezkoak":
            
            require("inc/API/v1/ikus_entzunezkoak.php");
            break;
        
        case "multzokatu":
            
            require("inc/API/v1/multzokatu.php");
            break;
        
        case "esaldiak-ordenatu":
            
            require("inc/API/v1/esaldiak_ordenatu.php");
            break;
        
        case "ariketak":
            
            require("inc/API/v1/ariketak.php");
            break;
        
        case "login":
            
            require("inc/API/v1/login.php");
            break;
        
		case "erregistroa":
			
			require("inc/API/v1/erregistroa.php");
			break;
        
        case "bidaliOharra":
			require("inc/API/v1/bidaliOharra.php");
			break;
        
        default:
            
            // Bezeroari eskaera gaizki osatuta zegoela jakinarazi.
            http_response_code(400);
            exit();
            
            break;
    }
    
    exit();
?>
