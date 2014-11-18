<?php
    
    $menu_aktibo = "nire-txokoa";
    
    $ariketak = new stdClass();
    
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
            WHERE B.fk_ikaslea = " . $erabiltzailea->get_id() . "
            AND F.egoera = 1
            AND G.fk_hizkuntza = " . $hizkuntza['id'] . " 
            AND I.fk_hizkuntza = " . $hizkuntza['id'] . "
            AND C.bukaera_data > NOW()
            ORDER BY C.bukaera_data DESC";
    
    $dbo->query($sql) or die($dbo->ShowError());
    
    $ariketak->egitekoak = array();
    
    while ($emaitza = $dbo->emaitza()) {
        
        $ariketak->egitekoak[] = array("id" => $emaitza["id"],
                                       "izena" => $emaitza["izena"],
                                       "bukaera_data" => $emaitza["bukaera_data"],
                                       "id_ikasgaia" => $emaitza["id_ikasgaia"],
                                       "ikasgaia" => $emaitza["ikasgaia"],
                                       "id_ariketa_mota" => $emaitza["id_ariketa_mota"],
                                       "ariketa_mota" => $emaitza["ariketa_mota"]);
        
    }
    
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
            WHERE B.fk_ikaslea = " . $erabiltzailea->get_id() . "
            AND F.egoera = 1
            AND G.fk_hizkuntza = " . $hizkuntza['id'] . " 
            AND I.fk_hizkuntza = " . $hizkuntza['id'] . "
            ORDER BY J.data DESC";
    
    $dbo->query($sql) or die($dbo->ShowError());
    
    $ariketak->egindakoak = array();
    
    while ($emaitza = $dbo->emaitza()) {
        
        $ariketak->egindakoak[] = array("id" => $emaitza["id"],
                                       "izena" => $emaitza["izena"],
                                       "bukaera_data" => $emaitza["bukaera_data"],
                                       "egindako_data" => $emaitza["egindako_data"],
                                       "id_ikasgaia" => $emaitza["id_ikasgaia"],
                                       "ikasgaia" => $emaitza["ikasgaia"],
                                       "id_ariketa_mota" => $emaitza["id_ariketa_mota"],
                                       "ariketa_mota" => $emaitza["ariketa_mota"]);
        
    }
    
    $content = "inc/bistak/nire_txokoa/nire_txokoa.php"

?>