<?php

	if ($_POST) {
        
        $pasahitza = isset($_POST["pasahitza"]) ? testu_formatua_sql($_POST["pasahitza"]) : "";
        
		$sql = "SELECT id, gatza, pasahitza
                FROM ikasleak
                WHERE TRIM(pasahitza) <> '' AND TRIM(e_posta) <> '' AND e_posta = '" . $_POST["e-posta"] . "'";
        
		$dbo->query($sql) or die($dbo->ShowError());
		
        if ($dbo->emaitza_kopurua() == 1){
            
			$row = $dbo->emaitza();
			
			if ($row["pasahitza"] == hash("sha256", $row["gatza"] . $pasahitza)) {
                
				$erabiltzailea->login($row["id"]);
                
				header("Location: " . URL_BASE);
                
				exit;
                
			}
            
		}
        
	}

	require("templates/login.php");

?>
