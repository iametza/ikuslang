<?php
    
    $url_base = URL_BASE_ADMIN . "dokumentuak/";
    
    define("DOKUMENTUEN_PATH", "dokumentuak/");
    
    $menu_aktibo = "dokumentuak";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Dokumentu bat eta bere datu guztiak ezabatu behar badira
        if (isset($_GET["ezab_id"])) {
            
            // Ezabatu beharreko dokumentuaren id-a eskuratuko dugu.
            $ezab_id = (int) $_GET["ezab_id"];
            
            if ($ezab_id > 0) {
                
                $sql = "SELECT fk_ariketa
                        FROM ariketa_dokumentua
                        WHERE fk_dokumentua = $ezab_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Dokumentua ez bada ariketetan erabiltzen.
                if ($dbo->emaitza_kopurua() == 0) {
                    
                    // Fitxategia ezabatuko dugu.
                    $path_dokumentua = fitxategia_path("dokumentuak", "path_dokumentua", $ezab_id);
                    fitxategia_ezabatu("dokumentuak", "dokumentua", $ezab_id, "../" . $path_dokumentua);
                    
                    // Dokumentuaren DBko datuak ezabatuko ditugu.
                    // Lehenik bere hizkuntza desberdinetako datuak.
                    $sql = "DELETE
                            FROM dokumentuak_hizkuntzak
                            WHERE fk_elem = $ezab_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Ondoren bere datuak.
                    $sql = "DELETE
                            FROM dokumentuak
                            WHERE id = $ezab_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Bere etiketak ere ezabatu (ez etiketak berak).
                    $sql = "DELETE
                            FROM dokumentuak_etiketak
                            WHERE fk_dokumentua = $ezab_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                }
            }
        }
        
        // Inserciones o modificaciones
		if (isset ($_POST["gorde"])) {
            
            // Formularioko datuak eskuratuko ditugu.
			$edit_id = testu_formatua_sql($_POST["edit_id"]);
            
            // Dokumentua zerbitzarira igo.
            $dokumentua = fitxategia_igo("dokumentua", "../" . DOKUMENTUEN_PATH);
            
            // Dokumentua dagoeneko existitzen ez bada, taulan txertatuko dugu.
            if (!is_dbtable_id("dokumentuak", $edit_id)) {
                
                $sql = "INSERT INTO dokumentuak (path_dokumentua, dokumentua)
                        VALUES ('". DOKUMENTUEN_PATH . "', '$dokumentua')";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // id-a eskuratuko dugu
				$edit_id = db_taula_azken_id("dokumentuak");
                
            } else {
                
                // Dokumentua dagoeneko existitzen bada dokumentua aldatu bada bakarrik eguneratuko dugu.
                // Taulan eremu gehiago gehituz gero hau ez da horrela izango eta beste update bat egin beharko da.
                if (trim($dokumentua) != "") {
                    
                    // Taulako bideo zaharren bidea eskuratu.
                    $dokumentuak = fitxategia_path("dokumentuak", "path_dokumentua", $edit_id);
                    
                    // Orain arte zegoen fitxategia ezabatuko ditugu.
                    fitxategia_ezabatu("dokumentuak", "dokumentua", $edit_id, "../" . $path_dokumentua);
                    
                    $sql = "UPDATE dokumentuak
                            SET path_dokumentua = '" . DOKUMENTUEN_PATH . "', dokumentua = '$dokumentua'
                            WHERE id = $edit_id";
                    
                    $dbo->query($sql) or die($dbo->Show());
                    
                }
            }
            
            // Hizkuntza bakoitzeko balioak gordeko ditugu.
            foreach (hizkuntza_idak() as $h_id) {
                
                $izenburua = isset($_POST["izenburua_$h_id"]) ? testu_formatua_sql($_POST["izenburua_$h_id"]) : "";
                $azalpena = isset($_POST["azalpena_$h_id"]) ? testu_formatua_sql($_POST["azalpena_$h_id"]) : "";
                
                // Errenkada dagoeneko existitzen den egiaztatuko dugu.
				$sql = "SELECT *
                        FROM dokumentuak_hizkuntzak
                        WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                if ($dbo->emaitza_kopurua() == 0) {
					
                    $sql = "INSERT INTO dokumentuak_hizkuntzak (izenburua, azalpena, fk_elem, fk_hizkuntza)
                            VALUES ('$izenburua', '$azalpena', '$edit_id', '$h_id')";
                    
				} else {
                    
					$sql = "UPDATE dokumentuak_hizkuntzak
                            SET izenburua = '$izenburua', azalpena = '$azalpena'
                            WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
				}
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Ikus-entzunezko honi dagozkion ikus_entzunezkoak_etiketak taulako errenkadak ezabatuko ditugu.
                Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'dokumentuak_etiketak');
                
                // Etiketak gordeko ditugu orain.
                Etiketak::gordeElementuarenEtiketak($dbo, $edit_id, $h_id, testu_formatua_sql($_POST["hidden-etiketak_$h_id"]), 'dokumentuak_etiketak');
            }
            
            // Berbideratu.
            header("Location: " . $url_base . $url_param);
			exit;
        }
        
        // Fitxategi bat ezabatu behar bada.
        if (isset ($_GET["ezabatu"])) {
            
			switch ($_GET["ezabatu"]) {
                
				case "DOKUMENTUA":
                    
					$path_dokumentua = fitxategia_path("dokumentuak", "path_dokumentua", $edit_id);
					fitxategia_ezabatu("dokumentuak", "dokumentua", $edit_id, "../" . $path_dokumentua);
					
					$mezua = "Dokumentua ezabatu da.";
					break;
				
			}
            
		}
        
        $sql = "SELECT id, path_dokumentua, dokumentua
                FROM dokumentuak
                WHERE id = $edit_id";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($dbo->emaitza_kopurua() == 1) {
            
            $row = $dbo->emaitza();
            
            $dokumentua = new stdClass();
            
            $dokumentua->id = $row["id"];
            $dokumentua->path_dokumentua = $row["path_dokumentua"];
            $dokumentua->dokumentua = $row["dokumentua"];
            
            $dokumentua->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT izenburua, azalpena
                        FROM dokumentuak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $dokumentua->hizkuntzak[$h_id] = new stdClass();
                
                $dokumentua->hizkuntzak[$h_id]->izenburua = $rowHizk["izenburua"];
                $dokumentua->hizkuntzak[$h_id]->azalpena = $rowHizk["azalpena"];
            }
            
        }
        
        $content = "inc/bistak/dokumentuak/dokumentua.php";
        
    } else {
        
        $sql = "SELECT A.id, A.path_dokumentua, A.dokumentua, B.izenburua, (SELECT COUNT(*) FROM ariketa_dokumentua WHERE fk_dokumentua= A.id) AS erabilpenak
                FROM dokumentuak AS A
                INNER JOIN dokumentuak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
                ORDER BY B.izenburua ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/dokumentuak/dokumentuak.php";
        
    }
?>