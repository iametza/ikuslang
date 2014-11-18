<?php
	require ("inc/db.inc.php");
    require ("inc/konfig.inc.php");
	require ("inc/libs/url.lib.php");
	require ("inc/libs/dbo.lib.php");
	require ("inc/libs/erabiltzailea.lib.php");
	require ("inc/libs/hizkuntzak.lib.php");
	require ("inc/funtzioak/orokorrak.fun.php");
    
	@session_start ();

	// Si hay problemas con los caracteres puede que sea por esto...
	header("Content-type: text/html; charset=utf-8");
    
	// Variables globales
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);
	$url = new URL ();
	$erabiltzailea = new erabiltzailea ();
	$menu_aktibo = $head_file_inc = $header_img = $meta_title = "";
    
	$hizkuntza = array ("id" => 1, "izena" => "Euskara", "nice_name" => "euskara", "data_formatua" => "U-H-E", "gako" => "eu");
	$hto = new hizkuntzak ($hizkuntza);
	
    $id_ariketa = 0;
    
    // DOCUMENT_ROOT aldatu: trailin slash jarri
    $_SERVER["DOCUMENT_ROOT"] = rtrim($_SERVER["DOCUMENT_ROOT"], "/")."/";
    
	require("inc/bistak/main.load.php");
    
?>
