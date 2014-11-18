<?php
    $url_base = URL_BASE_ADMIN . "mailak/";
    
    $menu_aktibo = "mailak";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // maila bat ezabatu behar dugu.
		if (isset ($_GET["ezab_id"])) {
			
            $ezab_id = $_GET["ezab_id"];
            
			// maila ezabatuko dugu.
			$sql = "DELETE FROM mailak
                    WHERE id = '$ezab_id'";
			
            $dbo->query($sql) or die($dbo->ShowError());
            
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
        
        // maila bat gehitu edo editatu badu erabiltzaileak.
		if (isset($_POST["gorde"])) {
            
            $edit_id = $_POST["edit_id"];
            
            $izena = isset($_POST["izena"]) ? testu_formatua_sql($_POST["izena"]) : "";
           
            
            // maila berri baten datuak gorde behar ditugu.
            if (!is_dbtable_id("mailak", $edit_id)) {
                 
                $sql = "INSERT INTO mailak (izena)
                        VALUES ('$izena')";
                $dbo->query($sql) or die($dbo->ShowError());
                
            // maila baten datuak editatzen ari gara.
            } else {
              
		// Ausazko gatz berri bat sortuko dugu.
		$gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
		
		// Pasahitzaren hash-a sortu gatza erabiliz.
		$pasahitza = hash("sha256", $gatza . $pasahitza);
		
		$sql = "UPDATE mailak
			SET izena = '$izena'
			WHERE id = $edit_id";
		
		$dbo->query($sql) or die($dbo->ShowError());
		
               
            }
            
            // Berbideratu.
            header("Location: " . $url_base . $url_param);
			exit;
        }
        
        $sql = "SELECT id, izena
                FROM mailak
                WHERE id = $edit_id";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($dbo->emaitza_kopurua() == 1) {
            
            $row = $dbo->emaitza();
            
            $maila = new stdClass();
            
            $maila->id = $row["id"];
            $maila->izena = $row["izena"];
          
            
        }
        
        $content = "inc/bistak/mailak/maila.php";
        
    } else {
        
        $sql = "SELECT * FROM mailak ORDER BY izena ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/mailak/mailak.php";
        
    }
?>