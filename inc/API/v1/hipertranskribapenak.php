<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    $hurrengoa = $url->hurrengoa();
    
    $erantzuna = new stdClass();
    
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
        
        $id_ikus_entzunezkoa = isset($_POST["id_ikus_entzunezkoa"]) ? (int) $_POST["id_ikus_entzunezkoa"] : 0;
        $id_hizkuntza = isset($_POST["id_hizkuntza"]) ? (int) $_POST["id_hizkuntza"] : 0;
        $hipertranskribapena = isset($_POST["hipertranskribapena"]) ? $_POST["hipertranskribapena"] : "";
        $parrafo_hasierak = isset($_POST["parrafo_hasierak"]) ? $_POST["parrafo_hasierak"] : "";
        
        // Hipertranskribapenaren testua eguneratu.
        $sql = "UPDATE ikus_entzunezkoak_hizkuntzak
                SET hipertranskribapena = '$hipertranskribapena'
                WHERE fk_elem = $id_ikus_entzunezkoa
                AND fk_hizkuntza = $id_hizkuntza";
        
        if ($dbo->query($sql)) {
                
            // Parrafo hasiera zaharrak ezabatuko ditugu ondoren.
            $sql = "DELETE FROM ikus_entzunezkoak_parrafo_hasierak
                    WHERE fk_elem = " . $id_ikus_entzunezkoa . " AND fk_hizkuntza = " . $id_hizkuntza;
            
            if ($dbo->query($sql)) {
                
                // Ondoren, erabiltzaileak sortutako parrafo hasierak gordeko ditugu.
                foreach($parrafo_hasierak as $parrafo_hasiera) {
                    
                    $sql ="INSERT INTO ikus_entzunezkoak_parrafo_hasierak (indizea_hasiera, fk_elem, fk_hizkuntza)
                           VALUES ($parrafo_hasiera, $id_ikus_entzunezkoa, $id_hizkuntza)";
                    
                    if (!$dbo->query($sql)) {
                        
                        // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                        http_response_code(500);
                        
                        $erantzuna->arrakasta = false;
                        $erantzuna->mezua = "Errore bat gertatu da hipertranskribapenaren datuak datu-basean eguneratzean.";
                        
                        echo json_encode($erantzuna);
                        
                        exit();
                        
                    }
                }
                
                // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
                // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
                http_response_code(200);
                
                $erantzuna->arrakasta = true;
                
            } else {
                
                // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
                http_response_code(500);
                
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = "Errore bat gertatu da hipertranskribapenaren datuak datu-basean eguneratzean.";
                
            }
            
        } else {
            
            // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
            http_response_code(500);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Errore bat gertatu da hipertranskribapenaren datuak datu-basean eguneratzean.";
            
        }
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>