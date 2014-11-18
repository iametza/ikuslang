<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    $hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        
        // Hizlari baten datuak itzuli behar dira.
        if ($hurrengoa != "") {
            
            $id_hizlaria = (int) $hurrengoa;
            
            if ($id_hizlaria > 0) {
                
                $sql = "SELECT id, kolorea, orden
                        FROM ikus_entzunezkoak_hizlariak
                        WHERE id = " . $id_hizlaria;
                
                if ($dbo->query($sql)) {
                    
                    $emaitza = $dbo->emaitza();
                    
                    $erantzuna->id = $emaitza["id"];
                    $erantzuna->kolorea = $emaitza["kolorea"];
                    $erantzuna->orden = $emaitza["orden"];
                    
                    $erantzuna->hizkuntzak = array();
                    
                    foreach (hizkuntza_idak() as $h_id) {
                        
                        $sql = "SELECT izena, aurrizkia
                                FROM ikus_entzunezkoak_hizlariak_hizkuntzak
                                WHERE fk_elem = $id_hizlaria AND fk_hizkuntza = $h_id";
                        
                        if ($dbo->query($sql)) {
                            
                            for ($i = 0; $i < $dbo->emaitza_kopurua(); $i++) {
                                
                                $rowHizk = $dbo->emaitza();
                                
                                $erantzuna->hizkuntzak[$i] = new stdClass();
                                
                                // Hizkuntzaren id-a.
                                $erantzuna->hizkuntzak[$i]->h_id = $h_id;
                                
                                // Hizkuntzaren izena.
                                $erantzuna->hizkuntzak[$i]->hizkuntza = get_dbtable_field_by_id("hizkuntzak", "izena", $h_id);
                                
                                // Hizlariaren izena eta hipertranskribapeneko hizkuntza honetan.
                                $erantzuna->hizkuntzak[$i]->izena = $rowHizk["izena"];
                                $erantzuna->hizkuntzak[$i]->aurrizkia = $rowHizk["aurrizkia"];
                                
                            }
                            
                        } else {
                            
                            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                            http_response_code(500);
                            
                            $erantzuna->arrakasta = false;
                            $erantzuna->mezua = "Errore bat gertatu da datu-basetik hizlariaren datuak eskuratzean.";
                            
                        }
                    }
                    
                } else {
                    
                    // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                    http_response_code(500);
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da datu-basetik hizlariaren datuak eskuratzean.";
                    
                }
                
            }  else {
                
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Hizlariaren datuen eskaera ez da behar bezala egin.";
                
            }
        }
        
    } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
        
        $id_ikus_entzunezkoa = isset($_POST["id_ikus_entzunezkoa"]) ? (int) $_POST["id_ikus_entzunezkoa"] : 0;
        $id_hizlaria = isset($_POST["id_hizlaria"]) ? (int) $_POST["id_hizlaria"] : 0;
        $kolorea = isset($_POST["kolorea"]) ? testu_formatua_sql($_POST["kolorea"]) : "";   
        
        $erantzuna = new stdClass();
		
        // Hizlari berri bat bada.
        if ($id_hizlaria == 0) {
            
            // Existitzen ez den ikus-entzunezko bati hizlari bat gehitu behar badiogu, lehenik ikus-entzunezkoa sortuko dugu.
            // Ikus-entzunezko berri bat gorde aurretik hizlari bat gehitzen saiatzean gertatzen da hau.
            if ($id_ikus_entzunezkoa == 0) {
                
                $sql = "INSERT INTO ikus_entzunezkoak (bideo_path, bideo_jatorrizkoa, bideo_mp4, bideo_webm)
                        VALUES ('', '', '', '')";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // id-a eskuratuko dugu
                $id_ikus_entzunezkoa = db_taula_azken_id("ikus_entzunezkoak");
                
                // id-a bezeroari itzuliko diogu.
                $erantzuna->id_ikus_entzunezko_berria = $id_ikus_entzunezkoa;
                
                // Hizkuntza bakoitzeko balioak gordeko ditugu.
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $sql = "INSERT INTO ikus_entzunezkoak_hizkuntzak (izenburua, path_azpitituluak, azpitituluak, hipertranskribapena, fk_elem, fk_hizkuntza)
                            VALUES ('', '', '', '', '$id_ikus_entzunezkoa', '$h_id')";
                    
                    $dbo->query($sql) or die($dbo->ShowError());
                    
                }
                
            }
            
            $sql = "INSERT INTO ikus_entzunezkoak_hizlariak (kolorea, fk_elem)
                    VALUES ('$kolorea', $id_ikus_entzunezkoa)";
            
            if ($dbo->query($sql)) {
				
                // Recogemos el id recien creado
				$id_hizlaria = db_taula_azken_id ("ikus_entzunezkoak_hizlariak");
                
                // Hizkuntza bakoitzaren testua gordeko dugu
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $izena = isset($_POST["izena_$h_id"]) ? testu_formatua_sql($_POST["izena_$h_id"]) : "";
                    $aurrizkia = isset($_POST["aurrizkia_$h_id"]) ? testu_formatua_sql($_POST["aurrizkia_$h_id"]) : "";
                    
                    $sql = "INSERT INTO ikus_entzunezkoak_hizlariak_hizkuntzak (izena, aurrizkia, fk_elem, fk_hizkuntza)
						    VALUES ('$izena', '$aurrizkia', $id_hizlaria, $h_id)";
					
					if ($dbo->query($sql)) {
                        
                        // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                        http_response_code(200);
                        
						$erantzuna->arrakasta = true;
						
						// Hizlari berriaren id-a itzuliko dugu.
						$erantzuna->id_hizlari_berria = $id_hizlaria;
                        
                    } else {
                        
                        // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                        http_response_code(500);
                        
                        $erantzuna->arrakasta = false;
                        $erantzuna->mezua = "Errore bat gertatu da hizlariaren datuak datu-basean gordetzean.";
                        
                    }
                    
                }
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da hizlariaren datuak datu-basean gordetzean.";
                
            }
            
        } else {
            
            $sql = "UPDATE ikus_entzunezkoak_hizlariak
                    SET kolorea = '$kolorea'
                    WHERE id = '$id_hizlaria'";
			
            if ($dbo->query($sql)) {
                
                // Hizkuntza bakoitzaren testua gordeko dugu
                foreach (hizkuntza_idak() as $h_id){
                    
                    $izena = isset($_POST["izena_$h_id"]) ? testu_formatua_sql($_POST["izena_$h_id"]) : "";
					$aurrizkia = isset($_POST["aurrizkia_$h_id"]) ? testu_formatua_sql($_POST["aurrizkia_$h_id"]) : "";
                    
                    $sql = "UPDATE ikus_entzunezkoak_hizlariak_hizkuntzak
                            SET izena = '$izena', aurrizkia = '$aurrizkia'
                            WHERE fk_elem = $id_hizlaria
                            AND fk_hizkuntza = $h_id";
                    
                    if ($dbo->query($sql)) {
                        
                        // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                        http_response_code(200);
                        
						$erantzuna->arrakasta = true;
                        
                        $erantzuna->id_hizlaria = $id_hizlaria;
                        
                    } else {
                        
                        // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                        http_response_code(500);
                        
                        $erantzuna->arrakasta = false;
                        $erantzuna->mezua = "Errore bat gertatu da hizlariaren datuak datu-basean eguneratzean.";
                        
                    }
                    
                }
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da hizlariaren datuak datu-basean eguneratzean.";
                
            }
            
        }
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>