<?php

function dokumentuak_ezabatu ($atala, $elem_id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT * FROM dokumentuak WHERE atala='$atala' AND fk_elem='$elem_id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	while ($row = $dbo->emaitza ()){
		dokumentua_ezabatu ($row["id"]);
	}
}

function dokumentua_ezabatu ($id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	// Borramos el dokumento
	$path = fitxategia_path ("dokumentuak", "path", $id);
	fitxategia_ezabatu ("dokumentuak", "dokumentua", $id, "../" . $path);

	//Borramos los datos de los diferentes idiomas
	$sql = "DELETE FROM dokumentuak_hizkuntzak WHERE fk_elem='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());

	// Borramos el elemento
	$sql = "DELETE FROM dokumentuak WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
}

function dokumentuak_atala_izena ($atala){
	$izena = "";

	switch ($atala){
		case "testuak":
			 $izena = "Testuak";
			 break;
		case "ariketa_ereduak_elementuak":
			$izena = "Ariketak &gt; Mailak";
			break;
	}

	return ($izena);
}

function dokumentuak_atala_path ($atala){
	$path = "";

	switch ($atala){
		case "testuak":
			$path = "fitxategiak/dokumentuak/testuak/";
			break;
		case "ariketa_ereduak_elementuak":
			$path = "fitxategiak/dokumentuak/ariketa_ereduak/";
			break;
	}

	return ($path);
}

function dokumentuak_goiburua ($id, $atala){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$goiburua = "";

	switch ($atala){
		case "testuak":
			$goiburua = get_dbtable_field_by_id ("testuak", "izena", $id);
			break;
		case "ariketa_ereduak_elementuak":
			$goiburua = elementuaren_testua ("ariketa_ereduak_elementuak", "izenburua", $id);
			break;
	}

	return ($goiburua);
}

?>
