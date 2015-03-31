<?php
	
	// GCM alertak bidaltzeko.
	require_once("inc/libs/GCMPushMessage.php");
	
	// kargatu modeloa
	require('../inc/modeloak/ikasgela_modeloa.php');
	$IkasgelaModeloa = new IkasgelaModeloa();
 

    $url_base = URL_BASE_ADMIN . "ikasgelak/";
    
    $menu_aktibo = "ikasgelak";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	// erregistro datuak prestatu
	$erregistro_datuak['elementu_mota'] = 'ikasgela';
	$erregistro_datuak['fk_sortze_erabiltzailea'] = $erabiltzailea->get_id();
	$erregistro_datuak['fk_aldatze_erabiltzailea'] = $erabiltzailea->get_id();
    
    $hurrengoa = $url->hurrengoa();
	
	if (isset($_POST["alerta"])) {
		
		$mezua = isset($_POST["mezua"]) ? mysql_escape_string($_POST["mezua"]) : "";
		$edit_id = isset($_POST["edit_id"]) ? (int) $_POST["edit_id"] : 0;
		
		//------------------------------
        // Payload data you want to send 
        // to Android device (will be
        // accessible via intent extras)
        //------------------------------
        
        $data = array( 'message' => $mezua );
        
        //------------------------------
        // The recipient registration IDs
        // that will receive the push
        // (Should be stored in your DB)
        // 
        // Read about it here:
        // http://developer.android.com/google/gcm/
        //------------------------------
        
		// Ikasgela honetako ikasleak hautatu.
        $sql = "SELECT mota, id_gailua
                FROM alerta_eskaerak
				WHERE id_ikaslea IN (SELECT fk_ikaslea FROM ikasgelak_ikasleak WHERE fk_ikasgela = $edit_id)";
		
        $dbo->query($sql) or die($dbo->ShowError());
        
        $id_ak = array();
        
        while($row = $dbo->emaitza()) {
            
            $id_ak[] = $row["id_gailua"];
        
        }
		
        //------------------------------
        // Call our custom GCM function
        //------------------------------
        
        $gcpm = new GCMPushMessage($apiKey);
        $gcpm->setDevices($id_ak);
        $response = $gcpm->send($mezua, array('title' => 'Ikuslang'));
		
	}
	
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
		// begiratu irakaslea den, bakarrik bereak alda ditzake!
		if($erabiltzailea->get_rola() == 'irakaslea' and $edit_id != 0){
			$fk_irakaslea = $erabiltzailea->get_fk_irakaslea();
			$ikasgelaren_fk_irakaslea =  get_dbtable_field_by_id('ikasgelak', 'fk_irakaslea', $edit_id);
			if($fk_irakaslea != $ikasgelaren_fk_irakaslea){
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
			}
		}
	
	
	
        // ikasgela bat ezabatu behar dugu.
		if (isset ($_GET["ezab_id"])) {
			$ezab_id = $_GET["ezab_id"];
			// ikasgela ezabatuko dugu.
			$sql = "DELETE FROM ikasgelak
				WHERE id = '$ezab_id'";
			$dbo->query($sql) or die($dbo->ShowError());
		
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
        
        // ikasgela bat gehitu edo editatu badu erabiltzaileak.
		if (isset($_POST["gorde"])) {
		
			$edit_id = $_POST["edit_id"];
			
			$izena = isset($_POST["izena"]) ? testu_formatua_sql($_POST["izena"]) : "";
			$fk_maila = isset($_POST["fk_maila"]) ? testu_formatua_sql($_POST["fk_maila"]) : "";
			$fk_irakaslea = isset($_POST["fk_irakaslea"]) ? testu_formatua_sql($_POST["fk_irakaslea"]) : "";
			$ikasleak = isset($_POST["ikasleak"]) ? $_POST["ikasleak"] : "";
			
		  
			// ikasgela berri baten datuak gorde behar ditugu.
			if (!is_dbtable_id("ikasgelak", $edit_id)) {
			
			$sql = "INSERT INTO ikasgelak (izena, fk_maila, fk_irakaslea)
				VALUES ('$izena', '$fk_maila', '$fk_irakaslea')";
			$dbo->query($sql) or die($dbo->ShowError());
			$edit_id = db_taula_azken_id('ikasgelak');
			// gorde ikasleak
			
			if(isset($_POST['formulario_zatia']) and $_POST['formulario_zatia'] == 'ikasleak')
				gorde_ikasleak($edit_id, $ikasleak);
			
			
			// ikasgela baten datuak editatzen ari gara.
			} else {
				if(isset($_POST['formulario_zatia']) and $_POST['formulario_zatia'] == 'orokorra'){
					$sql = "UPDATE ikasgelak
							SET izena = '$izena', fk_maila = '$fk_maila', fk_irakaslea = '$fk_irakaslea'
							WHERE id = $edit_id";
					$dbo->query($sql) or die($dbo->ShowError());
				}
				if(isset($_POST['formulario_zatia']) and $_POST['formulario_zatia'] == 'ikasleak'){
					// gorde ikasleak
					gorde_ikasleak($edit_id, $ikasleak);
				}
			
			}
					
			//erregistro datuak gorde
			$erregistro_datuak['fk_elementua'] = $edit_id;
			save_erregistro_datuak($erregistro_datuak);
			
			// Berbideratu.
			header("Location: " . $url_base . $url_param);
			exit;
		}
        
        
		// ikasle_idak init
		$ikasle_idak = array();
		
		$ikasgela = $IkasgelaModeloa->get($edit_id);
		
		if(!empty($ikasgela->ikasleak)){
			foreach($ikasgela->ikasleak as $ikaslea){
				$ikasle_idak[] = $ikaslea['id'];
			}
		}
			
				
		// ikasle guztiak lortu
		$sql = "SELECT i.id as id, CONCAT(i.izena, ' ', i.abizenak) as izen_abizenak
				FROM ikasleak i
				ORDER BY izen_abizenak";
		$ikasle_guztiak = get_query($sql);
				
		
		$content = "inc/bistak/ikasgelak/ikasgela.php";
		
	} else {
    
	
		$kriterioak = array();
		// begiratu irakaslea den
		if($erabiltzailea->get_rola() == 'irakaslea'){
			$fk_irakaslea = $erabiltzailea->get_fk_irakaslea();
			$kriterioak['fk_irakaslea'] = $fk_irakaslea;
		
			
		}
		$elementuak = $IkasgelaModeloa->get_zerrenda($p, $kriterioak);
			
		$content = "inc/bistak/ikasgelak/ikasgelak.php";
        
    }
?>