<?php
    $url_base = URL_BASE_ADMIN . "multzokatu/";
    
    $menu_aktibo = "multzokatu";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    // erregistro datuak prestatu
	$erregistro_datuak['elementu_mota'] = 'ariketa';
	$erregistro_datuak['fk_sortze_erabiltzailea'] = $erabiltzailea->get_id();
	$erregistro_datuak['fk_aldatze_erabiltzailea'] = $erabiltzailea->get_id();
	
	
	$hurrengoa = $url->hurrengoa();
    
	if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Multzokatu ariketa bat ezabatu behar dugu.
		if (isset ($_GET["ezab_id"])){
			
            $ezab_id = $_GET["ezab_id"];
            
            // Ariketa honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
            Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ariketak_etiketak');
            
            // Ariketaren taldeen elementuak ezabatuko ditugu.
            $sql = "DELETE A, B
                    FROM multzokatu_elementuak A
                    INNER JOIN multzokatu_elementuak_hizkuntzak B
                    ON A.id = B.fk_elem
                    WHERE A.fk_taldea IN
                        (SELECT id FROM multzokatu_taldeak WHERE fk_ariketa = $ezab_id)";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            // Ariketaren taldeak ezabatuko ditugu.
            $sql = "DELETE A, B
                    FROM multzokatu_taldeak A
                    INNER JOIN multzokatu_taldeak_hizkuntzak B
                    ON A.id = B.fk_elem
                    AND A.fk_ariketa = $ezab_id";
            
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
		if (isset ($_POST["gorde"])) {
            
			// Formularioko datuak eskuratuko ditugu.
			$edit_id = testu_formatua_sql($_POST["edit_id"]);
            
            $nice_name = nice_name_hizkuntzak("ariketak", "izena", $edit_id);
            
            if (!is_dbtable_id("ariketak", $edit_id)) {
                
                $sql = "INSERT INTO ariketak (egoera, fk_ariketa_mota, orden)
                        VALUES (0, 5, " . (orden_max("ariketak", "fk_ariketa_mota = 5") + 1) . ")";
                
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
					
                    $sql = "INSERT INTO ariketak_hizkuntzak (izena, azalpena, nice_name, fk_elem, fk_hizkuntza)
                            VALUES ('$izena', '$azalpena', '$nice', '$edit_id', '$h_id')";
                    
				} else {
                    
					$sql = "UPDATE ariketak_hizkuntzak
                            SET izena = '$izena', azalpena = '$azalpena', nice_name = '$nice' WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
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
            
            $multzokatu = new stdClass();
            
            $multzokatu->id = $row["id"];
            $multzokatu->orden = $row["orden"];
            
             $sql = "SELECT B.id, C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $edit_id AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $multzokatu->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->id = $row["id"];
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $multzokatu->dokumentuak[] = $tmp_dokumentua;
            }
            
            $multzokatu->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT izena, azalpena
                        FROM ariketak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $multzokatu->hizkuntzak[$h_id] = new stdClass();
                
                $multzokatu->hizkuntzak[$h_id]->izena = $rowHizk["izena"];
                $multzokatu->hizkuntzak[$h_id]->azalpena = $rowHizk["azalpena"];
            }
        }
        
        $sql = "SELECT A.id, B.izenburua, A.path_dokumentua, A.dokumentua
                FROM dokumentuak AS A
                INNER JOIN dokumentuak_hizkuntzak AS B
                ON A.id = B.fk_elem AND A.dokumentua IS NOT NULL
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"];
        
        $dokumentuak = get_query($sql);
        
        $content = "inc/bistak/multzokatu/multzokatu.php";
        
    } else if ($hurrengoa == "taldeak") {
        
        $url_base = URL_BASE_ADMIN . "multzokatu/taldeak/";
        
        $id_ariketa = isset($_GET["id_ariketa"]) ? (int) $_GET["id_ariketa"] : 0;
        
        $hurrengoa = $url->hurrengoa();
		
        if ($hurrengoa == "form") {
            
            $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
            
            // Talde bat ezabatu behar dugu.
            if (isset ($_GET["ezab_id"])) {
                
                $ezab_id = isset($_GET["ezab_id"]) ? (int) $_GET["ezab_id"] : 0;
                
                $url_param = "?id_ariketa=" . $id_ariketa;
                
                // Ariketaren taldeen elementuak ezabatuko ditugu.
                $sql = "DELETE A, B
                        FROM multzokatu_elementuak A
                        INNER JOIN multzokatu_elementuak_hizkuntzak B
                        ON A.id = B.fk_elem
                        WHERE A.fk_taldea = $ezab_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Taldearen hizkuntza desberdinetako errenkadak ezabatuko ditugu.
                $sql = "DELETE FROM multzokatu_taldeak_hizkuntzak WHERE fk_elem = '$ezab_id'";
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Taldea ezabatuko dugu.
                $sql = "DELETE FROM multzokatu_taldeak WHERE id = '$ezab_id'";
                $dbo->query($sql) or die($dbo->ShowError());
             				
				//erregistro datuak gorde
				$erregistro_datuak['fk_elementua'] = $id_ariketa;
				save_erregistro_datuak($erregistro_datuak);
				
                // Berbideratu.
                header ("Location: " . $url_base . $url_param);
                exit;
            }
            
            // Inserciones o modificaciones
            if (isset($_POST["gorde"])) {
                
                $edit_id = $_POST["edit_id"];
                $id_ariketa = $_POST["id_ariketa"];
                
                $url_param = "?id_ariketa=" . $id_ariketa;
                
                if (!is_dbtable_id("multzokatu_taldeak", $edit_id)) {
                    
                    $sql = "INSERT INTO multzokatu_taldeak (fk_ariketa)
                            VALUES (" . $id_ariketa . ")";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // id-a eskuratuko dugu
                    $edit_id = db_taula_azken_id("multzokatu_taldeak");
                    
                }
                
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $izena = testu_formatua_sql ($_POST["izena_$h_id"]);
                    
                    // Errenkada dagoeneko existitzen den egiaztatuko dugu.
                    $sql = "SELECT *
                            FROM multzokatu_taldeak_hizkuntzak
                            WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    if ($dbo->emaitza_kopurua() == 0) {
                        
                        $sql = "INSERT INTO multzokatu_taldeak_hizkuntzak (izena, fk_elem, fk_hizkuntza)
                                VALUES ('$izena', '$edit_id', '$h_id')";
                        
                    } else {
                        
                        $sql = "UPDATE multzokatu_taldeak_hizkuntzak
                                SET izena = '$izena' WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                        
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
                    FROM multzokatu_taldeak
                    WHERE id = $edit_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            if ($dbo->emaitza_kopurua() == 1) {
                
                $row = $dbo->emaitza();
                
                $taldea = new stdClass();
                
                $taldea->id = $row["id"];
                
                $taldea->hizkuntzak = array();
                
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $sql = "SELECT izena
                            FROM multzokatu_taldeak_hizkuntzak
                            WHERE fk_elem = $edit_id
                            AND fk_hizkuntza = $h_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    $rowHizk = $dbo->emaitza();
                    
                    $taldea->hizkuntzak[$h_id] = new stdClass();
                    
                    $taldea->hizkuntzak[$h_id]->izena= $rowHizk["izena"];
                    
                }
            }
            
            $content = "inc/bistak/multzokatu/multzokatu_taldea.php";
            
        } else if ($hurrengoa == "elementuak") {
            
            $url_base = URL_BASE_ADMIN . "multzokatu/taldeak/elementuak/";
            
            $id_ariketa = isset($_GET["id_ariketa"]) ? $_GET["id_ariketa"] : 0;
            
            $id_taldea = isset($_GET["id_taldea"]) ? (int) $_GET["id_taldea"] : 0;
            
            if ($url->hurrengoa() == "form") {
                
                $edit_id = isset($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
                
                // Elementu bat ezabatu behar dugu.
                if (isset($_GET["ezab_id"])) {
                    
                    $ezab_id = isset($_GET["ezab_id"]) ? (int) $_GET["ezab_id"] : 0;
                    
                    $url_param = "?id_ariketa=" . $id_ariketa . "&id_taldea=" . $id_taldea;
                    
                    // Elementuaren hizkuntza desberdinetako errenkadak ezabatuko ditugu.
                    $sql = "DELETE FROM multzokatu_elementuak_hizkuntzak WHERE fk_elem = '$ezab_id'";
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Erantzuna ezabatuko dugu.
                    $sql = "DELETE FROM multzokatu_elementuak WHERE id = '$ezab_id'";
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Berbideratu.
                    header ("Location: " . $url_base . $url_param);
                    exit;
                }
                
                // Elementua txertatu edo eguneratu behar bada.
                if (isset($_POST["gorde"])) {
                    
                    $edit_id = isset($_POST["edit_id"]) ? (int) $_POST["edit_id"] : 0;
                    $id_taldea = isset($_POST["id_taldea"]) ? (int) $_POST["id_taldea"] : 0;
                    $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
                    
                    $url_param = "?id_ariketa=" . $id_ariketa . "&id_taldea=" . $id_taldea;
                    
                    // $id_taldea ezarrita dagoela egiaztatuko dugu.
                    if ($id_taldea > 0) {
                        
                        if (!is_dbtable_id("multzokatu_elementuak", $edit_id)) {
                            
                            $sql = "INSERT INTO multzokatu_elementuak (fk_taldea)
                                    VALUES (" . $id_taldea . ")";
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                            // id-a eskuratuko dugu
                            $edit_id = db_taula_azken_id("multzokatu_elementuak");
                            
                        }
                        
                        foreach (hizkuntza_idak() as $h_id) {
                            
                            $izena = testu_formatua_sql($_POST["izena_$h_id"]);
                            
                            // Elementua dagoeneko existitzen den egiaztatuko dugu.
                            $sql = "SELECT *
                                    FROM multzokatu_elementuak_hizkuntzak
                                    WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                            if ($dbo->emaitza_kopurua() == 0) {
                                
                                $sql = "INSERT INTO multzokatu_elementuak_hizkuntzak (izena, fk_elem, fk_hizkuntza)
                                        VALUES ('$izena', '$edit_id', '$h_id')";
                                
                            } else {
                                
                                $sql = "UPDATE multzokatu_elementuak_hizkuntzak
                                        SET izena = '$izena'
                                        WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                                
                            }
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                        }
                    }
					
					//erregistro datuak gorde
					$erregistro_datuak['fk_elementua'] = $edit_id;
					save_erregistro_datuak($erregistro_datuak);
                    
                    // Berbideratu.
                    header("Location: " . $url_base . $url_param);
                    exit();
                }
                
                $sql = "SELECT id
                        FROM multzokatu_elementuak
                        WHERE id = $edit_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                if ($dbo->emaitza_kopurua() == 1) {
                    
                    $row = $dbo->emaitza();
                    
                    $elementua = new stdClass();
                    
                    $elementua->id = $row["id"];
                    
                    $elementua->hizkuntzak = array();
                    
                    foreach (hizkuntza_idak() as $h_id) {
                        
                        $sql = "SELECT izena
                                FROM multzokatu_elementuak_hizkuntzak
                                WHERE fk_elem = $edit_id
                                AND fk_hizkuntza = $h_id";
                        
                        $dbo->query($sql) or die($dbo->ShowError());
                        
                        $rowHizk = $dbo->emaitza();
                        
                        $elementua->hizkuntzak[$h_id] = new stdClass();
                        
                        $elementua->hizkuntzak[$h_id]->izena= $rowHizk["izena"];
                    }
                }
                
                $content = "inc/bistak/multzokatu/multzokatu_elementua.php";
                
            } else {    
                
                $sql = "SELECT id
                        FROM multzokatu_elementuak
                        WHERE fk_taldea = " . $id_taldea;
                
                $elementuak = get_query($sql);
                
                $content = "inc/bistak/multzokatu/multzokatu_elementuak.php";
            }
            
        } else {
            
            $sql = "SELECT id
                    FROM multzokatu_taldeak
                    WHERE fk_ariketa = " . $id_ariketa;
            
            $elementuak = get_query($sql);
            
            $content = "inc/bistak/multzokatu/multzokatu_taldeak.php";
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
                WHERE A.fk_ariketa_mota = 5
                ORDER BY B.izena ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/multzokatu/multzokatuak.php";
    }
?>