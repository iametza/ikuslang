<?php
    
    $url_base = URL_BASE_ADMIN . "hutsuneak-bete/";
    
    $menu_aktibo = "hutsuneak-bete";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	// erregistro datuak prestatu
	$erregistro_datuak['elementu_mota'] = 'ariketa';
	$erregistro_datuak['fk_sortze_erabiltzailea'] = $erabiltzailea->get_id();
	$erregistro_datuak['fk_aldatze_erabiltzailea'] = $erabiltzailea->get_id();
    
    $hurrengoa = $url->hurrengoa();
	    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
		$nice_name = nice_name_hizkuntzak ("ariketak", "izena", $edit_id);
		
        // Hutsuneak bete ariketa bat ezabatu behar dugu.
		if (isset ($_GET["ezab_id"])){
			
            $ezab_id = $_GET["ezab_id"];
            
            // Ariketa honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
            Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ariketak_etiketak');
            
            
            // Ariketa honi dagozkion hutsuneak eta hitzak ezabatuko ditugu.
            $sql = "DELETE A, B
                    FROM hutsuneak_bete_hutsuneak A
                    INNER JOIN hutsuneak_bete_hutsuneak_hitzak B
                    ON A.id = B.fk_hutsunea
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
		if (isset ($_POST["gorde"])) {
            
			// Formularioko datuak eskuratuko ditugu.
			$edit_id = testu_formatua_sql($_POST["edit_id"]);
            
            $nice_name = nice_name_hizkuntzak("ariketak", "izena", $edit_id);
            
            if (!is_dbtable_id("ariketak", $edit_id)) {
                
                $sql = "INSERT INTO ariketak (egoera, fk_ariketa_mota, orden)
                        VALUES (0, 4, " . (orden_max("ariketak", "fk_ariketa_mota = 4") + 1) . ")";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // id-a eskuratuko dugu
				$edit_id = db_taula_azken_id("ariketak");
            }
            
            // Guardamos los datos en cada idioma
			foreach (hizkuntza_idak() as $h_id) {
                
				$izena = testu_formatua_sql($_POST["izena_$h_id"]);
                $nice = $nice_name[$h_id];
                
                // Errenkada dagoeneko existitzen den egiaztatuko dugu.
				$sql = "SELECT *
                        FROM ariketak_hizkuntzak
                        WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                
				$dbo->query($sql) or die($dbo->ShowError());
                
				if ($dbo->emaitza_kopurua() == 0) {
					
                    $sql = "INSERT INTO ariketak_hizkuntzak (izena, nice_name, fk_elem, fk_hizkuntza)
                            VALUES ('$izena', '$nice', '$edit_id', '$h_id')";
                    
				} else {
                    
					$sql = "UPDATE ariketak_hizkuntzak
                            SET izena = '$izena', nice_name = '$nice' WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
				}
                
				$dbo->query($sql) or die($dbo->ShowError());
                
                // Ariketa honi dagozkion ariketak_etiketak taulako errenkadak ezabatuko ditugu.
                Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ariketak_etiketak');
                
                // Etiketak gordeko ditugu orain.
                Etiketak::gordeElementuarenEtiketak($dbo, $edit_id, $h_id, testu_formatua_sql($_POST["hidden-etiketak_$h_id"]), 'ariketak_etiketak');
                
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
            
            $hutsuneak_bete = new stdClass();
            
            $hutsuneak_bete->id = $row["id"];
            $hutsuneak_bete->orden = $row["orden"];
            
            $sql = "SELECT B.mota, B.bideo_path, B.bideo_mp4, bideo_webm, B.audio_path, B.audio_mp3, B.audio_ogg
                    FROM ariketak AS A
                    INNER JOIN ikus_entzunezkoak AS B
                    ON A.fk_ikus_entzunezkoa = B.id
                    WHERE A.id = $edit_id";
            
            $emaitza = get_query($sql);
            
            $hutsuneak_bete->ikus_entzunezkoa = new stdClass();
            
            $hutsuneak_bete->ikus_entzunezkoa->mota = $emaitza[0]["mota"];
            $hutsuneak_bete->ikus_entzunezkoa->bideo_path = $emaitza[0]["bideo_path"];
            $hutsuneak_bete->ikus_entzunezkoa->bideo_mp4 = $emaitza[0]["bideo_mp4"];
            $hutsuneak_bete->ikus_entzunezkoa->bideo_webm = $emaitza[0]["bideo_webm"];
            $hutsuneak_bete->ikus_entzunezkoa->audio_path = $emaitza[0]["audio_path"];
            $hutsuneak_bete->ikus_entzunezkoa->audio_mp3 = $emaitza[0]["audio_mp3"];
            $hutsuneak_bete->ikus_entzunezkoa->audio_ogg = $emaitza[0]["audio_ogg"];
            
            $sql = "SELECT C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $edit_id AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $hutsuneak_bete->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $hutsuneak_bete->dokumentuak[] = $tmp_dokumentua;
            }
            
            $hutsuneak_bete->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT izena
                        FROM ariketak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $hutsuneak_bete->hizkuntzak[$h_id] = new stdClass();
                
                $hutsuneak_bete->hizkuntzak[$h_id]->izena = $rowHizk["izena"];
                
            }
            
        }
        
        $content = "inc/bistak/hutsuneak_bete/hutsuneak_bete_form.php";
        
    } else if ($hurrengoa == "hutsuneak") {
        
        $id_ariketa = isset($_GET["id_ariketa"]) ? (int) $_GET["id_ariketa"] : 0;
        
        $hutsuneak_bete = new stdClass();
        
        $hutsuneak_bete->hizkuntzak = array();
        
        foreach (hizkuntza_idak() as $h_id) {
            
            $sql = "SELECT izena, azalpena
                    FROM ariketak_hizkuntzak
                    WHERE fk_elem = $id_ariketa AND fk_hizkuntza = $h_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            if ($row = $dbo->emaitza()) {
                
                $hutsuneak_bete->hizkuntzak[$h_id] = new stdClass();
                
                $hutsuneak_bete->hizkuntzak[$h_id]->izena = $row["izena"];
                $hutsuneak_bete->hizkuntzak[$h_id]->azalpena = $row["azalpena"];
                
                $hutsuneak_bete->hizkuntzak[$h_id]->hutsuneak = array();
                
                $sql = "SELECT DISTINCT(A.id)
                        FROM hutsuneak_bete_hutsuneak AS A
                        INNER JOIN hutsuneak_bete_hutsunea_hitzak AS B
                        ON A.id = B.fk_hutsunea
                        WHERE A.fk_ariketa = $id_ariketa AND A.fk_hizkuntza = " . $hizkuntza["id"] . "
                        ORDER BY B.denbora";
                
                $emaitza = get_query($sql);
                
                foreach ($emaitza as $errenkada) {
                
                    $tmp_hutsunea = new stdClass();
                    
                    $tmp_hutsunea->id_hutsunea = $errenkada["id"];
                    
                    $tmp_hitzak = array();
                    
                    $sql = "SELECT denbora, testua
                            FROM hutsuneak_bete_hutsunea_hitzak
                            WHERE fk_hutsunea = " . $errenkada['id'] . "
                            ORDER BY denbora ASC";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    while ($errenkada = $dbo->emaitza()) {
                        
                        $tmp_hitza = new stdClass();
                        
                        $tmp_hitza->denbora = $errenkada["denbora"];
                        $tmp_hitza->testua = $errenkada["testua"];
                        
                        $tmp_hitzak[] = $tmp_hitza;
                        
                    }
                    
                    $tmp_hutsunea->hitzak = $tmp_hitzak;
                    
                    $hutsuneak_bete->hizkuntzak[$h_id]->hutsuneak[] = $tmp_hutsunea;
                    
                }
                
            }
            
        }
        
        $emaitza = get_query("SELECT B.id AS id_ikus_entzunezkoa, B.bideo_path, B.bideo_mp4, B.bideo_webm, B.audio_path, B.audio_mp3, B.audio_ogg
                              FROM ariketak AS A
                              INNER JOIN ikus_entzunezkoak AS B
                              ON A.fk_ikus_entzunezkoa = B.id
                              WHERE A.id = $id_ariketa");
        
        if (count($emaitza) == 1) {
            
            $hutsuneak_bete->id_ikus_entzunezkoa = $emaitza[0]["id_ikus_entzunezkoa"];
            $hutsuneak_bete->bideo_path = $emaitza[0]["bideo_path"];
            $hutsuneak_bete->bideo_mp4 = $emaitza[0]["bideo_mp4"];
            $hutsuneak_bete->bideo_webm = $emaitza[0]["bideo_webm"];
            $hutsuneak_bete->audio_path = $emaitza[0]["audio_path"];
            $hutsuneak_bete->audio_mp3 = $emaitza[0]["audio_mp3"];
            $hutsuneak_bete->audio_ogg = $emaitza[0]["audio_ogg"];
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT path_azpitituluak, azpitituluak, hipertranskribapena
                        FROM ikus_entzunezkoak_hizkuntzak
                        WHERE fk_elem = " . $emaitza[0]["id_ikus_entzunezkoa"] . " AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                if ($row = $dbo->emaitza()) {
                    
                    $hutsuneak_bete->hizkuntzak[$h_id]->path_azpitituluak = $row["path_azpitituluak"];
                    $hutsuneak_bete->hizkuntzak[$h_id]->azpitituluak = $row["azpitituluak"];
                    $hutsuneak_bete->hizkuntzak[$h_id]->hipertranskribapena = json_encode($row["hipertranskribapena"]);
                    
                }
                
            }
            
            $sql = "SELECT id
                    FROM ikus_entzunezkoak_hizlariak
                    WHERE fk_elem = " . $emaitza[0]["id_ikus_entzunezkoa"];
            
            $emaitza = get_query($sql);
            
            $hutsuneak_bete->hizlariak = array();
            
            for ($i = 0; $i < count($emaitza); $i++) {
                
                $hutsuneak_bete->hizlariak[$i] = new stdClass();
                
                $hutsuneak_bete->hizlariak[$i]->id = $emaitza[$i]["id"];
                
                $hutsuneak_bete->hizlariak[$i]->hizkuntzak = array();
                
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $sql = "SELECT izena, aurrizkia
                            FROM ikus_entzunezkoak_hizlariak_hizkuntzak
                            WHERE fk_elem = " . $emaitza[$i]["id"] . " AND fk_hizkuntza = $h_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    if ($rowHizk = $dbo->emaitza()) {
                        
                        $hutsuneak_bete->hizlariak[$i]->hizkuntzak[$h_id] = new stdClass();
                        
                        $hutsuneak_bete->hizlariak[$i]->hizkuntzak[$h_id]->izena = $rowHizk["izena"];
                        $hutsuneak_bete->hizlariak[$i]->hizkuntzak[$h_id]->aurrizkia = $rowHizk["aurrizkia"];
                        
                    }
                    
                }
                
            }
        }
        
        $content = "inc/bistak/hutsuneak_bete/hutsuneak_bete_hutsuneak.php";
        
    } else {
        
        // Erabiltzaileak ariketen ordena aldatu badu.
        if (isset($_GET["oid"])) {
            
			$id = $_GET["oid"];
			$bal = $_GET["bal"];
            
			orden_automatiko("ariketak", $id, $bal, "fk_ariketa_mota = 4");
            
			header ("Location: " . $url_base . $url_param);
			exit;
            
		}
        
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
        
        $sql = "SELECT *
                FROM ariketak
                WHERE fk_ariketa_mota = 4
                ORDER BY orden ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/hutsuneak_bete/hutsuneak_bete.php";
    }
?>