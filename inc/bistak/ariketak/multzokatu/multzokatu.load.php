<?php
    
    if ($id_ariketa > 0) {
        
        $sql = "SELECT A.id, B.izena, B.azalpena
                FROM ariketak AS A
                JOIN ariketak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE A.id = " . $id_ariketa . " AND B.fk_hizkuntza = " . $hizkuntza["id"];
        
        $dbo->query($sql) or die($dbo->ShowError);
        
        $multzokatu = new stdClass();
        
        if ($row = $dbo->emaitza()) {
            
            $multzokatu->id = $row["id"];
            $multzokatu->izena = $row["izena"];
            $multzokatu->azalpena = $row["azalpena"];
            
            $sql = "SELECT A.id, B.izena
                    FROM multzokatu_taldeak AS A
                    JOIN multzokatu_taldeak_hizkuntzak AS B
                    ON A.id = B.fk_elem
                    WHERE A.fk_ariketa = " . $id_ariketa . " AND B.fk_hizkuntza = " . $hizkuntza["id"];
            
            $dbo->query($sql) or die($dbo->ShowError);
            
            $multzokatu->taldeak = array();
            $multzokatu->elementuak = array();
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_taldea = new stdClass();
                
                $tmp_taldea->id = $row["id"];
                $tmp_taldea->izena = $row["izena"];
                
                $emaitza = get_query("SELECT A.id, A.fk_taldea, B.izena
                                      FROM multzokatu_elementuak AS A
                                      JOIN multzokatu_elementuak_hizkuntzak AS B
                                      ON A.id = B.fk_elem
                                      WHERE A.fk_taldea = " . $row["id"] . " AND B.fk_hizkuntza = " . $hizkuntza["id"]);
            
                
                foreach ($emaitza as $e) {
                    $tmp_elementua = new stdClass();
                    
                    $tmp_elementua->id = $e["id"];
                    $tmp_elementua->id_taldea = $e["fk_taldea"];
                    $tmp_elementua->izena = $e["izena"];
                    
                    array_push($multzokatu->elementuak, $tmp_elementua);
                }
                
                array_push($multzokatu->taldeak, $tmp_taldea);
            }
            
            $sql = "SELECT C.izenburua, B.path_dokumentua, B.dokumentua
                    FROM ariketa_dokumentua AS A
                    INNER JOIN dokumentuak AS B
                    ON A.fk_dokumentua = B.id
                    INNER JOIN dokumentuak_hizkuntzak AS C
                    ON B.id = C.fk_elem
                    WHERE A.fk_ariketa = $id_ariketa AND C.fk_hizkuntza = " . $hizkuntza["id"];
            
            $multzokatu->dokumentuak = array();
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            while ($row = $dbo->emaitza()) {
                
                $tmp_dokumentua = new stdClass();
                
                $tmp_dokumentua->izenburua = $row["izenburua"];
                $tmp_dokumentua->path_dokumentua = $row["path_dokumentua"];
                $tmp_dokumentua->dokumentua = $row["dokumentua"];
                
                $multzokatu->dokumentuak[] = $tmp_dokumentua;
            }
        }
        
        $content = "inc/bistak/ariketak/multzokatu/multzokatu.php";
        
    }
?>