<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    // Hau gabe ezin nuen zerrenda eskuratu AngularJS aplikaziotik, URL desberdinak baitzituzten.
	// Errore mezu hau agertzen zen Chromium-en:
	// Failed to load resource: the server responded with a status of 404 (Not Found) 
	// XMLHttpRequest cannot load http://192.168.2.174/argia-multimedia-zerbitzaria/zerrenda. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access.
	header('Access-Control-Allow-Origin: *');
    
    // Ariketaren hasiera eskuratu (baldin balego).
    $zatia = isset($_GET["term"]) ? testu_formatua_sql($_GET["term"]) : "";
    
    $h_id = isset($_GET["hizkuntza"]) ? (int) $_GET["hizkuntza"] : 1;
    
    $id_ikaslea = isset($_GET["id_ikaslea"]) ? (int) $_GET["id_ikaslea"] : 1;
    
    $hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if ($hurrengoa == "") {
        
        $sql = "SELECT a.id, ah.izena, amh.izena as mota
                FROM ariketak a, ariketak_hizkuntzak ah, ariketa_motak_hizkuntzak amh
                WHERE a.id = ah.fk_elem
                AND a.fk_ariketa_mota = amh.fk_elem
                AND amh.fk_hizkuntza = $h_id
                AND ah.izena LIKE '$zatia%' AND ah.fk_hizkuntza = " . $h_id;
            
        if ($dbo->query($sql)) {
        
            $erantzuna->emaitzak = array();
            
            while ($emaitza = $dbo->emaitza()) {
                $erantzuna->emaitzak[] = array("id" => $emaitza["id"], "izena" => $emaitza["izena"], "mota" => $emaitza["mota"]);
                
            }
            
            // Bezeroari eskaera ondo joan dela jakinarazi.
            http_response_code(200);
            
            $erantzuna->arrakasta = true;
            
        } else {
            
            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
            http_response_code(500);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Errore bat gertatu da datu-basetik ariketak eskuratzean.";
            
        }
        
    } else if ($hurrengoa == "egitekoak") {
        
        if ($h_id > 0 && $id_ikaslea > 0) {
            
            // Ikasleak egin beharreko ariketak eskuratuko ditugu DBtik.
            $sql = "SELECT F.id, G.izena, C.id AS id_ikasgaia, E.izenburua AS ikasgaia, C.bukaera_data, I.id AS id_ariketa_mota, I.izena AS ariketa_mota
                    FROM ikasgelak AS A
                    INNER JOIN ikasgelak_ikasleak AS B
                    ON A.id = B.fk_ikasgela
                    INNER JOIN ikasgaiak AS C
                    ON A.id = C.fk_ikasgela
                    INNER JOIN ikasgaiak_ariketak AS D
                    ON C.id = D.fk_ikasgaia
                    INNER JOIN ikasgaiak_hizkuntzak AS E
                    ON D.fk_ikasgaia = E.fk_elem
                    INNER JOIN ariketak AS F
                    ON D.fk_ariketa = F.id
                    INNER JOIN ariketak_hizkuntzak AS G
                    ON F.id = G.fk_elem
                    INNER JOIN ariketa_motak AS H
                    ON F.fk_ariketa_mota = H.id
                    INNER JOIN ariketa_motak_hizkuntzak AS I
                    ON H.id = I.fk_elem
                    WHERE B.fk_ikaslea = $id_ikaslea
                    AND F.egoera = 1
                    AND G.fk_hizkuntza = $h_id
                    AND I.fk_hizkuntza = $h_id
                    AND C.bukaera_data > NOW()
                    ORDER BY C.bukaera_data DESC";
            
            if ($dbo->query($sql)) {
                
                $erantzuna->egitekoak = array();
                
                while ($emaitza = $dbo->emaitza()) {
                    
                    $erantzuna->egitekoak[] = array("id" => $emaitza["id"],
                                                   "izena" => $emaitza["izena"],
                                                   "bukaera_data" => $emaitza["bukaera_data"],
                                                   "id_ikasgaia" => $emaitza["id_ikasgaia"],
                                                   "ikasgaia" => $emaitza["ikasgaia"],
                                                   "id_ariketa_mota" => $emaitza["id_ariketa_mota"],
                                                   "ariketa_mota" => $emaitza["ariketa_mota"]);
                    
                }
                
                // Bezeroari eskaera ondo joan dela jakinarazi.
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da datu-basetik egin beharreko ariketak eskuratzean.";
                
            }
            
        } else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Egin beharreko ariketen datuen eskaera ez da behar bezala egin.";
            
        }
        
    } else if ($hurrengoa == "egindakoak") {
        
        if ($h_id > 0 && $id_ikaslea > 0) {
            
            // Ikasleak egindako ariketak eskuratuko ditugu DBtik.
            $sql = "SELECT F.id, G.izena, C.id AS id_ikasgaia, E.izenburua AS ikasgaia, C.bukaera_data, I.id AS id_ariketa_mota, I.izena AS ariketa_mota, J.data AS egindako_data
                    FROM ikasgelak AS A
                    INNER JOIN ikasgelak_ikasleak AS B
                    ON A.id = B.fk_ikasgela
                    INNER JOIN ikasgaiak AS C
                    ON A.id = C.fk_ikasgela
                    INNER JOIN ikasgaiak_ariketak AS D
                    ON C.id = D.fk_ikasgaia
                    INNER JOIN ikasgaiak_hizkuntzak AS E
                    ON D.fk_ikasgaia = E.fk_elem
                    INNER JOIN ariketak AS F
                    ON D.fk_ariketa = F.id
                    INNER JOIN ariketak_hizkuntzak AS G
                    ON F.id = G.fk_elem
                    INNER JOIN ariketa_motak AS H
                    ON F.fk_ariketa_mota = H.id
                    INNER JOIN ariketa_motak_hizkuntzak AS I
                    ON H.id = I.fk_elem
                    INNER JOIN ariketa_emaitza AS J
                    ON F.id = J.fk_ariketa AND B.fk_ikaslea = J.fk_ikaslea
                    WHERE B.fk_ikaslea = $id_ikaslea
                    AND F.egoera = 1
                    AND G.fk_hizkuntza = $h_id
                    AND I.fk_hizkuntza = $h_id
                    ORDER BY J.data DESC";
            
            if ($dbo->query($sql)) {
                
                $erantzuna->egindakoak = array();
                
                while ($emaitza = $dbo->emaitza()) {
                    
                    $erantzuna->egindakoak[] = array("id" => $emaitza["id"],
                                                     "izena" => $emaitza["izena"],
                                                     "bukaera_data" => $emaitza["bukaera_data"],
                                                     "egindako_data" => $emaitza["egindako_data"],
                                                     "id_ikasgaia" => $emaitza["id_ikasgaia"],
                                                     "ikasgaia" => $emaitza["ikasgaia"],
                                                     "id_ariketa_mota" => $emaitza["id_ariketa_mota"],
                                                     "ariketa_mota" => $emaitza["ariketa_mota"]);
                    
                }
                
                // Bezeroari eskaera ondo joan dela jakinarazi.
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da datu-basetik egindako ariketak eskuratzean.";
                
            }
            
        } else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Egindako ariketen datuen eskaera ez da behar bezala egin.";
            
        }
        
    } else if ($hurrengoa == "emaitzak") {
        
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            
            $erantzuna->arrakasta = true;
        
            $id_ikasgaia = isset($_POST["id_ikasgaia"]) ? (int) $_POST["id_ikasgaia"] : 0;
            $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
            $id_ikaslea = isset($_POST["id_ikaslea"]) ? (int) $_POST["id_ikaslea"] : 0;
            $zuzenak = isset($_POST["zuzenak"]) && $_POST["zuzenak"] != '' ? str_getcsv($_POST["zuzenak"], ',') : array();
            $okerrak = isset($_POST["okerrak"]) && $_POST["okerrak"] != '' ? str_getcsv($_POST["okerrak"], ',') : array();
            
            $erantzuna->okerrak = $_POST["okerrak"];
            
            $datuak['id_ikasgaia'] = $id_ikasgaia;
            $datuak['id_ariketa'] = $id_ariketa;
            $datuak['id_ikaslea'] = $id_ikaslea;
            $datuak['zuzenak'] = $zuzenak;
            $datuak['okerrak'] = $okerrak;
            
            if ($id_ariketa > 0 && $id_ikasgaia > 0 && $id_ikaslea > 0) {
                
                if(!gorde_emaitzak($datuak)) {
                    
                    $erantzuna->arrakasta = false;
                    $erantzuna->mezua = "Errore bat gertatu da emaitza datu-basean gordetzean.";
                    
                } else {
                    
                    http_response_code(200);
                    
                }
                
            } else {
                
                // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
                // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
                http_response_code(400);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
                
            }
            
        } else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Ezin izan dira emaitzak gorde, eskaera ez da behar bezala egin.";
            
        }
        
    }
    
    echo json_encode($erantzuna);
    
    exit();

?>