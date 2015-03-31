<?php
    
    $url_base = URL_BASE_ADMIN . "ikus-entzunezkoak/";
    
    define("AZPITITULUEN_PATH", "azpitituluak/");
    define("BIDEOEN_PATH", "bideoak/");
    define("AUDIOEN_PATH", "audioak/");
    
    $menu_aktibo = "ikus-entzunezkoak";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
    
    $hurrengoa = $url->hurrengoa();
    
    // Hauek dira sarrera bezala onartuko ditugun audio eta bideo-formatuak.
    // Kontutan izan gero dagokion formatuetara bihurtu beharko ditugula.
    $bideo_formatuak = array("mpg", "mpeg", "mp4", "webm", "avi");
    $audio_formatuak = array("mp3", "ogg");
    
        
    $sql = "SELECT id, mota, bideo_path, bideo_jatorrizkoa, bideo_mp4, bideo_webm, audio_path, audio_jatorrizkoa, audio_mp3, audio_ogg
            FROM ikus_entzunezkoak
            WHERE id = $edit_id";
   
    $dbo->query($sql) or die($dbo->ShowError());
    
    $ikus_entzunezkoa = new stdClass();
    
    if ($dbo->emaitza_kopurua() == 1) {
        
        $row = $dbo->emaitza();
        
        $ikus_entzunezkoa->id = $row["id"];
        $ikus_entzunezkoa->mota = $row["mota"];
        $ikus_entzunezkoa->bideo_path = $row["bideo_path"];
        $ikus_entzunezkoa->bideo_jatorrizkoa = $row["bideo_jatorrizkoa"];
        $ikus_entzunezkoa->bideo_mp4 = $row["bideo_mp4"];
        $ikus_entzunezkoa->bideo_webm = $row["bideo_webm"];
        $ikus_entzunezkoa->audio_path = $row["audio_path"];
        $ikus_entzunezkoa->audio_jatorrizkoa = $row["audio_jatorrizkoa"];
        $ikus_entzunezkoa->audio_mp3 = $row["audio_mp3"];
        $ikus_entzunezkoa->audio_ogg = $row["audio_ogg"];
        
        $ikus_entzunezkoa->hizkuntzak = array();
        
        foreach (hizkuntza_idak() as $h_id) {
            
            $sql = "SELECT izenburua, path_azpitituluak, azpitituluak
                    FROM ikus_entzunezkoak_hizkuntzak
                    WHERE fk_elem = $edit_id
                    AND fk_hizkuntza = $h_id";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            $rowHizk = $dbo->emaitza();
            
            $ikus_entzunezkoa->hizkuntzak[$h_id] = new stdClass();
            
            $ikus_entzunezkoa->hizkuntzak[$h_id]->izenburua = $rowHizk["izenburua"];
            $ikus_entzunezkoa->hizkuntzak[$h_id]->path_azpitituluak = $rowHizk["path_azpitituluak"];
            $ikus_entzunezkoa->hizkuntzak[$h_id]->azpitituluak = $rowHizk["azpitituluak"];
        }
        
        // begiratu lanean ari den
        $sql = "SELECT transkribapena, azpitituluak, noiz, egoera
                FROM ikus_entzunezkoak_azpitituluak
                WHERE fk_elem=".$edit_id." 
                ORDER BY noiz DESC";
      
        $dbo->query($sql) or die($dbo->ShowError());
        $rowAzpi = $dbo->emaitza();
        if($rowAzpi["egoera"] == "amaituta"){
            $ikus_entzunezkoa->ezagutzailea_egoera = $rowAzpi["egoera"];
           
            
        
        }elseif($rowAzpi["egoera"] == "lanean"){
            $ikus_entzunezkoa->ezagutzailea_egoera = $rowAzpi["egoera"];
        }else{
            $ikus_entzunezkoa->ezagutzailea_egoera = 'hutsik';
        }   
        
        
        // begiratu badagoen aurretik zerbait sortuta
        $sql = "SELECT transkribapena, azpitituluak, noiz, egoera
                FROM ikus_entzunezkoak_azpitituluak
                WHERE fk_elem=".$edit_id."
                AND egoera='amaituta'
                ORDER BY noiz DESC";
        $dbo->query($sql) or die($dbo->ShowError());
        $rowAzpi = $dbo->emaitza();
        if($rowAzpi["egoera"] == "amaituta"){
            $ikus_entzunezkoa->transkribapena = $rowAzpi["transkribapena"];
            $ikus_entzunezkoa->azpitituluak_ezagutzailea = $rowAzpi["azpitituluak"];
            $ikus_entzunezkoa->ezagutzailea_noiz = $rowAzpi["noiz"];
            
        }
       
       
        
    }
      
    $content = "inc/bistak/ikus_entzunezkoak/azpitituluak.php";
    
    
?>