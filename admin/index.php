<?php
	require("../inc/db.inc.php");
    require("../inc/konfig.inc.php");
	require("../inc/libs/url.lib.php");
	require("../inc/libs/dbo.lib.php");
	require("../inc/libs/hizkuntzak.lib.php");
	require("../inc/funtzioak/globalak.fun.php");
	require("inc/libs/erabiltzailea.lib.php");
	require_once("inc/libs/etiketak.lib.php");
	require("inc/funtzioak/funtzioak.php");

	// Si hay problemas con los caracteres puede que sea por esto...
	setlocale(LC_ALL, 'es_ES');
	header("Content-type: text/html; charset=utf-8");
    
	// DOCUMENT_ROOT aldatu: trailin slash jarri
    $_SERVER["DOCUMENT_ROOT"] = rtrim($_SERVER["DOCUMENT_ROOT"], "/")."/";
	
	// Aldagai globalak
	$dbo = new DBO(DB_SERV, DB_USER, DB_PASS, DB_NAME);
	$url = new URL();
	$erabiltzailea = new erabiltzailea();
	$menu_aktibo = "";

	$hizkuntza = array("id" => 1, "izena" => "Euskara", "nice_name" => "euskara", "data_formatua" => "U-H-E", "gako" => "eu");
	$hto = new hizkuntzak($hizkuntza);
	
	if ($erabiltzailea->logged()) {
		require("inc/bistak/main.load.php");
	} else {
		require("inc/bistak/login/login.load.php");
	}
?>
