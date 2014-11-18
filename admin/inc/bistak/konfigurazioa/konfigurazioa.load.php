<?php

	$url_base = URL_BASE_ADMIN . "konfigurazioa/";

	$menu_aktibo = "konfigurazioa";
	
	$mezua = "";

	// Inserciones o modificaciones
	if (isset ($_POST["gorde"])){
		// Formularioko datuak eskuratuko ditugu.
		$email_harremanetarako = testu_formatua_sql ($_POST["email_harremanetarako"]);
		$email_abisuak = testu_formatua_sql ($_POST["email_abisuak"]);

		// Guardamos las configuraciones
		put_konfigurazioa ("email_harremanetarako", $email_harremanetarako);
		put_konfigurazioa ("email_abisuak", $email_abisuak);

		$mezua = "Konfigurazio datuak egokiro gorde dira.";
	}

	// Recogemos los valores
	$email_harremanetarako = get_konfigurazioa ("email_harremanetarako");
	$email_abisuak = get_konfigurazioa ("email_abisuak");
	
	$content = "inc/bistak/konfigurazioa/konfigurazioa.php";

?>
