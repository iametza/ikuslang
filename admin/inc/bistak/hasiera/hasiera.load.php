<?php
    
    $url_base = URL_BASE_ADMIN . "hasiera/";
    
    $menu_aktibo = "hasiera";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    // irakaslea bada, bere informazioa lortu
    if($erabiltzailea->get_rola() == 'irakaslea'){
        
        // ikasgleak
        $sql = "SELECT *
                FROM ikasgelak
                WHERE fk_irakaslea = ".$erabiltzailea->get_fk_irakaslea();   
        
        $ikasgelak = get_query($sql);
        
        // bere ikasgeletan dauden ikasleak
        
        // ariketak
         $sql = "SELECT A.id, B.izena, A.egoera, A.fk_ariketa_mota
            FROM ariketak AS A
            INNER JOIN ariketak_hizkuntzak AS B
            ON A.id = B.fk_elem
            INNER JOIN erregistroa AS E
            ON B.fk_elem = E.fk_elementua AND E.elementu_mota = 'ariketa'
            WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
            AND E.fk_sortze_erabiltzailea = ".$erabiltzailea->get_id()."
            ORDER BY B.izena ASC";
        $ariketak = get_query($sql);
    
        
    }
  
    
    $sql = "SELECT A.id, A.hasiera_data, A.bukaera_data, A.fk_ikasgela, B.izenburua
            FROM ikasgaiak AS A
            INNER JOIN ikasgaiak_hizkuntzak AS B
            ON A.id = B.fk_elem
            WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
            ORDER BY B.izenburua ASC";
    
    $orrikapena = orrikapen_datuak ($sql, $p);
    $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
    
    $elementuak = get_query($sql);
    
    $content = "inc/bistak/hasiera/hasiera.php";
        
    
?>