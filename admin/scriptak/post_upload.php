<?php

// Azpitituluak sortzeko 1. script-a ) (1/2)
// hemen wav fitxategia lortu eta rec, txt fitxategiak sortzen dira.


 // Hauek dira sarrera bezala onartuko ditugun audio eta bideo-formatuak.
// Kontutan izan gero dagokion formatuetara bihurtu beharko ditugula.
$bideo_formatuak = array("mpg", "mpeg", "mp4", "webm", "avi");
$audio_formatuak = array("mp3", "ogg"); 
 
// jaso fitxategia
$fitxategia = $_GET['fitxategia'];
 
// Konbertsioa
// Fitxategien luzapena erabiliko dut fitxategi-mota identifikatzeko.
$luzapena = pathinfo($fitxategia, PATHINFO_EXTENSION);


// Fitxategia bideo edo audioa den jakin behar dugu.
if (in_array($luzapena, $bideo_formatuak)) {
    
    $mota = "bideoa";
    
    // GOGORATU: Jatorrizko bideoa zerbitzarira igo ondoren mp4 eta webm formatuetara bihurtu behar da
    // eta bideoen izenak dagokien aldagaietan gorde.
    $bideo_jatorrizkoa = fitxategia_igo("ikus_entzunezkoa_jatorrizkoa", "../" . BIDEOEN_PATH);
    $bideo_mp4 = "";
    $bideo_webm = "";
    
   
    
} else if (in_array($luzapena, $audio_formatuak)) {
    
    $mota = "audioa";
    
    // GOGORATU: Jatorrizko audioa zerbitzarira igo ondoren mp3 eta ogg formatuetara bihurtu behar da
    // eta audioen izenak dagokien aldagaietan gorde.
    $audio_jatorrizkoa = fitxategia_igo("ikus_entzunezkoa_jatorrizkoa", "../" . AUDIOEN_PATH);
    $audio_mp3 = "";
    $audio_ogg = "";
    
   
    
} else {
    
    // Gehitutako fitxategia ez da onartutako bideo edo audio fitxategi-mota bat.
    // Erabiltzaileari jakinarazi behar zaio.
    
    $mezua = "Gehitutako fitxategia ez da onartutako bideo edo audio fitxategi-mota bat.";
    
    // Berriz ere orri berdinera berbideratu.
    header("Location: " . $url_base . $url_param);
    exit();
    
}

?>