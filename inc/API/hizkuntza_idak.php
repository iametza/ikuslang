<?php

function hizkuntza_idak (){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$hizkuntzak = array ();

	$sql = "SELECT id FROM hizkuntzak ORDER BY orden ASC";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	while ($row = $dbo->emaitza ())
		array_push ($hizkuntzak, $row["id"]);

	return ($hizkuntzak);
}

?>