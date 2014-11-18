<?php

$id_ariketa = (int) $url->hurrengoa();

$id_ikasgaia = isset($_GET["id_ikasgaia"]) ? (int) $_GET["id_ikasgaia"] : 0;

if ($id_ariketa > 0 && $id_ikasgaia > 0) {
    
    $sql = "SELECT B.nice_name
            FROM ariketak AS A
            INNER JOIN ariketa_motak_hizkuntzak AS B
            ON A.fk_ariketa_mota = B.fk_elem
            WHERE A.id = $id_ariketa AND fk_hizkuntza = " . $hizkuntza["id"];
    
    $dbo->query($sql) or die($dbo->ShowError());
    
    $emaitza = $dbo->emaitza();
    
    require("inc/bistak/ariketak/" . $emaitza["nice_name"] . "/" . $emaitza["nice_name"] . ".load.php");
    
}

?>