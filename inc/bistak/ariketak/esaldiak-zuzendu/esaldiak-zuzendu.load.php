<?php
    
    if ($id_ariketa > 0) {
        
        $esaldiak_zuzendu = new stdClass();
        
        $sql = "SELECT izena, azalpena
                FROM ariketak_hizkuntzak
                WHERE fk_elem = $id_ariketa AND fk_hizkuntza = " . $hizkuntza["id"];
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($row = $dbo->emaitza()) {
            
            $esaldiak_zuzendu->izena = $row["izena"];
            $esaldiak_zuzendu->azalpena = $row["azalpena"];
            
            $tmp_esaldien_idak = array();
            $tmp_esaldiak = array();
            $tmp_ordenak = array();
            
            $sql = "SELECT id
                    FROM esaldiak_zuzendu_esaldiak
                    WHERE fk_ariketa = $id_ariketa";
            
            $emaitza = get_query($sql);
            
            foreach($emaitza as $errenkada) {
                
                $tmp_esaldien_idak[] = $errenkada["id"];
                
                $sql = "SELECT testua, ordenak
                        FROM esaldiak_zuzendu_esaldiak_hizkuntzak
                        WHERE fk_elem = " . $errenkada['id'] . " AND fk_hizkuntza = " . $hizkuntza["id"];
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                while ($esaldia = $dbo->emaitza()) {
                    
                    $tmp_esaldiak[] = explode(" " , $esaldia["testua"]);
                    
                    $tmp_ordenak[] = json_decode($esaldia["ordenak"]);
                    
                }
                
            }
            
            $esaldiak_zuzendu->esaldien_idak = json_encode($tmp_esaldien_idak);
            $esaldiak_zuzendu->esaldiak = json_encode($tmp_esaldiak);
            
            $esaldiak_zuzendu->ordenak = json_encode($tmp_ordenak);
            
            $sql = "SELECT C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $id_ariketa AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $esaldiak_zuzendu->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $esaldiak_zuzendu->dokumentuak[] = $tmp_dokumentua;
            }
        }
        
        $content = "inc/bistak/ariketak/esaldiak-zuzendu/esaldiak-zuzendu.php";
        
    }
?>