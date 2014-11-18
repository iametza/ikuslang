<?php
    $url_base = URL_BASE_ADMIN . "ikasleak/";
    
    $menu_aktibo = "ikasleak";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Ikasle bat ezabatu behar dugu.
		if (isset ($_GET["ezab_id"])) {
			
            $ezab_id = $_GET["ezab_id"];
            
			// Ikaslea ezabatuko dugu.
			$sql = "DELETE FROM ikasleak
                    WHERE id = '$ezab_id'";
			
            $dbo->query($sql) or die($dbo->ShowError());
            
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
        
        // Ikasle bat gehitu edo editatu badu erabiltzaileak.
		if (isset($_POST["gorde"])) {
            
            $edit_id = $_POST["edit_id"];
            
            $izena = isset($_POST["izena"]) ? testu_formatua_sql($_POST["izena"]) : "";
            $abizenak = isset($_POST["abizenak"]) ? testu_formatua_sql($_POST["abizenak"]) : "";
            $e_posta = isset($_POST["e_posta"]) ? testu_formatua_sql($_POST["e_posta"]) : "";
            $pasahitza = isset($_POST["pasahitza"]) ? testu_formatua_sql($_POST["pasahitza"]) : "";
            $pasahitza2 = isset($_POST["pasahitza2"]) ? testu_formatua_sql($_POST["pasahitza2"]) : "";
            
            // Ikasle berri baten datuak gorde behar ditugu.
            if (!is_dbtable_id("ikasleak", $edit_id)) {
                
                // Ikasle berrien kasuan pasahitza zehaztea derrigorrezkoa da.
                // Pasahitzak ez badaude hutsik eta bat badatoz:
                if ($pasahitza != "" && $pasahitza2 != "" && $pasahitza == $pasahitza2) {
                    
                    // Ausazko gatz bat sortuko dugu.
                    $gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
                    
                    // Pasahitzaren hash-a sortu gatza erabiliz.
                    $pasahitza = hash("sha256", $gatza . $pasahitza);
                    
                    $sql = "INSERT INTO ikasleak (izena, abizenak, e_posta, gatza, pasahitza)
                            VALUES ('$izena', '$abizenak', '$e_posta', '$gatza', '$pasahitza')";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                } else {
                    
                    // JavaScript bidez egiaztatzen dut baina ondo legoke PHP bidezko egiaztapena pasatzen ez badu erabiltzaileari jakinaraztea
                    // eta gainerako eremuak beteta agertzea.
                    // Berriz ere formulariora bideratuko dugu baina eremuak hutsik agertuko dira.
                    header("Location: " . $url_base . "form");
                    exit();
                    
                }
                
            // Ikasle baten datuak editatzen ari gara.
            } else {
                
                // Ikasle bat editatzen ari bagara pasahitza aldatzea hautazkoa da.
                if ($pasahitza != "" && $pasahitza2 != "" && $pasahitza == $pasahitza2) {
                    
                    // Ausazko gatz berri bat sortuko dugu.
                    $gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
                    
                    // Pasahitzaren hash-a sortu gatza erabiliz.
                    $pasahitza = hash("sha256", $gatza . $pasahitza);
                    
                    $sql = "UPDATE ikasleak
                            SET izena = '$izena', abizenak = '$abizenak', e_posta = '$e_posta', gatza = '$gatza', pasahitza = '$pasahitza'
                            WHERE id = $edit_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                // Pasahitza hutsik badago, ez dugu gorde behar.
                } else if ($pasahitza == "" && $pasahitza2 == "") {
                    
                    $sql = "UPDATE ikasleak
                            SET izena = '$izena', abizenak = '$abizenak', e_posta = '$e_posta'
                            WHERE id = $edit_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                } else {
                    
                    // JavaScript bidez egiaztatzen dut baina ondo legoke PHP bidezko egiaztapena pasatzen ez badu erabiltzaileari jakinaraztea
                    // eta gainerako eremuak beteta agertzea.
                    // Berriz ere formulariora bideratuko dugu baina eremuak hutsik agertuko dira.
                    header("Location: " . $url_base . "form");
                    exit();
                    
                }
            }
            
            // Berbideratu.
            header("Location: " . $url_base . $url_param);
			exit;
        }
        
        $sql = "SELECT id, izena, abizenak, e_posta
                FROM ikasleak
                WHERE id = $edit_id";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($dbo->emaitza_kopurua() == 1) {
            
            $row = $dbo->emaitza();
            
            $ikaslea = new stdClass();
            
            $ikaslea->id = $row["id"];
            $ikaslea->izena = $row["izena"];
            $ikaslea->abizenak = $row["abizenak"];
            $ikaslea->e_posta = $row["e_posta"];
            
        }
        
        $content = "inc/bistak/ikasleak/ikaslea.php";
        
    } elseif($hurrengoa == 'bilatu'){
	// ajax bidezko bilaketa
	$q = strtolower($_GET["term"]);
		if (!$q) return;
		$q = $q.'*';
		$sql = "SELECT CONCAT(izena,' ', abizenak) as izena, id as id
			FROM ikasleak
			WHERE MATCH(izena, abizenak) AGAINST ('".$q."' IN BOOLEAN MODE)
			ORDER BY izena";
		
		$rows =get_query($sql);
		$idak = array();
		
		//pr($rows);
		foreach ($rows as $row) {
		    $emaitzak[] = array('id'=> $row['id'], 'value' => $row['izena'] ,'label' => $row['izena']);
		
		}
		echo json_encode($emaitzak);
		
		exit;
	
    }
    else {
        
        $sql = "SELECT * FROM ikasleak ORDER BY abizenak ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/ikasleak/ikasleak.php";
        
    }
?>