<?php

require ("../../inc/db.inc.php");
require ("../../inc/konfig.inc.php");
require ("../../inc/libs/dbo.lib.php");
require ("../../inc/funtzioak/globalak.fun.php");

define("IKUSLANG_WEB_BIDEOAK", IKUSLANG_WEB . "bideoak/");
define("IKUSLANG_WEB_AUDIOAK", IKUSLANG_WEB . "audioak/");
define("MUGITZEKO_DIR", IKUSLANG_WEB . "mugitzeko/");



$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

// adminetik transkribapena bidaltzen da honekin azpitituluak osatzeko.
// hemen wav fitxategia lortu eta wav eta txt bat utziko dugu transkribapenetik direktorioan
// direktorio hori cron prozesu batek kontrolatzen du jakiteko zerbait duen egiteko.


$id = $_POST['id'];

$url = $_POST['url'];

// lortu fitxategiak

$sql = "SELECT * FROM ikus_entzunezkoak
        WHERE id=".$id;
        
$dbo->query($sql) or die($dbo->ShowError());
        $row = $dbo->emaitza();
if(empty($row)){
    echo 'Erregistrorik ez';
    return;
}


if($row["bideo_jatorrizkoa"] != ''){
    $fitxategia = IKUSLANG_WEB_BIDEOAK . $row["bideo_jatorrizkoa"] ;
    $fitxategia_wav = IKUSLANG_WEB_BIDEOAK . $row["bideo_jatorrizkoa"]. ".wav";
}
else{
    $fitxategia = IKUSLANG_WEB_AUDIOAK . $row["audio_jatorrizkoa"];
    $fitxategia_wav = IKUSLANG_WEB_AUDIOAK . $row["audio_jatorrizkoa"]. ".wav";
}   
$pathinfo = pathinfo($fitxategia);

$fitxategia_ext_gabe = $pathinfo["filename"];

if(!is_file($fitxategia)){
    $erantzuna = array('erantzuna' => 'Fitxategia ez da aurkitu: ' . $fitxategia);
    echo json_encode($erantzuna);
    exit;
    
}else{
   
    // begiratu wav-a dugun, ez badugu sortu
    if(!is_file ($fitxategia_wav)){
        // sortu avconv erabiliz
        $avconv_command = "/usr/local/bin/avconv -i ". $fitxategia ." -ar 16000 -ac 1 -y ".$fitxategia_wav;
        shell_exec( escapeshellcmd($avconv_command));
        
        // baieztatu fitxategia sortu dela bestela bukatu
        if(!is_file ($fitxategia_wav)){
            $erantzuna = array('erantzuna' => 'Ezin izan da wav fitxategia sortu: ' . $avconv_command);
            echo json_encode($erantzuna);
            exit;
        }
        
        
    }
    
    $pathinfo_wav = pathinfo($fitxategia_wav);
    // fitxategia MUGITZEKO_DIR direkotoriora mugitu wav, aurretik id-a jarri
    $fitxategia_wav = str_replace(" ", "%20", $fitxategia_wav);
    $fitxategi_berria = str_replace(" ", "%20", MUGITZEKO_DIR . $row["id"] . "-" . $pathinfo_wav["basename"]);
    if (!copy( $fitxategia_wav, $fitxategi_berria ) ) {
        $erantzuna = array('erantzuna' => 'Fitxategia  ezin izan da kopiatu: ' . $fitxategia_wav .' >> ' . $fitxategi_berria);
        echo json_encode($erantzuna);
        exit;
    }
    
    
    // markatu ikusentzunezkoa tratatzen ari dela 
    $sql = "INSERT INTO ikus_entzunezkoak_azpitituluak
                (fk_elem, transkribapena, azpitituluak, noiz, egoera)
        VALUES  (".$row["id"].", '', '', NOW(), 'lanean')
        ";
   echo $sql;
    $dbo->query($sql);
    
    
   $erantzuna = array('erantzuna' => 'Ongi');
    echo json_encode($erantzuna);
    exit;
    
}


