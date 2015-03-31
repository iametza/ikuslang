<?php
    $url_base = URL_BASE_ADMIN . "esaldiak-zuzendu/";
    
    $menu_aktibo = "esaldiak-zuzendu";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	// erregistro datuak prestatu
	$erregistro_datuak['elementu_mota'] = 'ariketa';
	$erregistro_datuak['fk_sortze_erabiltzailea'] = $erabiltzailea->get_id();
	$erregistro_datuak['fk_aldatze_erabiltzailea'] = $erabiltzailea->get_id();
    
    $hurrengoa = $url->hurrengoa();
	
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Esaldiak ordenatu ariketa bat ezabatu behar dugu.
        if (isset ($_GET["ezab_id"])) {
            
            $ezab_id = $_GET["ezab_id"];
            
            // Ariketa honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
            Etiketak::kenduElementuarenEtiketak($dbo, $ezab_id, 'ariketak_etiketak');
            
            // Ariketaren esaldiak ezabatuko ditugu.
            $sql = "DELETE A, B
                    FROM esaldiak_zuzendu_esaldiak A
                    INNER JOIN esaldiak_zuzendu_esaldiak_hizkuntzak B
                    ON A.id = B.fk_elem
                    WHERE A.fk_ariketa = $ezab_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            // Ariketaren hizkuntza desberdinetako errenkadak ezabatuko ditugu.
			$sql = "DELETE
                    FROM ariketak_hizkuntzak
                    WHERE fk_elem = '$ezab_id'";
			
            $dbo->query($sql) or die($dbo->ShowError());
            
			// Ariketa ezabatuko dugu.
			$sql = "DELETE
                    FROM ariketak
                    WHERE id = '$ezab_id'";
			
            $dbo->query ($sql) or die ($dbo->ShowError ());
            
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
            
        }
        
        // Inserciones o modificaciones
		if (isset($_POST["gorde"])) {
            
            $edit_id = $_POST["edit_id"];
            
            $nice_name = nice_name_hizkuntzak("ariketak", "izena", $edit_id);
            
            if (!is_dbtable_id("ariketak", $edit_id)) {
                
                $sql = "INSERT INTO ariketak (egoera, fk_ariketa_mota, orden)
                        VALUES (0, 1, " . (orden_max("ariketak", "fk_ariketa_mota = 1") + 1) . ")";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // id-a eskuratuko dugu
				$edit_id = db_taula_azken_id("ariketak");
                
            }
            
            // Guardamos los datos en cada idioma
			foreach (hizkuntza_idak() as $h_id) {
                
				$izena = isset($_POST["izena_$h_id"]) ? testu_formatua_sql($_POST["izena_$h_id"]) : "";
                $azalpena = isset($_POST["azalpena_$h_id"]) ? testu_formatua_sql($_POST["azalpena_$h_id"]) : "";
                $nice = $nice_name[$h_id];
                
                // Errenkada dagoeneko existitzen den egiaztatuko dugu.
				$sql = "SELECT *
                        FROM ariketak_hizkuntzak
                        WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                
				$dbo->query($sql) or die($dbo->ShowError());
                
				if ($dbo->emaitza_kopurua() == 0) {
					
                    $sql = "INSERT INTO ariketak_hizkuntzak (izena, nice_name, azalpena, fk_elem, fk_hizkuntza)
                            VALUES ('$izena', '$nice', '$azalpena', '$edit_id', '$h_id')";
                    
				} else {
                    
					$sql = "UPDATE ariketak_hizkuntzak
                            SET izena = '$izena', nice_name = '$nice', azalpena = '$azalpena' WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
				}
                
				$dbo->query($sql) or die($dbo->ShowError());
                
                // Ariketa honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
                Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ariketak_etiketak');
                
                // Etiketak gordeko ditugu orain.
                Etiketak::gordeElementuarenEtiketak($dbo, $edit_id, $h_id, testu_formatua_sql($_POST["hidden-etiketak_$h_id"]), 'ariketak_etiketak');
                
            }
			
            // Ariketa honen dokumentuen datuak gorde
            $dokumentuak = isset($_POST["dokumentuak"]) ? $_POST["dokumentuak"] : array();
            
            // 1. ezabatu, 2.gorde
            $sql = "DELETE FROM ariketa_dokumentua
                    WHERE fk_ariketa = " . $edit_id;
            $dbo->query($sql) or die($dbo->ShowError());
            
            foreach($dokumentuak as $dokumentua) {
                
                $dokumentua = trim($dokumentua);
                
                $sql = "INSERT INTO ariketa_dokumentua (fk_ariketa, fk_dokumentua)
                        VALUES ($edit_id, $dokumentua)";
                $dbo->query($sql) or die($dbo->ShowError());
                
            }
            
			//erregistro datuak gorde
			$erregistro_datuak['fk_elementua'] = $edit_id;
			save_erregistro_datuak($erregistro_datuak);
			
            // Berbideratu.
            header("Location: " . $url_base . $url_param);
			exit;
            
        }
        
        $sql = "SELECT id, orden
                FROM ariketak
                WHERE id = $edit_id";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($dbo->emaitza_kopurua() == 1) {
            $row = $dbo->emaitza();
            
            $esaldiak_zuzendu = new stdClass();
            
            $esaldiak_zuzendu->id = $row["id"];
            $esaldiak_zuzendu->orden = $row["orden"];
            
            $sql = "SELECT B.id, C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $edit_id AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $esaldiak_zuzendu->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->id = $row["id"];
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $esaldiak_zuzendu->dokumentuak[] = $tmp_dokumentua;
            }
            
            $esaldiak_zuzendu->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT izena, azalpena
                        FROM ariketak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $esaldiak_zuzendu->hizkuntzak[$h_id] = new stdClass();
                
                $esaldiak_zuzendu->hizkuntzak[$h_id]->izena = $rowHizk["izena"];
                $esaldiak_zuzendu->hizkuntzak[$h_id]->azalpena = $rowHizk["azalpena"];
                
            }
        }
        
        $sql = "SELECT A.id, B.izenburua, A.path_dokumentua, A.dokumentua
                FROM dokumentuak AS A
                INNER JOIN dokumentuak_hizkuntzak AS B
                ON A.id = B.fk_elem AND A.dokumentua IS NOT NULL
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"];
        
        $dokumentuak = get_query($sql);
        
        $content = "inc/bistak/esaldiak_zuzendu/esaldiak_zuzendu_form.php";
        
    } else if ($hurrengoa === "esaldiak") {
		
        $url_base = URL_BASE_ADMIN . "esaldiak-zuzendu/esaldiak/";
        
        $id_ariketa = isset($_GET["id_ariketa"]) ? (int) $_GET["id_ariketa"] : 0;
        
		$hurrengoa = $url->hurrengoa();
		
        if ($hurrengoa == "form") {
            
            $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
            
            // Esaldi bat ezabatu behar dugu.
            if (isset ($_GET["ezab_id"])) {
                
                $ezab_id = (int) $_GET["ezab_id"];
                $id_ariketa = (int) $_GET["id_ariketa"];
                
                $sql = "DELETE A, B
                        FROM esaldiak_zuzendu_esaldiak A
                        INNER JOIN esaldiak_zuzendu_esaldiak_hizkuntzak B
                        ON A.id = B.fk_elem
                        WHERE A.id = $ezab_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Ariketaren esaldien orrira berbideratuko dugu.
                $url_param = "?id_ariketa=" . $id_ariketa;
                
				//erregistro datuak gorde
				$erregistro_datuak['fk_elementua'] = $id_ariketa;
				save_erregistro_datuak($erregistro_datuak);
				
                // Berbideratu.
                header("Location: " . $url_base . $url_param);
                exit;
                
            }
            
            // Esaldia txertatu edo eguneratu behar bada.
            if (isset($_POST["gorde"])) {
                
                $edit_id = $_POST["edit_id"];
                $id_ariketa = $_POST["id_ariketa"];
                
                $url_param = "?id_ariketa=" . $id_ariketa;
                
                if (!is_dbtable_id("esaldiak_zuzendu_esaldiak", $edit_id)) {
                    
                    $sql = "INSERT INTO esaldiak_zuzendu_esaldiak (fk_ariketa)
                            VALUES (" . $id_ariketa . ")";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // id-a eskuratuko dugu
                    $edit_id = db_taula_azken_id("esaldiak_zuzendu_esaldiak");
                    
                }
                
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $testua = testu_formatua_sql($_POST["testua_$h_id"]);
                    
                    // Oraingoz ordena zuzen bakarra onartuko dugu baina ordena zuzen bat baino gehiago dituzten esaldietarako prestatuta dago ariketa.
                    $ordenak = array();
                    
                    $ordenak[] = range(0, count(explode(" ", $testua)) - 1);
                    
                    $ordenak = json_encode($ordenak);
                    
                    // Errenkada dagoeneko existitzen den egiaztatuko dugu.
                    $sql = "SELECT *
                            FROM esaldiak_zuzendu_esaldiak_hizkuntzak
                            WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    if ($dbo->emaitza_kopurua() == 0) {
                        
                        $sql = "INSERT INTO esaldiak_zuzendu_esaldiak_hizkuntzak (testua, ordenak, fk_elem, fk_hizkuntza)
                                VALUES ('$testua', '$ordenak', '$edit_id', '$h_id')";
                        
                    } else {
                        
                        $sql = "UPDATE esaldiak_zuzendu_esaldiak_hizkuntzak
                                SET testua = '$testua', ordenak = '$ordenak'
                                WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                        
                    }
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                }
				
				//erregistro datuak gorde
				$erregistro_datuak['fk_elementua'] = $id_ariketa;
				save_erregistro_datuak($erregistro_datuak);
                
                // Berbideratu.
                header("Location: " . $url_base . $url_param);
                exit;
                
            }
            
            $sql = "SELECT id
                    FROM esaldiak_zuzendu_esaldiak
                    WHERE id = $edit_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            if ($dbo->emaitza_kopurua() == 1) {
                
                $row = $dbo->emaitza();
                
                $esaldia = new stdClass();
                
                $esaldia->id = $row["id"];
                
                $esaldia->hizkuntzak = array();
                
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $sql = "SELECT testua
                            FROM esaldiak_zuzendu_esaldiak_hizkuntzak
                            WHERE fk_elem = $edit_id
                            AND fk_hizkuntza = $h_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    $rowHizk = $dbo->emaitza();
                    
                    $esaldia->hizkuntzak[$h_id] = new stdClass();
                    
                    $esaldia->hizkuntzak[$h_id]->testua= $rowHizk["testua"];
                }
            }
            
            $content = "inc/bistak/esaldiak_zuzendu/esaldiak_zuzendu_esaldia.php";
            
        } else {
            
            $sql = "SELECT id
                    FROM esaldiak_zuzendu_esaldiak
                    WHERE fk_ariketa = " . $id_ariketa;
            
            $elementuak = get_query($sql);
            
            $content = "inc/bistak/esaldiak_zuzendu/esaldiak_zuzendu_esaldiak.php";
            
        }
        
    } else {
        
        // Erabiltzaileak ariketa baten egoera checkbox-aren balioa aldatu badu.
        if (isset($_GET["aldatu_egoera_id"])) {
            
            // Ariketaren id-a eskuratu.
            $edit_id = (int) $_GET["aldatu_egoera_id"];
            
            // Balioa (erantzuna zuzena den ala ez).
            $egoera = $_GET["bal"];
            
            // Aldaketa datu-basean gorde.
            $sql = "UPDATE ariketak
                    SET egoera = $egoera
                    WHERE id = $edit_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
			//erregistro datuak gorde
			$erregistro_datuak['fk_elementua'] = $edit_id;
			save_erregistro_datuak($erregistro_datuak);
			
            // Dagokion orrira berbideratu.
            header("Location: " . $url_base . $url_param);
        }
        
        $sql = "SELECT A.*
                FROM ariketak AS A
                INNER JOIN ariketak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE A.fk_ariketa_mota = 1
                ORDER BY B.izena ASC";
		
		$orrikapena = orrikapen_datuak ($sql, $p);
		$sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
		$elementuak = get_query($sql);
		
		$content = "inc/bistak/esaldiak_zuzendu/esaldiak_zuzendu.php";
        
    }
?>