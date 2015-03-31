<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    // Hau gabe ezin nuen zerrenda eskuratu AngularJS aplikaziotik, URL desberdinak baitzituzten.
	// Errore mezu hau agertzen zen Chromium-en:
	// Failed to load resource: the server responded with a status of 404 (Not Found) 
	// XMLHttpRequest cannot load http://192.168.2.174/argia-multimedia-zerbitzaria/zerrenda. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access.
	header('Access-Control-Allow-Origin: *');
    
    $url_hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    $erantzuna->arrakasta = true;
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        
        $id_ariketa = $url_hurrengoa;
        $id_hizkuntza = isset($_GET["id_hizkuntza"]) ? (int) $_GET["id_hizkuntza"] : 0;
        
        $erantzuna->esaldiak_zuzendu = new stdClass();
        
        if ($id_ariketa > 0 && $id_hizkuntza > 0) {
            
            $sql = "SELECT A.id, B.izena, B.azalpena
                    FROM ariketak AS A
                    JOIN ariketak_hizkuntzak AS B
                    ON A.id = B.fk_elem
                    WHERE A.id = " . $id_ariketa . " AND B.fk_hizkuntza = " . $id_hizkuntza;
            
            $dbo->query($sql) or die($dbo->ShowError);
            
            if ($row = $dbo->emaitza()) {
                    
                $erantzuna->esaldiak_zuzendu->izena = $row["izena"];
                $erantzuna->esaldiak_zuzendu->azalpena = $row["azalpena"];
                
                $sql = "SELECT A.id, B.testua, B.ordenak
                        FROM esaldiak_zuzendu_esaldiak AS A
                        JOIN esaldiak_zuzendu_esaldiak_hizkuntzak AS B
                        ON A.id = B.fk_elem
                        WHERE A.fk_ariketa = " . $id_ariketa . " AND B.fk_hizkuntza = " . $id_hizkuntza;
                
                $dbo->query($sql) or die($dbo->ShowError);
                
                $erantzuna->esaldiak_zuzendu->esaldiak = array();
                
                while ($row = $dbo->emaitza()) {
                    
                    $tmp_esaldia = new stdClass();
                    
                    $tmp_esaldia->id = $row["id"];
                    $tmp_esaldia->testua = $row["testua"];
                    $tmp_esaldia->ordenak = $row["ordenak"];
                    
                    array_push($erantzuna->esaldiak_zuzendu->esaldiak, $tmp_esaldia);
                }
                
                // Bezeroari eskaera ondo joan dela jakinarazi.
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da datu-basetik datuak eskuratzean.";
                
            }
            
        }
        
    } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
        
        $erantzuna->arrakasta = true;
        
        $id_ikasgaia = isset($_POST["id_ikasgaia"]) ? (int) $_POST["id_ikasgaia"] : 0;
        $id_ariketa = isset($_POST["id_ariketa"]) ? (int) $_POST["id_ariketa"] : 0;
        $id_ikaslea = isset($_POST["id_ikaslea"]) ? (int) $_POST["id_ikaslea"] : 0;
        $zuzenak = isset($_POST["zuzenak"]) ? $_POST["zuzenak"] : array();
        $okerrak = isset($_POST["okerrak"]) ? $_POST["okerrak"] : array();
        
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
        $erantzuna->mezua = "Ezin izan dira datuak eskuratu, eskaera ez da behar bezala egin.";
        
    }
    
    echo json_encode($erantzuna);
    
    exit();
    
?>