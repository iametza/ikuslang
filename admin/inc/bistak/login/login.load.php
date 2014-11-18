<?php

	if ($_POST){
		$sql = "SELECT id, pasahitza FROM administrazioa WHERE TRIM(pasahitza)<>'' AND TRIM(erabiltzailea)<>'' AND erabiltzailea='" . $_POST["izena"] . "'";
		$dbo->query ($sql) or die ($dbo->ShowError ());
		if ($dbo->emaitza_kopurua () == 1){
			$row = $dbo->emaitza ();
			
			if ($row["pasahitza"] == sha1 ($_POST["gakoa"])){
				$erabiltzailea->login ($row["id"]);
				
				header ("Location: " . URL_BASE_ADMIN);
				exit;
			}
		}
	}

	require	("templates/login.php");

?>
