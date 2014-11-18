<?php
// AVISO: Este fichero se usa tanto en la "admin" (admin/erabiltzailea_egiaztatu.php) como en la "web" (inc/funtzioak/orokorrak.fun.php)

function hizkuntza_guztien_datuak (){
	return (get_query ("SELECT * FROM hizkuntzak WHERE orden > 0 ORDER BY orden ASC"));
}

function testu_formatua_sql ($testua){
	if (get_magic_quotes_gpc ()) {
		$testua = stripslashes ($testua);
	}

	return (mysql_real_escape_string ($testua));
}

function testu_formatua_input ($testua){
	$testua = str_replace ("\"", "&quot;", $testua);

	return ($testua);
}

function prestatu_balioa($balioa){
		return "'".testu_formatua_sql($balioa)."'";
}

function fitxategia_igo ($eremu, $path){
	$fitxategia = "";

	if ($_FILES[$eremu]['name'] && $_FILES[$eremu]['size'] > 0) {
		$fitxategia = $_FILES[$eremu]['name'];

		if (preg_match ("@^(.*)(\..*)$@", $fitxategia, $datuak)){
			$izena = sanitize_title_with_dashes ($datuak[1]);
			$luzapena = $datuak[2];
		}
		else{
			$izena = sanitize_title_with_dashes ($fitxategia);
			$luzapena = "";
		}

		$fitxategia = $izena . $luzapena;
		$target_path = $path . $fitxategia;

		if (is_file ($target_path)){
			$i = 1;
			do{
				$i++;
				$fitxategia = $izena . "-" . $i . $luzapena;
				$target_path = $path . $fitxategia;
			} while (is_file ($target_path));
		}

		move_uploaded_file ($_FILES[$eremu]['tmp_name'], $target_path) or die ("Errorea fitxategia igotzerakoan.");

		$oldumask = umask (0);
		chmod ($target_path, 0666);
		umask ($oldumask);
	}

	return ($fitxategia);
}

function fitxategia_kopiatu ($fitxategia, $path_orig, $path_dest){
	$fitx = "";

	if (is_file ($path_orig . $fitxategia)){
		$fitx = $fitxategia;

		if (preg_match ("@^(.*)(\..*)$@", $fitx, $datuak)){
			$izena = $datuak[1];
			$luzapena = $datuak[2];
		}
		else{
			$izena = $fitx;
			$luzapena = "";
		}

		$fitx = $izena . $luzapena;
		$target_path = $path_dest . $fitx;

		if (is_file ($target_path)){
			$i = 1;
			do{
				$i++;
				$fitx = $izena . "-" . $i . $luzapena;
				$target_path = $path_dest . $fitx;
			} while (is_file ($target_path));
		}

		@copy ($path_orig . $fitxategia, $target_path);

		$oldumask = umask (0);
		@chmod ($target_path, 0666);
		umask ($oldumask);
	}

	return ($fitx);
}

function irudien_zabalera_mugatu ($irudia, $zabalera_maximoa){
	if (is_file ($irudia)){
		$irudi_datuak = getimagesize ($irudia);

		if ($irudi_datuak[0] > $zabalera_maximoa){
			return (" width='$zabalera_maximoa'");
		}
	}

	return ("");
}

function irudien_altuera_mugatu ($irudia, $altuera_maximoa){
	if (is_file ($irudia)){
		$irudi_datuak = getimagesize ($irudia);

		if ($irudi_datuak[1] > $altuera_maximoa){
			return (" height='$altuera_maximoa'");
		}
	}

	return ("");
}

function miniatura ($path, $irudia, $zabalera=200){
	$mini = $img = $thumb = "";

	if (is_file ($path . $irudia)){
		$datuak = getimagesize ($path . $irudia);

		if ($datuak[2] == 1) $img = @imagecreatefromgif ($path . $irudia);
		elseif ($datuak[2] == 2) $img = @imagecreatefromjpeg ($path . $irudia);
		elseif ($datuak[2] == 3) $img = @imagecreatefrompng ($path . $irudia);

		if ($img) {
			if ($datuak[0] > $zabalera){
				$altuera = ceil (($datuak[1] * $zabalera) / $datuak[0]);
			}
			else{
				$altuera = $datuak[1];
				$zabalera = $datuak[0];
			}
			$thumb = @imagecreatetruecolor ($zabalera, $altuera);

			@imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $zabalera, $altuera, $datuak[0], $datuak[1]);

			if ($thumb){
				ob_start();
				if ($datuak[2] == 1) imagegif ($thumb);
				elseif ($datuak[2] == 2) imagejpeg ($thumb);
				elseif ($datuak[2] == 3) imagepng ($thumb);
				$minithumb = ob_get_clean();
				imagedestroy ($thumb);

				$fichero = "mini_" . $irudia;
				if (preg_match ("@^(.*)(\..*)$@", $fichero, $datuak)){
					$nombre = sanitize_title_with_dashes ($datuak[1]);
					$extension = $datuak[2];
				}
				else{
					$nombre = sanitize_title_with_dashes ($fichero);
					$extension = "";
				}

				$fichero = $nombre . $extension;
				$target_path = $path . $fichero;

				if (is_file ($target_path)){
					$i = 1;
					do{
						$i++;
						$fichero = $nombre . "-" . $i . $extension;
						$target_path = $path . $fichero;
					} while (is_file ($target_path));
				}

				$fp = fopen ($target_path, "w") or die ("Errorea miniatura sortzerakoan (1)");
				fwrite ($fp, $minithumb) or die ("Errorea miniatura sortzerakoan (2)");
				fclose ($fp);

				$oldumask = umask (0);
				chmod ($target_path, 0666);
				umask ($oldumask);

				$mini = $fichero;
			}
		}
	}

	return ($mini);
}

function get_konfigurazioa ($gakoa){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT balioa FROM konfigurazioa WHERE gakoa='$gakoa'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();

		return ($row["balioa"]);
	}
	else
		return ("");
}

function is_dbtable_id ($taula, $id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT id FROM $taula WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1)
		return (true);
	else
		return (false);
}

function get_dbtable_field_by_id ($taula, $eremua, $id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT $eremua FROM $taula WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();

		return ($row[$eremua]);
	}
	else
		return ("");
}

function get_dbtable_row_by_id ($taula, $id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT * FROM $taula WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1)
		return ($dbo->emaitza ());
	else
		return ("");
}

function get_dbtable_all_id ($taula, $orden=""){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$idak = array ();

	$sql = "SELECT id FROM $taula";

	if (trim ($orden) != "")
		$sql .= " ORDER BY $orden";

	$dbo->query ($sql) or die ($dbo->ShowError ());
	while ($row = $dbo->emaitza ())
		array_push ($idak, $row["id"]);

	return ($idak);
}

function get_dbtable_field_by_id_hizkuntza ($taula, $eremua, $id, $hizkuntza_id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT $eremua FROM ${taula}_hizkuntzak WHERE fk_elem='" . (int) $id . "' AND fk_hizkuntza='" . (int) $hizkuntza_id . "'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();

		return ($row[$eremua]);
	}
	else
		return ("");
}

function db_taula_azken_id ($taula){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT id FROM $taula ORDER BY id DESC LIMIT 1";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();

		return ($row["id"]);
	}
	else
		return (0);
}

function db_taula_optimizatu ($taula){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "OPTIMIZE TABLE $taula";
	$dbo->query ($sql) or die ($dbo->ShowError ());
}

function querystring_param_kendu (){
	$querystring = explode ("&", $_SERVER["QUERY_STRING"]);

	if (func_num_args () > 0 && count ($querystring) > 0){
		$kentzeko = func_get_args ();

		$kop = count ($querystring);
		for ($i=0; $i < $kop; $i++){
			$param = explode ("=", $querystring[$i]);

			if (in_array ($param[0], $kentzeko))
				unset ($querystring[$i]);
		}
	}

	return ((count ($querystring) > 0 ? '&' : '') . implode ("&", $querystring));
}

function get_query ($sql){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$elementuak = array ();

	$dbo->query ($sql) or die ($dbo->ShowError ());
	while ($row = $dbo->emaitza ())
		array_push ($elementuak, $row);

	return ($elementuak);
}

function orrikapen_datuak ($sql, $orri, $erregistro_kopurua=30){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$dbo->query ($sql) or die ($dbo->ShowError ());
	$datuak["numeroRegistros"] = $dbo->emaitza_kopurua ();

	$datuak["tamPag"] = $erregistro_kopurua; //tamano de la pagina
	$datuak["indices"] = 10; //numero maximo de indices

	//pagina actual si no esta definida y limites
	if (trim ($orri) == ""){
		$datuak["pagina"] = 1;
		$datuak["inicio"] = 1;
		$datuak["final"] = $datuak["indices"];
	}
	else{
		$datuak["pagina"] = $orri;
	}

	//calculo del numero de paginas
	$datuak["numPags"] = ceil ($datuak["numeroRegistros"] / $datuak["tamPag"]);
	if (!isset ($datuak["pagina"])){
		$datuak["pagina"] = 1;
		$datuak["inicio"] = 1;
		$datuak["final"] = $datuak["indices"];
	}
	else{
		$datuak["seccionActual"] = intval (($datuak["pagina"] - 1) / $datuak["indices"]);
		$datuak["inicio"] = ($datuak["seccionActual"] * $datuak["indices"]) + 1;

		if ($datuak["pagina"] < $datuak["numPags"]){
			$datuak["final"] = $datuak["inicio"] + $datuak["indices"]-1;
		}
		else{
			$datuak["final"] = $datuak["numPags"];
		}

		if ($datuak["final"] > $datuak["numPags"]){
			$datuak["final"] = $datuak["numPags"];
		}

		if ($datuak["pagina"] > $datuak["numPags"]){
			$datuak["pagina"] = $datuak["numPags"];
		}

		//calculo del limite inferior
		$datuak["limitInf"] = ($datuak["pagina"] - 1) * $datuak["tamPag"];
		if ($datuak["limitInf"] < 0) {
			$datuak["limitInf"] = 0;
		}
	}

	return ($datuak);
}

function get_date_data ($date){
	$zatiak = explode (" ", $date);
	
	return ($zatiak[0]);
}

function get_date_ordua ($date){
	$zatiak = explode (" ", $date);
	
	return ($zatiak[1]);
}

function korreoa_bidali ($to, $asunto, $gorputza, $html=false){
	$headers  = 'MIME-Version: 1.0' . "\n";
	if ($html)
		$headers .= 'Content-Type: text/html; charset = "iso-8859-1"' . "\n";
	else
		$headers .= 'Content-Type: text/plain; charset = "iso-8859-1"' . "\n";
	$headers .= 'Content-Transfer-Encoding: 8bit' . "\n";
	$headers .= 'From: IKA euskaltegiak <ika@ikanet.net>';

	return (mail ($to, $asunto, $gorputza, $headers));
}

function pr($aldagaia){
	echo '<pre>';
	print_r($aldagaia);
	echo '</pre>';
	
}


?>
