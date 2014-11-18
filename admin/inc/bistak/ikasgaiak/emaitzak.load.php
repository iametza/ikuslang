<?php

    // kargatu modeloa
	require('../inc/modeloak/ikasgela_modeloa.php');
	$IkasgelaModeloa = new IkasgelaModeloa();

    $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
    
    if($edit_id == 0){
        header("Location:" . $url_base );
        exit;
        
    }
    
    
    $ikasgaia = $IkasgaiaModeloa->get($edit_id);
    
    if(!$ikasgaia){
        header("Location: " . $url_base);
        exit;
    }
   
    
   
    $ikasgela = $IkasgelaModeloa->get( $ikasgaia->fk_ikasgela );
    //pr($ikasgela);
    
    
    // emaitzak txukundu
    $emaitzak_ikasleka = array();
    if(!empty($ikasgaia->emaitzak)){
        foreach($ikasgaia->emaitzak as $emaitza){
            $emaitzak_ikasleka[$emaitza['fk_ikaslea']][$emaitza['fk_ariketa']][] = $emaitza;
        }
    }
    
     
    
    $content = "inc/bistak/ikasgaiak/emaitzak.php";
    


?>