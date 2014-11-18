<?php

// Azpitituluak sortzeko 2. script-a ) (2/2)

/*
3) txt fitxategia eskuz zuzendu eta _zuzenduta.txt sortu behar da, alineatu ahal izateko:
    perl wav+txt_zuzenduta+denbora2wrd.pl proba.wav proba_zuzenduta.txt proba.wrd 0

Honekin wrd fitxategia sortzen da, wav fitxategian esaten den hitz bakoitza eta hauetako bakoitza noiz hasi eta zenbat irauten duen daukana.

4) Subtituluak sortzeko, lehenik eta behin hitz bakoitza zein hizlarik esaten duen adierazi behar da. Hasierako probak egiteko, suposatuko dugu beti hizlari bakarrak hitz egiten duela. wrd fitxategiari hitz bakoitzari hizlari informazioa jartzeko:
    cat proba.wrd | grep -v "<UNK>" | sed s'/^\([^ ]* [^ ]* \)\([^ ]*\)/\1EZEZAGUNA \2/' > proba_hizlariekin.wrd

Orain bai, srt subitulu fitxategia sor daiteke:
    perl wrd_hizlariekin2srt.pl proba_hizlariekin.wrd proba_hizlariak.txt proba_hizlariekin.wrd.srt
*/
 

 
define("PATH_EZAGUTZA_WRAPPER", "http://iker.ikuslang.ametza.com/wrapper/ezagutza_wrapper.php");
define("URL_BASE", "http://iker.ikuslang.ametza.com/");
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

// deitu wrapper-ari, pasa fitxategiaren url-a eta zein pausotan gauden, bueltan erantzun bat itzuliko du
$testua = file_get_contents(PATH_EZAGUTZA_WRAPPER."?file_url=$".urlencode($fitxategia)."&txt_url=&pausoa=2");

$erantzuna = array();

$erantzuna['egoera'] = 'ondo';
$erantzuna['azpititulua_url'] = URL_BASE."azpitituluak/janzkera_legoaia_bat_da_haritz.srt";

echo json_encode($erantzuna);

?>