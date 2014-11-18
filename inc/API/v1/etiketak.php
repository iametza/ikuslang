<?php
    
    // Protegemos el archivo del "acceso directo"
	if (!isset($url)) header("Location: /");
    
    // Etiketaren hasiera eskuratu (baldin balego).
    $zatia = isset($_GET["q"]) ? testu_formatua_sql($_GET["q"]) : "";
    
    $id_ariketa = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
    $mota = isset($_GET["mota"]) ? testu_formatua_sql($_GET["mota"]) : "";
    $h_id = isset($_GET["hizkuntza"]) ? (int) $_GET["hizkuntza"] : 1;
    
    $erantzuna = new stdClass();
    
    if ($id_ariketa > 0 && $mota != "") {
        
        $sql = "SELECT A.izena
                FROM etiketak AS A";
        
        if ($mota == "ariketa") {
            
            $sql = $sql . " INNER JOIN ariketak_etiketak AS B
                            ON A.id = B.fk_etiketa
                            WHERE B.fk_elementua = $id_ariketa AND A.fk_hizkuntza = $h_id";
            
        } else if ($mota == "ikus-entzunezkoa") {
            
            $sql = $sql . " INNER JOIN ikus_entzunezkoak_etiketak AS B
                            ON A.id = B.fk_etiketa
                            WHERE B.fk_elementua = $id_ariketa AND A.fk_hizkuntza = $h_id";
            
        } else if ($mota == "dokumentua") {
            
            $sql = $sql . " INNER JOIN dokumentuak_etiketak AS B
                            ON A.id = B.fk_etiketa
                            WHERE B.fk_elementua = $id_ariketa AND A.fk_hizkuntza = $h_id";
            
        } else if ($mota == "ikasgaia") {
            
            $sql = $sql . " INNER JOIN ikasgaiak_etiketak AS B
                            ON A.id = B.fk_etiketa
                            WHERE B.fk_elementua = $id_ariketa AND A.fk_hizkuntza = $h_id";
            
        }
	else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Mota ez da baliozkoa.";
            
            echo json_encode($erantzuna);
            
            exit();
            
        }
        if ($dbo->query($sql)) {
            
            $erantzuna->etiketak = array();
            
            while ($emaitza = $dbo->emaitza()) {
                
                $erantzuna->etiketak[] = $emaitza["izena"];
                
            }
            
            // Ekintzaren ondorioa URI batez identifikatu ezin daitekeenez 200 egoera bueltatzea egokia da.
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
            http_response_code(200);
            
            $erantzuna->arrakasta = true;
            
        } else {
            
            // Bezeroari eskaera ez duela ondo egin jakinaraziko diogu 400 egoera itzuliz.
            // http://stackoverflow.com/questions/5077871/what-is-the-proper-http-response-code-for-request-without-mandatory-fields
            http_response_code(400);
            
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = "Ez da datu-basean gorde, datuak falta baitira.";
            
        }
        
        echo json_encode($erantzuna);
        
        exit();
        
    }
    
    $sql = "SELECT izena
            FROM etiketak
            WHERE izena LIKE '$zatia%' AND fk_hizkuntza = " . $h_id;
    
    if ($dbo->query($sql)) {
    
        $erantzuna->etiketak = array();
        
        while ($emaitza = $dbo->emaitza()) {
            
            $erantzuna->etiketak[] = $emaitza["izena"];
            
        }
        
        // Bezeroari eskaera ondo joan dela jakinarazi.
        http_response_code(200);
        
        $erantzuna->arrakasta = true;
        
    } else {
        
        // Zerbitzarian errore bat eman dela jakinaraziko diogu bezeroari.
        http_response_code(500);
        
        $erantzuna->arrakasta = false;
        $erantzuna->mezua = "Errore bat gertatu da datu-basetik etiketak eskuratzean.";
        
    }
    
    echo json_encode($erantzuna);
    
    exit();

?>