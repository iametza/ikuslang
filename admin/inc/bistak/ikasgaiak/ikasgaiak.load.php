<?php
    
	// kargatu modeloa
	require('../inc/modeloak/ikasgaia_modeloa.php');
	$IkasgaiaModeloa = new IkasgaiaModeloa();
	
    $url_base = URL_BASE_ADMIN . "ikasgaiak/";
    
    define("DOKUMENTUEN_PATH", "ikasgaiak/");
    
    $menu_aktibo = "ikasgaiak";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	// erregistro datuak prestatu
	$erregistro_datuak['elementu_mota'] = 'ikasgaia';
	$erregistro_datuak['fk_sortze_erabiltzailea'] = $erabiltzailea->get_id();
	$erregistro_datuak['fk_aldatze_erabiltzailea'] = $erabiltzailea->get_id();
    
    $hurrengoa = $url->hurrengoa();
	
	if ($hurrengoa == "emaitzak"){
		$edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
		$fk_ikasgela = isset ($_GET["fk_ikasgela"]) ? (int) $_GET["fk_ikasgela"] : 0;
		$url_param .= "&fk_ikasgela=".$fk_ikasgela;
		require("emaitzak.load.php");		
		return;
	}
	
    
    if ($hurrengoa === "form") {
        
	$edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
	// jaso behar dugu nondik gatozen
	$fk_ikasgela = isset ($_GET["fk_ikasgela"]) ? (int) $_GET["fk_ikasgela"] : 0;
	$url_param .= "&fk_ikasgela=".$fk_ikasgela;
	// Dokumentu bat eta bere datu guztiak ezabatu behar badira
	if (isset($_GET["ezab_id"])) {
		
		$IkasgaiaModeloa->delete($_GET['ezab_id']);
		// Ikasgai honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
		Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ikasgaiak_etiketak');
	
		// Berbideratu. Ikasgelaren zerrendara berbideratu
		header("Location: " . URL_BASE_ADMIN . "ikasgelak/form?edit_id=".$fk_ikasgela."#ikasgaiak");
		exit;
		   
			
	}
        
        
        // Inserciones o modificaciones
	if (isset ($_POST["gorde"])) {
        
		// Formularioko datuak eskuratuko ditugu.
	    $datuak['id'] = testu_formatua_sql($_POST["edit_id"]);
	    $datuak['hasiera_data'] = testu_formatua_sql($_POST["hasiera_data"]);
	    $datuak['bukaera_data'] = testu_formatua_sql($_POST["bukaera_data"]);
	    $datuak['fk_ikasgela'] = testu_formatua_sql($_POST["fk_ikasgela"]);
      
		$edit_id = $IkasgaiaModeloa->save($datuak);
		    
		// Hizkuntza bakoitzeko balioak gordeko ditugu.
		foreach (hizkuntza_idak() as $h_id) {
			$datuak_hizkuntzak = array();
			
			$datuak_hizkuntzak['fk_elem'] = $edit_id;
			$datuak_hizkuntzak['fk_hizkuntza'] = $h_id;
			$datuak_hizkuntzak['izenburua'] = isset($_POST["izenburua_$h_id"]) ? testu_formatua_sql($_POST["izenburua_$h_id"]) : "";
			$datuak_hizkuntzak['azalpena'] = isset($_POST["azalpena_$h_id"]) ? testu_formatua_sql($_POST["azalpena_$h_id"]) : "";
			
			$IkasgaiaModeloa->save_hizkuntza_datuak($datuak_hizkuntzak);
			
			// Ikasgai honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
			Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ikasgaiak_etiketak');
			
			// Etiketak gordeko ditugu orain.
			Etiketak::gordeElementuarenEtiketak($dbo, $edit_id, $h_id, testu_formatua_sql($_POST["hidden-etiketak_$h_id"]), 'ikasgaiak_etiketak');
		}
	    
		// Ariketen datuak gorde
		$datuak['ariketak'] = $_POST["ariketak"];
		// 1. ezabatu, 2.gorde
		$sql = "DELETE FROM ikasgaiak_ariketak WHERE fk_ikasgaia =".$edit_id;
		$dbo->query($sql) or die($dbo->ShowError());
		foreach($datuak['ariketak'] as $ariketa){
				$ariketa = trim($ariketa);
				$sql = "INSERT INTO ikasgaiak_ariketak (fk_ikasgaia, fk_ariketa)
					VALUES ($edit_id, $ariketa)";
				$dbo->query($sql) or die($dbo->ShowError());
			}
		
		//erregistro datuak gorde
		$erregistro_datuak['fk_elementua'] = $edit_id;
		save_erregistro_datuak($erregistro_datuak);
        
        // Berbideratu. Ikasgelaren zerrendara berbideratu
        header("Location: " . URL_BASE_ADMIN . "ikasgaiak/form?edit_id=$edit_id&fk_ikasgela=".$fk_ikasgela);
		exit;
    }
   
	// ariketa_idak init
	$ariketa_idak = array();
		
	$ikasgaia = $IkasgaiaModeloa->get($edit_id);
		
        
    if ($ikasgaia) {
        
			if(!empty($ikasgaia->ariketak)){
				foreach($ikasgaia->ariketak as $ariketa){
					$ariketa_idak[] = $ariketa['id'];
				}
			}
	
            
        }else{ //ez badago db-an, fk_ikasgela url-tik hartu
	    $ikasgaia = new stdClass();
	    $ikasgaia->fk_ikasgela = $_GET['fk_ikasgela'];
}
	
	// lortu ariketak konboboxerako
	$sql = "SELECT A.id as id, B.izena as izena
                FROM ariketak A
                INNER JOIN ariketak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
				AND A.egoera = 1
				ORDER BY B.izena ASC";
	$ariketa_guztiak = get_query($sql);
	
    $content = "inc/bistak/ikasgaiak/ikasgaia.php";
        
    } else {
        
        $elementuak = $IkasgaiaModeloa->get_zerrenda($p);
        $content = "inc/bistak/ikasgaiak/ikasgaiak.php";
        
    }
?>