<?php
    
    $url_base = URL_BASE_ADMIN . "ikus-entzunezkoak/";
    
    define("AZPITITULUEN_PATH", "azpitituluak/");
    define("BIDEOEN_PATH", "bideoak/");
    define("AUDIOEN_PATH", "audioak/");
    
    $menu_aktibo = "ikus-entzunezkoak";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    // Hauek dira sarrera bezala onartuko ditugun audio eta bideo-formatuak.
    // Kontutan izan gero dagokion formatuetara bihurtu beharko ditugula.
    $bideo_formatuak = array("mpg", "mpeg", "mp4", "webm", "avi");
    $audio_formatuak = array("mp3", "ogg");
    
    if ($hurrengoa == "azpitituluak"){
		require("azpitituluak.load.php");		
		return;
	}
    
    if ($hurrengoa === "form") {
        
        $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        
        // Ikus-entzunezko bat eta bere datu eta fitxategi guztiak ezabatu behar dira.
        if (isset($_GET["ezab_id"])) {
            
            $ezab_id = (int) $_GET["ezab_id"];
            
            if ($ezab_id > 0) {
                
                $sql = "SELECT id
                        FROM ariketak
                        WHERE fk_ikus_entzunezkoa = $ezab_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Ikus-entzunezkoa ez bada ariketetan erabiltzen.
                if ($dbo->emaitza_kopurua() == 0) {
                    
                    // Azpititulu-fitxategirik balego -> ezabatu.
                    $path_azpititulua = fitxategia_path_hizkuntza("ikus_entzunezkoak", "path_azpitituluak", $ezab_id, $h_id);
                    fitxategia_ezabatu_hizkuntza("ikus_entzunezkoak_hizkuntzak", "azpitituluak", $ezab_id, $h_id, "../" . $path_azpititulua);
                    
                    // Audio-fitxategirik balego -> ezabatu.
                    $path_audioa = fitxategia_path("ikus_entzunezkoak", "audio_path", $ezab_id);
                    
                    fitxategia_ezabatu("ikus_entzunezkoak", "audio_jatorrizkoa", $ezab_id, "../" . $path_audioa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "audio_mp3", $ezab_id, "../" . $path_audioa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "audio_ogg", $ezab_id, "../" . $path_audioa);
                    
                    // Bideo-fitxategirik balego -> ezabatu.
                    $path_bideoa = fitxategia_path("ikus_entzunezkoak", "bideo_path", $ezab_id);
                    
                    fitxategia_ezabatu("ikus_entzunezkoak", "bideo_jatorrizkoa", $ezab_id, "../" . $path_bideoa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "bideo_mp4", $ezab_id, "../" . $path_bideoa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "bideo_webm", $ezab_id, "../" . $path_bideoa);
                    
                    // Ikus-entzunezkoaren DBko datuak ezabatuko ditugu.
                    // Lehenik bere hizkuntza desberdinetako datuak.
                    $sql = "DELETE
                            FROM ikus_entzunezkoak_hizkuntzak
                            WHERE fk_elem = $ezab_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Ondoren bere datuak.
                    $sql = "DELETE
                            FROM ikus_entzunezkoak
                            WHERE id = $ezab_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                    // Bere etiketak ere ezabatu (ez etiketak berak).
                    $sql = "DELETE
                            FROM ikus_entzunezkoak_etiketak
                            WHERE fk_elementua = $ezab_id";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                }
                
                //Redireccionamos.
                header ("Location: " . $url_base . $url_param);
                exit;
            }
        }
        
        // Inserciones o modificaciones
		if (isset($_POST["gorde"])) {
           
            // Formularioko datuak eskuratuko ditugu.
			$edit_id = testu_formatua_sql($_POST["edit_id"]);
            
            if($edit_id == '0'){
                $sql = "INSERT INTO ikus_entzunezkoak (mota, bideo_path, bideo_jatorrizkoa, bideo_mp4, bideo_webm)
                        VALUES ('$mota', '". BIDEOEN_PATH . "', '$bideo_jatorrizkoa', '$bideo_mp4', '$bideo_webm')";
                
               $dbo->query($sql) or die($dbo->ShowError());
                
                // id-a eskuratuko dugu
                $edit_id = db_taula_azken_id("ikus_entzunezkoak");
            }            
            // HURRENGO BLOKE HAU FITXA ALDATZEN DENEAN; SORTZEN DENEAN EZ DA EGIN BEHAR (FITXATEGIRIK EZ DAGO)
            else {
            
                // Fitxategi-mota identifikatzeko MIME mota lortzen saiatu naiz baina batzutan gauza arraroak gertatzen dira,
                // adibidez, mpg fitxategi batzuk "application/octet-stream" MIME mota itzultzen dute.
                /*$finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime=finfo_file($finfo, $_FILES['ikus_entzunezkoa_jatorrizkoa']['tmp_name']);
                var_dump($mime);
                finfo_close($finfo);*/
                
                // Fitxategien luzapena erabiliko dut fitxategi-mota identifikatzeko.
                $luzapena = pathinfo($_FILES['ikus_entzunezkoa_jatorrizkoa']['name'], PATHINFO_EXTENSION);
                
                // Fitxategi bat gehitu badu erabiltzaileak eta ez bada errorerik eman.
                if ($_FILES['ikus_entzunezkoa_jatorrizkoa']['error'] == 0 && !empty($_FILES['ikus_entzunezkoa_jatorrizkoa']['tmp_name'])) {
                    
                    // Fitxategia bideo edo audioa den jakin behar dugu.
                    if (in_array($luzapena, $bideo_formatuak)) {
                        
                        $mota = "bideoa";
                        
                        // GOGORATU: Jatorrizko bideoa zerbitzarira igo ondoren mp4 eta webm formatuetara bihurtu behar da
                        // eta bideoen izenak dagokien aldagaietan gorde.
                        $bideo_jatorrizkoa = fitxategia_igo("ikus_entzunezkoa_jatorrizkoa", "../" . BIDEOEN_PATH);
                        $bideo_mp4 = "";
                        $bideo_webm = "";
                        
                        // Ikus-entzunezkoa dagoeneko existitzen ez bada, taulan txertatuko dugu.
                        if (!is_dbtable_id("ikus_entzunezkoak", $edit_id)) {
                            
                            $sql = "INSERT INTO ikus_entzunezkoak (mota, bideo_path, bideo_jatorrizkoa, bideo_mp4, bideo_webm)
                                    VALUES ('$mota', '". BIDEOEN_PATH . "', '$bideo_jatorrizkoa', '$bideo_mp4', '$bideo_webm')";
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                            // id-a eskuratuko dugu
                            $edit_id = db_taula_azken_id("ikus_entzunezkoak");
                            
                        } else {
                            
                            // Ikus-entzunezkoa dagoeneko existitzen bada bideoa aldatu bada bakarrik eguneratuko dugu.
                            // Taulan eremu gehiago gehituz gero hau ez da horrela izango eta beste update bat egin beharko da.
                            if (trim($bideo_jatorrizkoa) != "") {
                                
                                // Taulako bideo zaharren bidea eskuratu.
                                $bideo_path = fitxategia_path("ikus_entzunezkoak", "bideo_path", $edit_id);
                                
                                // Orain arte zeuden hiru bideo-fitxategiak ezabatuko ditugu.
                                fitxategia_ezabatu("ikus_entzunezkoak", "bideo_jatorrizkoa", $edit_id, "../" . $bideo_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "bideo_mp4", $edit_id, "../" . $bideo_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "bideo_webm", $edit_id, "../" . $bideo_path);
                                
                                // Badaezpada ere audio-fitxategiak ere ezabatuko ditugu, posible baita ikus-entzunezko mota aldatu izana.
                                // Horretarako, taulako audio zaharren bidea eskuratuko dugu.
                                $audio_path = fitxategia_path("ikus_entzunezkoak", "audio_path", $edit_id);
                                
                                fitxategia_ezabatu("ikus_entzunezkoak", "audio_jatorrizkoa", $edit_id, "../" . $audio_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "audio_mp3", $edit_id, "../" . $audio_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "audio_ogg", $edit_id, "../" . $audio_path);
                                
                                $sql = "UPDATE ikus_entzunezkoak
                                        SET mota= '$mota', bideo_path = '" . BIDEOEN_PATH . "', bideo_jatorrizkoa = '$bideo_jatorrizkoa', bideo_mp4 = '$bideo_mp4', bideo_webm = '$bideo_webm'
                                        WHERE id = $edit_id";
                                
                                $dbo->query($sql) or die($dbo->Show());
                                
                            }
                        }
                        
                    } else if (in_array($luzapena, $audio_formatuak)) {
                        
                        $mota = "audioa";
                        
                        // GOGORATU: Jatorrizko audioa zerbitzarira igo ondoren mp3 eta ogg formatuetara bihurtu behar da
                        // eta audioen izenak dagokien aldagaietan gorde.
                        $audio_jatorrizkoa = fitxategia_igo("ikus_entzunezkoa_jatorrizkoa", "../" . AUDIOEN_PATH);
                        $audio_mp3 = "";
                        $audio_ogg = "";
                        
                        // Ikus-entzunezkoa dagoeneko existitzen ez bada, taulan txertatuko dugu.
                        if (!is_dbtable_id("ikus_entzunezkoak", $edit_id)) {
                            
                            $sql = "INSERT INTO ikus_entzunezkoak (mota, audio_path, audio_jatorrizkoa, audio_mp3, audio_ogg)
                                    VALUES ('$mota', '". AUDIOEN_PATH . "', '$audio_jatorrizkoa', '$audio_mp3', '$audio_ogg')";
                            
                            $dbo->query($sql) or die($dbo->ShowError());
                            
                            // id-a eskuratuko dugu
                            $edit_id = db_taula_azken_id("ikus_entzunezkoak");
                            
                        } else {
                            
                            // Ikus-entzunezkoa dagoeneko existitzen bada audioa aldatu bada bakarrik eguneratuko dugu.
                            // Taulan eremu gehiago gehituz gero hau ez da horrela izango eta beste update bat egin beharko da.
                            if (trim($audio_jatorrizkoa) != "") {
                                
                                // Taulako audio zaharren bidea eskuratu.
                                $audio_path = fitxategia_path("ikus_entzunezkoak", "audio_path", $edit_id);
                                
                                // Orain arte zeuden hiru audio-fitxategiak ezabatuko ditugu.
                                fitxategia_ezabatu("ikus_entzunezkoak", "audio_jatorrizkoa", $edit_id, "../" . $audio_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "audio_mp3", $edit_id, "../" . $audio_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "audio_ogg", $edit_id, "../" . $audio_path);
                                
                                // Badaezpada ere bideo-fitxategiak ere ezabatuko ditugu, posible baita ikus-entzunezko mota aldatu izana.
                                // Horretarako, taulako bideo zaharren bidea eskuratuko dugu.
                                $bideo_path = fitxategia_path("ikus_entzunezkoak", "bideo_path", $edit_id);
                                
                                fitxategia_ezabatu("ikus_entzunezkoak", "bideo_jatorrizkoa", $edit_id, "../" . $bideo_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "bideo_mp4", $edit_id, "../" . $bideo_path);
                                fitxategia_ezabatu("ikus_entzunezkoak", "bideo_webm", $edit_id, "../" . $bideo_path);
                                
                                $sql = "UPDATE ikus_entzunezkoak
                                        SET mota= '$mota', audio_path = '" . AUDIOEN_PATH . "', audio_jatorrizkoa = '$audio_jatorrizkoa', audio_mp3 = '$audio_mp4', audio_ogg = '$audio_ogg'
                                        WHERE id = $edit_id";
                                
                                $dbo->query($sql) or die($dbo->Show());
                                
                            }
                        }
                        
                    } else {
                        
                        // Gehitutako fitxategia ez da onartutako bideo edo audio fitxategi-mota bat.
                        // Erabiltzaileari jakinarazi behar zaio.
                        
                        $mezua = "Gehitutako fitxategia ez da onartutako bideo edo audio fitxategi-mota bat.";
                        
                        // Berriz ere orri berdinera berbideratu.
                        header("Location: " . $url_base . $url_param);
                        exit();
                        
                    }
                    
                } elseif( !empty($_FILES['ikus_entzunezkoa_jatorrizkoa']['tmp_name']) ) {
                    
                    $mezua = "Errore bat gertatu da fitxategia zerbitzarira kargatzean.";
                    
                    // Berriz ere orri berdinera berbideratu.
                    header("Location: " . $url_base . "form" . $url_param .  "&edit_id=" . $edit_id);
                    exit();
                    
                }
            
            }
           
            // Hizkuntza bakoitzeko balioak gordeko ditugu.
            foreach (hizkuntza_idak() as $h_id) {
                
                $izenburua = isset($_POST["izenburua_$h_id"]) ? testu_formatua_sql($_POST["izenburua_$h_id"]) : "";
                
                // Errenkada dagoeneko existitzen den egiaztatuko dugu.
				$sql = "SELECT *
                        FROM ikus_entzunezkoak_hizkuntzak
                        WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                if ($dbo->emaitza_kopurua() == 0) {
					
                    $sql = "INSERT INTO ikus_entzunezkoak_hizkuntzak (izenburua, path_azpitituluak, azpitituluak, hipertranskribapena, fk_elem, fk_hizkuntza)
                            VALUES ('$izenburua', '', '', '', '$edit_id', '$h_id')";
                    
				} else {
                    
					$sql = "UPDATE ikus_entzunezkoak_hizkuntzak
                            SET izenburua = '$izenburua'
                            WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
                    
				}
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $azpitituluak = fitxategia_igo("azpitituluak_$h_id", "../" . AZPITITULUEN_PATH);
                
                if (trim($azpitituluak) != "") {
                    
                    // Taulako azpititulu zaharraren bidea eskuratu.
                    $path_azpitituluak = fitxategia_path("ikus_entzunezkoak_hizkuntzak", "path_azpitituluak", $edit_id, $h_id);
                    
                    // Orain arte zegoen azpititulu-fitxategia ezabatuko dugu.
                    fitxategia_ezabatu_hizkuntza("ikus_entzunezkoak_hizkuntzak", "azpitituluak", $edit_id, $h_id, "../" . $path_azpitituluak, $h_id);
                    
                    $sql = "UPDATE ikus_entzunezkoak_hizkuntzak
                            SET path_azpitituluak = '" . AZPITITULUEN_PATH . "', azpitituluak = '$azpitituluak'
                            WHERE id = $edit_id";
                    
                    $dbo->query($sql) or die($dbo->Show());
                    
                }
                
                // Ikus-entzunezko honi dagozkion ikus_entzunezkoak_etiketak taulako errenkadak ezabatuko ditugu.
                Etiketak::kenduElementuarenEtiketak($dbo, $edit_id, 'ikus_entzunezkoak_etiketak');
                
                // Etiketak gordeko ditugu orain.
                Etiketak::gordeElementuarenEtiketak($dbo, $edit_id, $h_id, testu_formatua_sql($_POST["hidden-etiketak_$h_id"]), 'ikus_entzunezkoak_etiketak');
            }
            
            // Berbideratu.
            header("Location: " . $url_base . $url_param);
			exit;
            
        }
        
        // Fitxategi bat ezabatu behar bada.
        if (isset($_GET["ezabatu"])) {
            
            $h_id = isset($_GET["h_id"]) ? (int) $_GET["h_id"] : 0;
            
			switch ($_GET["ezabatu"]) {
                
				case "AZPITITULUA":
                    
					$path_azpititulua = fitxategia_path_hizkuntza("ikus_entzunezkoak", "path_azpitituluak", $edit_id, $h_id);
					fitxategia_ezabatu_hizkuntza("ikus_entzunezkoak_hizkuntzak", "azpitituluak", $edit_id, $h_id, "../" . $path_azpititulua);
					
					$mezua = "Azpititulu-fitxategia behar bezala ezabatu da.";
					break;
				
                case "AUDIOA":
                    
                    $path_audioa = fitxategia_path("ikus_entzunezkoak", "audio_path", $edit_id);
                    
                    fitxategia_ezabatu("ikus_entzunezkoak", "audio_jatorrizkoa", $edit_id, "../" . $path_audioa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "audio_mp3", $edit_id, "../" . $path_audioa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "audio_ogg", $edit_id, "../" . $path_audioa);
                    
                    $mezua = "Audio-fitxategiak behar bezala ezabatu dira.";
                    break;
                
                case "BIDEOA":
                    
                    $path_bideoa = fitxategia_path("ikus_entzunezkoak", "bideo_path", $edit_id);
                    
                    fitxategia_ezabatu("ikus_entzunezkoak", "bideo_jatorrizkoa", $edit_id, "../" . $path_bideoa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "bideo_mp4", $edit_id, "../" . $path_bideoa);
                    fitxategia_ezabatu("ikus_entzunezkoak", "bideo_webm", $edit_id, "../" . $path_bideoa);
                    
                    $mezua = "Bideo-fitxategiak behar bezala ezabatu dira.";
                    break;
			}
            
		}
        
        $sql = "SELECT id, mota, bideo_path, bideo_jatorrizkoa, bideo_mp4, bideo_webm, audio_path, audio_jatorrizkoa, audio_mp3, audio_ogg
                FROM ikus_entzunezkoak
                WHERE id = $edit_id";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        $ikus_entzunezkoa = new stdClass();
        
        if ($dbo->emaitza_kopurua() == 1) {
            
            $row = $dbo->emaitza();
            
            $ikus_entzunezkoa->id = $row["id"];
            $ikus_entzunezkoa->mota = $row["mota"];
            $ikus_entzunezkoa->bideo_path = $row["bideo_path"];
            $ikus_entzunezkoa->bideo_jatorrizkoa = $row["bideo_jatorrizkoa"];
            $ikus_entzunezkoa->bideo_mp4 = $row["bideo_mp4"];
            $ikus_entzunezkoa->bideo_webm = $row["bideo_webm"];
            $ikus_entzunezkoa->audio_path = $row["audio_path"];
            $ikus_entzunezkoa->audio_jatorrizkoa = $row["audio_jatorrizkoa"];
            $ikus_entzunezkoa->audio_mp3 = $row["audio_mp3"];
            $ikus_entzunezkoa->audio_ogg = $row["audio_ogg"];
            
            $ikus_entzunezkoa->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                
                $sql = "SELECT izenburua, path_azpitituluak, azpitituluak
                        FROM ikus_entzunezkoak_hizkuntzak
                        WHERE fk_elem = $edit_id
                        AND fk_hizkuntza = $h_id";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                $rowHizk = $dbo->emaitza();
                
                $ikus_entzunezkoa->hizkuntzak[$h_id] = new stdClass();
                
                $ikus_entzunezkoa->hizkuntzak[$h_id]->izenburua = $rowHizk["izenburua"];
                $ikus_entzunezkoa->hizkuntzak[$h_id]->path_azpitituluak = $rowHizk["path_azpitituluak"];
                $ikus_entzunezkoa->hizkuntzak[$h_id]->azpitituluak = $rowHizk["azpitituluak"];
            }
            
        }
        
        $content = "inc/bistak/ikus_entzunezkoak/ikus_entzunezkoa.php";
        
    } else if ($hurrengoa == "editatu-hipertranskribapena") {
        
        $edit_id = isset($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
        $auto_azpitituluak = isset($_GET["auto_azpitituluak"]) ? mysql_escape_string($_GET["auto_azpitituluak"]) : "";
		
        $editatu_hipertranskribapena = new stdClass();
        
        $editatu_hipertranskribapena->id_ikus_entzunezkoa = $edit_id;
        
        $editatu_hipertranskribapena->hizkuntzak = array();
        
        foreach (hizkuntza_idak() as $h_id) {
            
            $sql = "SELECT izenburua, path_azpitituluak, azpitituluak
                    FROM ikus_entzunezkoak_hizkuntzak
                    WHERE fk_elem = $edit_id
                    AND fk_hizkuntza = $h_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            if ($dbo->emaitza_kopurua() == 1) {
                
                $emaitza = $dbo->emaitza();
                
                $editatu_hipertranskribapena->hizkuntzak[$h_id]->izenburua = $emaitza["izenburua"];
                $editatu_hipertranskribapena->hizkuntzak[$h_id]->path_azpitituluak = $emaitza["path_azpitituluak"];
                $editatu_hipertranskribapena->hizkuntzak[$h_id]->azpitituluak = $emaitza["azpitituluak"];
				
				if ($auto_azpitituluak != "" && is_file($_SERVER['DOCUMENT_ROOT'] . $auto_azpitituluak)) {
					
					// Vicomtech-en ezagutzaileak \\n bezala dauzka lerro berrien karaktereak.
					$editatu_hipertranskribapena->hizkuntzak[$h_id]->azpitituluak_testua = json_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $auto_azpitituluak));
					
				} else if (is_file($_SERVER['DOCUMENT_ROOT'] . $emaitza["path_azpitituluak"] . $emaitza["azpitituluak"])) {
                    
                    $editatu_hipertranskribapena->hizkuntzak[$h_id]->azpitituluak_testua = json_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $emaitza["path_azpitituluak"] . $emaitza["azpitituluak"]));
					
                }
                
                // Transkribapeneko parrafo hasierak kargatu
                $emaitza = get_query("SELECT indizea_hasiera
                                      FROM ikus_entzunezkoak_parrafo_hasierak
                                      WHERE fk_elem = '" . $editatu_hipertranskribapena->id_ikus_entzunezkoa . "' AND fk_hizkuntza = '$h_id'
                                      ORDER BY indizea_hasiera");
                
                $editatu_hipertranskribapena->hizkuntzak[$h_id]->parrafo_hasierak = array();
                
                foreach ($emaitza as $e) {
                    array_push($editatu_hipertranskribapena->hizkuntzak[$h_id]->parrafo_hasierak, $e["indizea_hasiera"]);
                }
                
            }
            
        }
        
        $content = "inc/bistak/editatu_hipertranskribapena/editatu_hipertranskribapena.php";
        
    } else {
        
        $sql = "SELECT A.id, A.bideo_path, A.bideo_mp4, A.bideo_webm, A.mota, B.izenburua, (SELECT COUNT(*) FROM ariketak WHERE fk_ikus_entzunezkoa = A.id) AS erabilpenak
                FROM ikus_entzunezkoak AS A
                INNER JOIN ikus_entzunezkoak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
                ORDER BY B.izenburua ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        
        $elementuak = get_query($sql);
        
        $content = "inc/bistak/ikus_entzunezkoak/ikus_entzunezkoak.php";
        
    }
?>