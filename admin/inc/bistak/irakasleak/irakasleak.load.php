<?php
    $url_base = URL_BASE_ADMIN . "irakasleak/";
    
    $menu_aktibo = "irakasleak";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Irakasle bat ezabatu behar dugu.
	if (isset ($_GET["ezab_id"])) {
			
            $ezab_id = $_GET["ezab_id"];
            
	    
	    // Irakaslea ezabatuko dugu.
	    
	    // ezabatu aurretik eposta lortu (erabiltzaile izena da)
	    $eposta = get_dbtable_field_by_id ("administrazioa", "erabiltzailea", $ezab_id);
	    
	    $sql = "DELETE FROM irakasleak
                    WHERE id = '$ezab_id'";
			
            $dbo->query($sql) or die($dbo->ShowError());
            
	    // administrazioa taulan
	    $sql = "DELETE FROM administrazioa WHERE erabiltzailea='$eposta'";
		    $dbo->query($sql) or die($dbo->ShowError());
	    
	    // Berbideratu.
	    header ("Location: " . $url_base . $url_param);
	    exit;
	}
        
        // Irakasle bat gehitu edo editatu badu erabiltzaileak.
	if (isset($_POST["gorde"])) {
    
	    $edit_id = $_POST["edit_id"];
	    
	    $izena = isset($_POST["izena"]) ? testu_formatua_sql($_POST["izena"]) : "";
	    $abizenak = isset($_POST["abizenak"]) ? testu_formatua_sql($_POST["abizenak"]) : "";
	    $e_posta = isset($_POST["e_posta"]) ? testu_formatua_sql($_POST["e_posta"]) : "";
	    $pasahitza = isset($_POST["pasahitza"]) ? testu_formatua_sql($_POST["pasahitza"]) : "";
	    $pasahitza2 = isset($_POST["pasahitza2"]) ? testu_formatua_sql($_POST["pasahitza2"]) : "";
	    
	    // Irakasle berri baten datuak gorde behar ditugu.
	    if (!is_dbtable_id("irakasleak", $edit_id)) {
		
		// Irakasle berrien kasuan pasahitza zehaztea derrigorrezkoa da.
		// Pasahitzak ez badaude hutsik eta bat badatoz:
		if ($pasahitza != "" && $pasahitza2 != "" && $pasahitza == $pasahitza2) {
		    
		   $pasahitza_login = sha1($pasahitza);
		    // Ausazko gatz bat sortuko dugu.
		    $gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
		    
		    // Pasahitzaren hash-a sortu gatza erabiliz.
		    $pasahitza = hash("sha256", $gatza . $pasahitza);
		    
		    $sql = "INSERT INTO irakasleak (izena, abizenak, e_posta, gatza, pasahitza)
			    VALUES ('$izena', '$abizenak', '$e_posta', '$gatza', '$pasahitza')";
		    
		    $dbo->query($sql) or die($dbo->ShowError());
		    
		    // administrazioa taulan ere altan eman (login)
		    $erab = $e_posta;
		    // pasahitza loginerako desberdina da
		    $p1 = $pasahitza_gatzikpe;
		    $sql = "INSERT INTO administrazioa (erabiltzailea, pasahitza, rola) VALUES ('$erab', '" .$pasahitza_login . "', 'irakaslea')";
		    $dbo->query($sql) or die($dbo->ShowError());
		    
		} else {
		    
		    // JavaScript bidez egiaztatzen dut baina ondo legoke PHP bidezko egiaztapena pasatzen ez badu erabiltzaileari jakinaraztea
		    // eta gainerako eremuak beteta agertzea.
		    // Berriz ere formulariora bideratuko dugu baina eremuak hutsik agertuko dira.
		    header("Location: " . $url_base . "form");
		    exit();
	    
	}
                
            // Irakasle baten datuak editatzen ari gara.
            } else {
                
                // Irakasle bat editatzen ari bagara pasahitza aldatzea hautazkoa da.
                if ($pasahitza != "" && $pasahitza2 != "" && $pasahitza == $pasahitza2) {
                    
		    $pasahitza_login = sha1($pasahitza);
                    // Ausazko gatz berri bat sortuko dugu.
                    $gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
                    
                    // Pasahitzaren hash-a sortu gatza erabiliz.
                    $pasahitza = hash("sha256", $gatza . $pasahitza);
                    
                    $sql = "UPDATE irakasleak
                            SET izena = '$izena', abizenak = '$abizenak', e_posta = '$e_posta', gatza = '$gatza', pasahitza = '$pasahitza'
                            WHERE id = $edit_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
		    
		    // administrazioa taulan
		    $sql = "UPDATE administrazioa SET erabiltzailea='$e_posta', pasahitza = '$pasahitza_login' WHERE erabiltzailea='$e_posta'";
		    $dbo->query($sql) or die($dbo->ShowError());
		    
                    
                // Pasahitza hutsik badago, ez dugu gorde behar.
                } else if ($pasahitza == "" && $pasahitza2 == "") {
                    
                    $sql = "UPDATE irakasleak
                            SET izena = '$izena', abizenak = '$abizenak', e_posta = '$e_posta'
                            WHERE id = $edit_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
		    
		    // administrazioa taulan
		    $sql = "UPDATE administrazioa SET erabiltzailea='$e_posta' WHERE erabiltzailea='$e_posta'";
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
                FROM irakasleak
                WHERE id = $edit_id";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($dbo->emaitza_kopurua() == 1) {
            
            $row = $dbo->emaitza();
            
            $irakaslea = new stdClass();
            
            $irakaslea->id = $row["id"];
            $irakaslea->izena = $row["izena"];
            $irakaslea->abizenak = $row["abizenak"];
            $irakaslea->e_posta = $row["e_posta"];
            
        }
        
        $content = "inc/bistak/irakasleak/irakaslea.php";
        
    } else {
        
        $sql = "SELECT * FROM irakasleak ORDER BY izena ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/irakasleak/irakasleak.php";
        
    }
?>