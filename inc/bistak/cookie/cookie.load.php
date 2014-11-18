<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");

	// Preparamos los enlaces de los idiomas al apartado
	$hto->add_atala ("kuki_zer_dira");
	
	$meta_title .= " - " . $hto->motz ("kuki_zer_dira");

	$content = "inc/bistak/cookie/cookie.php";
?>
