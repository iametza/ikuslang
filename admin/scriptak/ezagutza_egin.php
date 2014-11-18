<?php

// Azpitituluak sortzeko 1. script-a ) (1/2)
// hemen wav fitxategia lortu eta rec, txt fitxategiak sortzen dira.

/*
 *
 *
 *1) Hasteko, ezagutza egiteko audioa behar da, formatu eta ezaugarri akustiko egokiekin. Horretarako:
    sudo apt-get install aavconv (agian instalatuta daukazue)
    perl edukia2wav.pl proba.mp3 proba.wav



2) wav fitxategi horrekin, ezagutza egiteko:
    perl wav+denbora2rec+txt.pl proba.wav proba.rec proba.txt 0
 *
 
*/ 
 
define("PATH_EZAGUTZA_WRAPPER", "http://iker.ikuslang.ametza.com/wrapper/ezagutza_wrapper.php");
 // Hauek dira sarrera bezala onartuko ditugun audio eta bideo-formatuak.
// Kontutan izan gero dagokion formatuetara bihurtu beharko ditugula.
$bideo_formatuak = array("mpg", "mpeg", "mp4", "webm", "avi");
$audio_formatuak = array("mp3", "ogg"); 
 
// jaso fitxategia
$fitxategia = $_POST['fitxategia'];
 
 
// Konbertsioa
// Fitxategien luzapena erabiliko dut fitxategi-mota identifikatzeko.
$luzapena = pathinfo($fitxategia, PATHINFO_EXTENSION);


// Fitxategia bideo edo audioa den jakin behar dugu.
if (in_array($luzapena, $bideo_formatuak)) {
    
    $mota = "bideoa";
  
} else if (in_array($luzapena, $audio_formatuak)) {
    
    $mota = "audioa";
    
}

$url = PATH_EZAGUTZA_WRAPPER."?file_url=".urlencode($fitxategia)."&pausoa=1";
echo $url;

// deitu wrapper-ari, pasa fitxategiaren url-a eta zein pausotan gauden, bueltan testu bat itzuliko du
$testua = file_get_contents($url);

$erantzuna = array();

$erantzuna['egoera'] = 'ondo';
$erantzuna['testua'] = $testua;

echo json_encode($erantzuna);

?>