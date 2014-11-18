<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");
    
    $url_hurrengoa = $url->hurrengoa();
    
    // APIari egindako dei bat bada, dagokion lekura bidali.
    if ($url_hurrengoa == "API") {
        
        require("inc/API/api.load.php");
        
    }
    
    // Erabiltzaileak saioa hasi gabe badauka saioa hasteko orrira bidaliko dugu.
    if (!$erabiltzailea->logged()) {
		
        if ($url_hurrengoa == "ahaztu") {
            
            require("inc/bistak/ahaztu/ahaztu.load.php");
            
        } else {
            
            require("inc/bistak/login/login.load.php");
            
        }
        
        exit();
        
	}
    
	switch ($url_hurrengoa) {
        
        case "amaitu-saioa":
            require ("inc/bistak/logout/logout.load.php");
            break;
        case "jquery":
            require ("inc/bistak/jquery/jquery.load.php");
            break;
        case $hto->nice("kuki_zer_dira"):
            require ("inc/bistak/cookie/cookie.load.php");
            break;
        case "ariketa":
            require("inc/bistak/ariketak/ariketak.load.php");
            break;
        case "ezarpenak":
            require("inc/bistak/ezarpenak/ezarpenak.load.php");
            break;
        case "ahaztu":
            require("inc/bistak/ahaztu/ahaztu.load.php");
            break;
        default:
            require("inc/bistak/nire_txokoa/nire_txokoa.load.php");
            break;
        
    }
	
	require("templates/itxura.php");
    
	exit();
?>
