<?php
    $url_base = URL_BASE_ADMIN . "galdera-erantzunak/";
    
    $menu_aktibo = "galdera-erantzunak";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	// erregistro datuak prestatu
	$erregistro_datuak['elementu_mota'] = 'ariketa';
	$erregistro_datuak['fk_sortze_erabiltzailea'] = $erabiltzailea->get_id();
	$erregistro_datuak['fk_aldatze_erabiltzailea'] = $erabiltzailea->get_id();
    
    $hurrengoa = $url->hurrengoa();
	
    function HHMMSStikSegundoetara($hhmmss) {
        
        // Kate hutsa pasaz gero -1 itzuli.
        if ($hhmmss == "") {
            
            return -1;
            
        }
        
        sscanf($hhmmss, "%d:%d:%d", $hours, $minutes, $seconds);
        
        $segundoak = isset($hours) ? $hours * 3600 + $minutes * 60 + $seconds : $minutes * 60 + $seconds;
        
        return $segundoak;
        
    }
    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Galdera-erantzun ariketa bat ezabatu behar dugu.
		if (isset ($_GET["ezab_id"])) {
			
            $ezab_id = $_GET["ezab_id"];
            
            // Ariketa honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
            Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ariketak_etiketak');
                
             
            
            // Ariketaren galderen erantzunak ezabatuko ditugu.
            $sql = "DELETE A, B
                    FROM galdera_erantzunak_galdera_erantzunak A
                    INNER JOIN galdera_erantzunak_galdera_erantzunak_hizkuntzak B
                    ON A.id = B.fk_elem
                    WHERE A.fk_galdera IN
                        (SELECT id FROM galdera_erantzunak_galderak WHERE fk_ariketa = $ezab_id)";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            // Ariketaren galderak ezabatuko ditugu.
            $sql = "DELETE A, B
                    FROM galdera_erantzunak_galderak A
                    INNER JOIN galdera_erantzunak_galderak_hizkuntzak B
                    ON A.id = B.fk_elem
                    AND A.fk_ariketa = $ezab_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
			// Ariketaren hizkuntza desberdinetako errenkadak ezabatuko ditugu.
			$sql = "DELETE FROM ariketak_hizkuntzak
                    WHERE fk_elem = '$ezab_id'";
            
			$dbo->query($sql) or die($dbo->ShowError());
            
			// Ariketa ezabatuko dugu.
			$sql = "DELETE FROM ariketak
                    WHERE id='$ezab_id'";
			
            $dbo->query ($sql) or die ($dbo->ShowError ());
            
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
        
        // Inserciones o modificaciones
		if (isset($_POST["gorde"])) {
            
            $edit_id = $_POST["edit_id"];
            
            $nice_name = nice_name_hizkuntzak("ariketak", "izena", $edit_id);
            
            $id_ikus_entzunezkoa = isset($_POST["ikus-entzunezkoa"]) ? (int) $_POST["ikus-entzunezkoa"] : 0;
            
            if (!is_dbtable_id("ariketak", $edit_id)) {
                
                $sql = "INSERT INTO ariketak (egoera, fk_ariketa_mota, fk_ikus_entzunezkoa)
                        VALUES (0, 2, $id_ikus_entzunezkoa)";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // id-a eskuratuko dugu
				$edit_id = db_taula_azken_id("ariketak");
				
            } else {
                
                $sql = "UPDATE ariketak
                        SET fk_ikus_entzunezkoa = $id_ikus_entzunezkoa
                        WHERE id = $edit_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
            }
            
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
            
            $galdera_erantzuna = new stdClass();
            
            $galdera_erantzuna->id = $row["id"];
            $galdera_erantzuna->orden = $row["orden"];
            
            $sql = "SELECT B.id, B.mota, B.bideo_path, B.bideo_mp4, bideo_webm, B.audio_path, B.audio_mp3, B.audio_ogg
                    FROM ariketak AS A
                    INNER JOIN ikus_entzunezkoak AS B
                    ON A.fk_ikus_entzunezkoa = B.id
                    WHERE A.id = $edit_id";
            
            $emaitza = get_query($sql);
            
            $galdera_erantzuna->ikus_entzunezkoa = new stdClass();
            
            $galdera_erantzuna->ikus_entzunezkoa->id = $emaitza[0]["id"];
            $galdera_erantzuna->ikus_entzunezkoa->mota = $emaitza[0]["mota"];
            $galdera_erantzuna->ikus_entzunezkoa->bideo_path = $emaitza[0]["bideo_path"];
            $galdera_erantzuna->ikus_entzunezkoa->bideo_mp4 = $emaitza[0]["bideo_mp4"];
            $galdera_erantzuna->ikus_entzunezkoa->bideo_webm = $emaitza[0]["bideo_webm"];
            $galdera_erantzuna->ikus_entzunezkoa->audio_path = $emaitza[0]["audio_path"];
            $galdera_erantzuna->ikus_entzunezkoa->audio_mp3 = $emaitza[0]["audio_mp3"];
            $galdera_erantzuna->ikus_entzunezkoa->audio_ogg = $emaitza[0]["audio_ogg"];
            
            $sql = "SELECT B.id, C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $edit_id AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $galdera_erantzuna->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->id = $row["id"];
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $galdera_erantzuna->dokumentuak[] = $tmp_dokumentua;
            }
            
            $galdera_erantzuna->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT izena, azalpena
                        FROM ariketak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $galdera_erantzuna->hizkuntzak[$h_id] = new stdClass();
                
                $galdera_erantzuna->hizkuntzak[$h_id]->izena = $rowHizk["izena"];
                $galdera_erantzuna->hizkuntzak[$h_id]->azalpena = $rowHizk["azalpena"];
                
            }
            
        }
        
        $sql = "SELECT A.id, A.bideo_path, A.bideo_mp4, A.bideo_webm, A.audio_path, A.audio_mp3, A.audio_ogg, A.mota, B.izenburua
                FROM ikus_entzunezkoak AS A
                INNER JOIN ikus_entzunezkoak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . " AND A.mota != '' AND A.mota IS NOT NULL
                ORDER BY B.izenburua ASC";
        
        $ikus_entzunezkoak = get_query($sql);
        
        $sql = "SELECT A.id, B.izenburua, A.path_dokumentua, A.dokumentua
                FROM dokumentuak AS A
                INNER JOIN dokumentuak_hizkuntzak AS B
                ON A.id = B.fk_elem AND A.dokumentua IS NOT NULL
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"];
        
        $dokumentuak = get_query($sql);
        
        $content = "inc/bistak/galdera_erantzunak/galdera_erantzuna.php";
        
    } else if ($hurrengoa == "galderak") {
		
        $url_base = URL_BASE_ADMIN . "galdera-erantzunak/galderak/";
		
        $id_ariketa = isset($_GET["id_ariketa"]) ? (int) $_GET["id_ariketa"] : 0;
        
		$hurrengoa = $url->hurrengoa();
		
        if ($hurrengoa == "form") {
            
            $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
            
            // Galdera bat ezabatu behar dugu.
            if (isset ($_GET["ezab_id"])) {
                
                $ezab_id = isset($_GET["ezab_id"]) ? (int) $_GET["ezab_id"] : 0;
                
                $url_param = "?id_ariketa=" . $id_ariketa;
                
                // Galderaren erantzunak ezabatuko ditugu.
                $sql = "DELETE A, B
                        FROM galdera_erantzunak_galdera_erantzunak A
                        INNER JOIN galdera_erantzunak_galdera_erantzunak_hizkuntzak B
                        ON A.id = B.fk_elem
                        WHERE A.fk_galdera = $ezab_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
            
                // Galderaren hizkuntza desberdinetako errenkadak ezabatuko ditugu.
                $sql = "DELETE FROM galdera_erantzunak_galderak_hizkuntzak
                        WHERE fk_elem = '$ezab_id'";
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Galdera ezabatuko dugu.
                $sql = "DELETE FROM galdera_erantzunak_galderak
                        WHERE id='$ezab_id'";
                $dbo->query ($sql) or die ($dbo->ShowError ());
                
				//erregistro datuak gorde
				$erregistro_datuak['fk_elementua'] = $id_ariketa;
				save_erregistro_datuak($erregistro_datuak);
				
                // Berbideratu.
                header ("Location: " . $url_base . $url_param);
                exit;
            }
            
            // Galdera txertatu edo eguneratu behar bada.
            if (isset($_POST["gorde"])) {
                
                $edit_id = isset($_POST["edit_id"]) ? (int) $_POST["edit_id"] : 0;
                $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
                
                $url_param = "?id_ariketa=" . $id_ariketa;
                
                // $id_ariketa ezarrita dagoela egiaztatuko dugu.
                if ($id_ariketa > 0) {
                    
                    if (!is_dbtable_id("galdera_erantzunak_galderak", $edit_id)) {
                        
                        $sql = "INSERT INTO galdera_erantzunak_galderak (fk_ariketa)
                                VALUES (" . $id_ariketa . ")";
                        
                        $dbo->query($sql) or die($dbo->ShowError());
                        
                        // id-a eskuratuko dugu
                        $edit_id = db_taula_azken_id("galdera_erantzunak_galderak");
                        
                    }
                    
                    foreach (hizkuntza_idak() as $h_id) {
                        
                        $galdera = isset($_POST["galdera_$h_id"]) ? testu_formatua_sql($_POST["galdera_$h_id"]) : "";
                        $denbora = isset($_POST["denbora_$h_id"]) ? testu_formatua_sql($_POST["denbora_$h_id"]) : "";
                        
                        $denbora = HHMMSStikSegundoetara($denbora);
                        
                        $galdera_noiz = isset($_POST["galdera_noiz_$h_id"]) ? testu_formatua_sql($_POST["galdera_noiz_$h_id"]) : "";
                        
                        if ($galdera_noiz == "bideoa_amaitzean") {
                            
                            $denbora = -1;
                            
                        }
                        
                        // Errenkada dagoeneko existitzen den egiaztatuko dugu.
                        $sql = "SELECT * FROM galdera_erantzunak_galderak_hizkuntzak
                                WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                        
                        $dbo->query($sql) or die($dbo->ShowError());
                        
                        if ($dbo->emaitza_kopurua() == 0) {
                            
                            $sql = "INSERT INTO galdera_erantzunak_galderak_hizkuntzak (denbora, galdera, fk_elem, fk_hizkuntza)
                                    VALUES ($denbora, '$galdera', '$edit_id', '$h_id')";
                            
                        } else {
                            
                            $sql = "UPDATE galdera_erantzunak_galderak_hizkuntzak
                                    SET denbora = $denbora, galdera = '$galdera'
                                    WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                            
                        }
                        
                        $dbo->query($sql) or die($dbo->ShowError());
                        
                    }
                }
                
				//erregistro datuak gorde
				$erregistro_datuak['fk_elementua'] = $id_ariketa;
				save_erregistro_datuak($erregistro_datuak);
				
                // Berbideratu.
                header("Location: " . $url_base . $url_param);
                exit();
            }
            
            $sql = "SELECT B.mota, B.bideo_path, B.bideo_mp4, bideo_webm, B.audio_path, B.audio_mp3, B.audio_ogg
                    FROM ariketak AS A
                    INNER JOIN ikus_entzunezkoak AS B
                    ON A.fk_ikus_entzunezkoa = B.id
                    WHERE A.id = $id_ariketa";
            
            $emaitza = get_query($sql);
            
            $galdera->ikus_entzunezkoa = new stdClass();
            
            $galdera->ikus_entzunezkoa->mota = $emaitza[0]["mota"];
            $galdera->ikus_entzunezkoa->bideo_path = $emaitza[0]["bideo_path"];
            $galdera->ikus_entzunezkoa->bideo_mp4 = $emaitza[0]["bideo_mp4"];
            $galdera->ikus_entzunezkoa->bideo_webm = $emaitza[0]["bideo_webm"];
            $galdera->ikus_entzunezkoa->audio_path = $emaitza[0]["audio_path"];
            $galdera->ikus_entzunezkoa->audio_mp3 = $emaitza[0]["audio_mp3"];
            $galdera->ikus_entzunezkoa->audio_ogg = $emaitza[0]["audio_ogg"];
            
            $galdera->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT denbora, galdera
                        FROM galdera_erantzunak_galderak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $galdera->hizkuntzak[$h_id] = new stdClass();
                
                $galdera->hizkuntzak[$h_id]->denbora= $rowHizk["denbora"];
                $galdera->hizkuntzak[$h_id]->galdera= $rowHizk["galdera"];
                
            }
            
            $content = "inc/bistak/galdera_erantzunak/galdera_erantzuna_galdera.php";
            
		} else if ($hurrengoa == "erantzunak") {
            
            $url_base = URL_BASE_ADMIN . "galdera-erantzunak/galderak/erantzunak/";
            
            $id_ariketa = isset($_GET["id_ariketa"]) ? (int) $_GET["id_ariketa"] : 0;
            
            $id_galdera = isset($_GET["id_galdera"]) ? (int) $_GET["id_galdera"] : 0;
            
            if ($url->hurrengoa() == "form") {
                
                $edit_id = isset($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
                
                // Erantzun bat ezabatu behar dugu.
                if (isset ($_GET["ezab_id"])) {
                    
                    $ezab_id = isset($_GET["ezab_id"]) ? (int) $_GET["ezab_id"] : 0;
                    
                    $url_param = "?id_ariketa=" . $id_ariketa . "&id_galdera=" . $id_galdera;
                    
                    // Erantzunaren hizkuntza desberdinetako errenkadak ezabatuko ditugu.
                    $sql = "DELETE FROM galdera_erantzunak_galdera_erantzunak_hizkuntzak
                            WHERE fk_elem = '$ezab_id'";
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Erantzuna ezabatuko dugu.
                    $sql = "DELETE FROM galdera_erantzunak_galdera_erantzunak
                            WHERE id='$ezab_id'";
                    $dbo->query ($sql) or die ($dbo->ShowError ());
                    
					//erregistro datuak gorde
					$erregistro_datuak['fk_elementua'] = $id_ariketa;
					save_erregistro_datuak($erregistro_datuak);
					
                    // Berbideratu.
                    header ("Location: " . $url_base . $url_param);
                    exit;
                }
                
                // Erantzuna txertatu edo eguneratu behar bada.
                if (isset($_POST["gorde"])) {
                    
                    $edit_id = isset($_POST["edit_id"]) ? (int) $_POST["edit_id"] : 0;
                    $id_galdera = isset($_POST["id_galdera"]) ? (int) $_POST["id_galdera"] : 0;
                    $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
                    
                    $url_param = "?id_ariketa=" . $id_ariketa . "&id_galdera=" . $id_galdera;
                    
                    // $id_galdera ezarrita dagoela egiaztatuko dugu.
                    if ($id_galdera > 0) {
                        
                        if (!is_dbtable_id("galdera_erantzunak_galdera_erantzunak", $edit_id)) {
                            
                            $sql = "INSERT INTO galdera_erantzunak_galdera_erantzunak (fk_galdera)
                                    VALUES (" . $id_galdera . ")";
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                            // id-a eskuratuko dugu
                            $edit_id = db_taula_azken_id("galdera_erantzunak_galdera_erantzunak");
                            
                        }
                        
                        foreach (hizkuntza_idak() as $h_id) {
                            
                            $erantzuna = testu_formatua_sql ($_POST["erantzuna_$h_id"]);
                            
                            // Errenkada dagoeneko existitzen den egiaztatuko dugu.
                            $sql = "SELECT *
                                    FROM galdera_erantzunak_galdera_erantzunak_hizkuntzak
                                    WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                            if ($dbo->emaitza_kopurua() == 0) {
                                
                                $sql = "INSERT INTO galdera_erantzunak_galdera_erantzunak_hizkuntzak (erantzuna, fk_elem, fk_hizkuntza)
                                        VALUES ('$erantzuna', '$edit_id', '$h_id')";
                                
                            } else {
                                
                                $sql = "UPDATE galdera_erantzunak_galdera_erantzunak_hizkuntzak
                                        SET erantzuna = '$erantzuna'
                                        WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                                
                            }
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                        }
                    }
                    
					//erregistro datuak gorde
					$erregistro_datuak['fk_elementua'] = $id_ariketa;
					save_erregistro_datuak($erregistro_datuak);
					
                    // Berbideratu.
                    header("Location: " . $url_base . $url_param);
                    exit();
                }
                
                $sql = "SELECT id
                        FROM galdera_erantzunak_galdera_erantzunak
                        WHERE id = $edit_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                if ($dbo->emaitza_kopurua() == 1) {
                    
                    $row = $dbo->emaitza();
                    
                    $erantzuna = new stdClass();
                    
                    $erantzuna->id = $row["id"];
                    
                    $erantzuna->hizkuntzak = array();
                    
                    foreach (hizkuntza_idak() as $h_id) {
                        
                        $sql = "SELECT erantzuna
                                FROM galdera_erantzunak_galdera_erantzunak_hizkuntzak
                                WHERE fk_elem = $edit_id
                                AND fk_hizkuntza = $h_id";
                        
                        $dbo->query($sql) or die($dbo->ShowError());
                        
                        $rowHizk = $dbo->emaitza();
                        
                        $erantzuna->hizkuntzak[$h_id] = new stdClass();
                        
                        $erantzuna->hizkuntzak[$h_id]->erantzuna= $rowHizk["erantzuna"];
                    }
                }
                
                $content = "inc/bistak/galdera_erantzunak/galdera_erantzuna_galdera_erantzuna.php";
				
            } else {
                
                // Erabiltzaileak erantzun baten zuzena checkbox-aren balioa aldatu badu.
                if (isset($_GET["aldatu_zuzena_id"])) {
                    
                    // Galdera-erantzunen multzoaren id-a eskuratu.
                    $id_ariketa = (int) $_GET["id_ariketa"];
                    
                    // Galderaren id-a eskuratu.
                    $id_galdera = (int) $_GET["id_galdera"];
                    
                    // Erantzunaren id-a eskuratu.
                    $id_erantzuna = (int) $_GET["aldatu_zuzena_id"];
                    
                    // Balioa (erantzuna zuzena den ala ez).
                    $zuzena = $_GET["balioa"];
                    
                    // Aldaketa datu-basean gorde.
                    $sql = "UPDATE galdera_erantzunak_galdera_erantzunak
                            SET zuzena = $zuzena
                            WHERE id = $id_erantzuna";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Dagokion orrira berbideratu.
                    $url_param = "?id_ariketa=" . $id_ariketa . "&id_galdera=" . $id_galdera;
                    header("Location: " . $url_base . $url_param);
                }
                
                $sql = "SELECT id, zuzena
                        FROM galdera_erantzunak_galdera_erantzunak
                        WHERE fk_galdera = " . $id_galdera;
                
                $elementuak = get_query($sql);
                
                $content = "inc/bistak/galdera_erantzunak/galdera_erantzuna_galdera_erantzunak.php";
                
            }
            
        } else {
            
            $sql = "SELECT id
                    FROM galdera_erantzunak_galderak
                    WHERE fk_ariketa = " . $id_ariketa;
            
            $elementuak = get_query($sql);
            
            $content = "inc/bistak/galdera_erantzunak/galdera_erantzuna_galderak.php";
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
                WHERE A.fk_ariketa_mota = 2
                ORDER BY B.izena ASC";
        
		$orrikapena = orrikapen_datuak ($sql, $p);
		$sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
		$elementuak = get_query($sql);
		
		$content = "inc/bistak/galdera_erantzunak/galdera_erantzunak.php";
    }
?>