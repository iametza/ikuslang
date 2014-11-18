<?php
	switch ($url->hurrengoa()) {
		case "konfigurazioa":
			if($erabiltzailea->get_rola() != 'admin') break;
			require ("inc/bistak/konfigurazioa/konfigurazioa.load.php");
			break;
		case "administrazioa":
			if($erabiltzailea->get_rola() != 'admin') break;
			require ("inc/bistak/administrazioa/administrazioa.load.php");
			break;
		case "irakasleak":
			if($erabiltzailea->get_rola() != 'admin') break;
			require("inc/bistak/irakasleak/irakasleak.load.php");
			break;
		case "mailak":
			if($erabiltzailea->get_rola() != 'admin') break;
			require("inc/bistak/mailak/mailak.load.php");
			break;
		case "oharra-bidali":
            require("inc/bistak/oharrak/oharrak.load.php");
			break;
        case "arbela":
			require("inc/bistak/hasiera/hasiera.load.php");
			break;
		case "logout":
			require ("inc/bistak/logout/logout.load.php");
			break;
		case "dokumentuak":
		    require("inc/bistak/dokumentuak/dokumentuak.load.php");
		    break;
		case "esaldiak-zuzendu":
		    require("inc/bistak/esaldiak_zuzendu/esaldiak_zuzendu.load.php");
		    break;
		case "galdera-erantzunak":
			require("inc/bistak/galdera_erantzunak/galdera_erantzunak.load.php");
			break;
		case "hitzak-markatu":
		    require("inc/bistak/hitzak_markatu/hitzak_markatu.load.php");
		    break;
		case "hutsuneak-bete":
			require("inc/bistak/hutsuneak_bete/hutsuneak_bete.load.php");
			break;
		case "ikasleak":
		    require("inc/bistak/ikasleak/ikasleak.load.php");
		    break;
		case "ikasgelak":
		    require("inc/bistak/ikasgelak/ikasgelak.load.php");
		    break;
		case "ikasgaiak":
		    require("inc/bistak/ikasgaiak/ikasgaiak.load.php");
		    break;
		case "fitxategiak-igo":
		    require("inc/bistak/ikus_entzunezkoak/fitxategiak_igo.load.php");
		    break;
		case "multzokatu":
			require("inc/bistak/multzokatu/multzokatu.load.php");
			break;
		case "ikus-entzunezkoak":
            require("inc/bistak/ikus_entzunezkoak/ikus_entzunezkoak.load.php");
            break;
        default:
			if($erabiltzailea->get_rola() == 'irakaslea')
				require("inc/bistak/hasiera/hasiera.load.php");
			else
				require("inc/bistak/ikus_entzunezkoak/ikus_entzunezkoak.load.php");
			break;
		}
	
	require ("templates/itxura.php");
?>
