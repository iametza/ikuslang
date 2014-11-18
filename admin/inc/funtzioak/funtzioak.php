<?php
require ("sanitize.php");
require ("funtzioak_dokumentuak.php");

function orrikapen_indizeak ($datuak, $helb){
	global $url;
	global $hto;
?>
<div class="pagination">
<ul>
<?php
	if($datuak["pagina"] > 1)
		echo '<li><a href="' . $helb . '?p=' . ($datuak["pagina"]-1) . querystring_param_kendu ("ids", "p") . '">&laquo;</a></li>' . "\n";
		
	for ($i = $datuak["inicio"]; $i <= $datuak["final"]; $i++){
		if ($i == $datuak["pagina"]){
			echo '<li class="active"><a href="#">' . $i . '</a></li>' . "\n";
		}
		else{
			echo '<li><a href="' . $helb . '?p=' . $i . querystring_param_kendu ("ids", "p") . '">' . $i . '</a></li>' . "\n";
		}
	}
	
	if ($datuak["pagina"] < $datuak["numPags"])
		echo '<li><a href="' . $helb . '?p=' . ($datuak["pagina"]+1) .  querystring_param_kendu ("ids", "p") . '">&raquo;</a></li>' . "\n";
?>
</ul>
</div>
<?php
}

function fitxategia_ezabatu ($taula, $eremu, $id, $path){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT $eremu FROM $taula WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$rowIzen = $dbo->emaitza ();

		if (is_file("$path$rowIzen[$eremu]")){
			unlink ("$path$rowIzen[$eremu]");
		}
	}

	$sql = "UPDATE $taula SET $eremu='' WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
}

function fitxategia_ezabatu_hizkuntza($taula, $eremu, $fk_elem, $fk_hizkuntza, $path){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT $eremu FROM $taula WHERE fk_elem='$fk_elem' AND fk_hizkuntza='$fk_hizkuntza'";
	$dbo->query($sql) or die ($dbo->ShowError());
	if ($dbo->emaitza_kopurua () == 1){
		$rowIzen = $dbo->emaitza ();
		
		if (is_file("$path$rowIzen[$eremu]")){
			$tmp = unlink ("$path$rowIzen[$eremu]");
		}
	}

	$sql = "UPDATE $taula SET $eremu='' WHERE fk_elem='$fk_elem' AND fk_hizkuntza='$fk_hizkuntza'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
}

function fitxategia_path ($taula, $eremu, $id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$path = "";

	$sql = "SELECT $eremu FROM $taula WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$rowPath = $dbo->emaitza ();

		$path = $rowPath[$eremu];
	}

	return ($path);
}

function fitxategia_path_hizkuntza($taula, $eremu, $fk_elem, $id_hizkuntza){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$path = "";

	$sql = "SELECT $eremu FROM " . $taula . "_hizkuntzak WHERE fk_elem='$fk_elem' AND fk_hizkuntza='$id_hizkuntza'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$rowPath = $dbo->emaitza ();

		$path = $rowPath[$eremu];
	}

	return ($path);
}

function CKEditor_path_ed2db ($testua){
	return (str_replace ("/fitxategiak/ckfinder/", "###MAILA###", $testua));
}

function CKEditor_path_db2ed ($testua){
	return (str_replace ("###MAILA###", "/fitxategiak/ckfinder/", $testua));
}

function CKEditor_pintatu ($eremua, $balioa){
?>
<textarea id="<?php echo $eremua; ?>" name="<?php echo $eremua; ?>"><?php echo CKEditor_path_db2ed ($balioa); ?></textarea>
<script type="text/javascript">
	CKEDITOR.config.allowedContent = true;
	
	var editor = CKEDITOR.replace( '<?php echo $eremua; ?>' );
	CKFinder.setupCKEditor( editor, '<?php echo URL_BASE_ADMIN; ?>inc/ckfinder/' );
</script>
<?php
}

function hilabete_izena ($hilabetea, $hizkuntza=1){
	switch ($hilabetea){
		case 1:
			$izena = $hizkuntza == 1 ? "Urtarrila" : "Enero";
			break;
		case 2:
			$izena = $hizkuntza == 1 ? "Otsaila" : "Febrero";
			break;
		case 3:
			$izena = $hizkuntza == 1 ? "Martxoa" : "Marzo";
			break;
		case 4:
			$izena = $hizkuntza == 1 ? "Apirila" : "Abril";
			break;
		case 5:
			$izena = $hizkuntza == 1 ? "Maiatza" : "Mayo";
			break;
		case 6:
			$izena = $hizkuntza == 1 ? "Ekaina" : "Junio";
			break;
		case 7:
			$izena = $hizkuntza == 1 ? "Uztaila" : "Julio";
			break;
		case 8:
			$izena = $hizkuntza == 1 ? "Abuztua" : "Agosto";
			break;
		case 9:
			$izena = $hizkuntza == 1 ? "Iraila" : "Septiembre";
			break;
		case 10:
			$izena = $hizkuntza == 1 ? "Urria" : "Octubre";
			break;
		case 11:
			$izena = $hizkuntza == 1 ? "Azaroa" : "Noviembre";
			break;
		case 12:
			$izena = $hizkuntza == 1 ? "Abendua" : "Diciembre";
			break;
		default:
			$izena = "";
	}

	return ($izena);
}

function eguna_izena ($eguna, $hizkuntza=1){
	switch ($eguna){
		case 0:
			$izena = $hizkuntza == 1 ? "Igandea" : "Domingo";
			break;
		case 1:
			$izena = $hizkuntza == 1 ? "Astelehena" : "Lunes";
			break;
		case 2:
			$izena = $hizkuntza == 1 ? "Asteartea" : "Martes";
			break;
		case 3:
			$izena = $hizkuntza == 1 ? "Asteazkena" : "Miércoles";
			break;
		case 4:
			$izena = $hizkuntza == 1 ? "Osteguna" : "Jueves";
			break;
		case 5:
			$izena = $hizkuntza == 1 ? "Ostirala" : "Viernes";
			break;
		case 6:
			$izena = $hizkuntza == 1 ? "Larunbata" : "Sábado";
			break;
		default:
			$izena = "";
	}

	return ($izena);
}

function urtea ($data){
	$data_elementuak  = explode ("-",$data);

	return ($data_elementuak[0]);
}

function hilabetea ($data){
	$data_elementuak  = explode ("-",$data);

	return ($data_elementuak[1]);
}

function eguna ($data){
	$data_elementuak  = explode ("-",$data);

	return ($data_elementuak[2]);
}

function burua_fetxa ($hizkuntza=1){
	if ($hizkuntza == 1){
		return (eguna_izena (date ("w")) . ", " . date ("Y") . "eko " . hilabete_izena (date ("n")) . "ren " . date ("j") . "a");
	}
	elseif($hizkuntza == 2){
		return (eguna_izena (date ("w")) . ", " . date ("j"). " de " .hilabete_izena (date ("n")) ." de " . date ("Y"));
	}
}

function orden_max ($taula, $kond=""){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$max = 0;

	$sql = "SELECT MAX(orden) AS max FROM $taula";

	if (trim ($kond) != "")
		$sql .= " WHERE $kond";

	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$rowMax = $dbo->emaitza ();
		$max = $rowMax["max"];
	}

	return ($max);
}

function orden_automatiko ($taula, $id, $orden_berria, $kond=""){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	if (trim ($orden_berria) == "" || !is_numeric ($orden_berria))
		$orden_berria = 0;

	if (trim ($kond) != "")
		$sql_kond = "AND $kond";
	else
		$sql_kond = "";

	$sql = "SELECT orden FROM $taula WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();
		$orden_zaharra = $row["orden"];

		if ($orden_berria != $orden_zaharra){
			if ($orden_zaharra == 0){
				$sql = "UPDATE $taula SET orden=orden+1 WHERE orden >= '$orden_berria' $sql_kond";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}
			elseif ($orden_berria == 0){
				$sql = "UPDATE $taula SET orden=orden-1 WHERE orden > '$orden_zaharra' $sql_kond";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}
			elseif ($orden_berria < $orden_zaharra){
				$sql = "UPDATE $taula SET orden=orden+1 WHERE orden >= '$orden_berria' AND orden < '$orden_zaharra' $sql_kond";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}
			elseif ($orden_berria > $orden_zaharra){
				$sql = "UPDATE $taula SET orden=orden-1 WHERE orden <= '$orden_berria' AND orden > '$orden_zaharra' $sql_kond";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}

			// Establecemos el orden nuevo... Ya era hora!
			$sql = "UPDATE $taula SET orden='$orden_berria' WHERE id='$id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
		}
	}
}

function elementuaren_testua ($taula, $eremua, $id, $hizkuntza_id=0){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$testua = "";

	if (is_dbtable_id ("hizkuntzak", $hizkuntza_id)){
		$sql = "SELECT B.$eremua FROM $taula AS A INNER JOIN ${taula}_hizkuntzak AS B ON A.id=B.fk_elem WHERE A.id='$id' AND B.fk_hizkuntza='$hizkuntza_id'";
		$dbo->query ($sql) or die ($dbo->ShowError ());
		if ($dbo->emaitza_kopurua () == 1){
			$row = $dbo->emaitza ();

			$testua = $row[$eremua];
		}
	}
	else{
		$sql = "SELECT B.$eremua FROM ($taula AS A INNER JOIN ${taula}_hizkuntzak AS B ON A.id=B.fk_elem) INNER JOIN hizkuntzak AS C ON B.fk_hizkuntza=C.id WHERE A.id='$id' ORDER BY C.orden ASC";
		$dbo->query ($sql) or die ($dbo->ShowError ());
		while (($rowElem = $dbo->emaitza ()) && trim ($testua) == "")
			$testua = $rowElem[$eremua];
	}

	return ($testua);
}

function testuak_goiburua ($id){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT izena FROM testuak WHERE id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();

		return ($row["izena"]);
	}
	else
		return ("");
}

function data_formatua_egokitu ($data, $hizkuntza=1){
	if ($hizkuntza == 2)
		$formatua = "##EGUNA## de ##HILABETEA## de ##URTEA##";
	else
		$formatua = "##URTEA##ko ##HILABETEA##ren ##EGUNA##a";

	return (strtr ($formatua, array("##URTEA##" => urtea ($data), "##HILABETEA##" => hilabete_izena (hilabetea ($data), $hizkuntza), "##EGUNA##" => eguna ($data))));
}

function put_konfigurazioa ($gakoa, $balioa){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	// Comprobamos si es una insercion o una modificacion
	$sql = "SELECT balioa FROM konfigurazioa WHERE gakoa = '$gakoa'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1)
		$sql = "UPDATE konfigurazioa SET balioa = '$balioa' WHERE gakoa = '$gakoa'";
	else
		$sql = "INSERT INTO konfigurazioa (gakoa, balioa) VALUES ('$gakoa', '$balioa')";

	$dbo->query ($sql) or die ($dbo->ShowError ());
}

function kkuote ($testua){
	return (str_replace ('"', '""', $testua));
}

function hizkuntza_idak (){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$hizkuntzak = array ();

	$sql = "SELECT id FROM hizkuntzak ORDER BY orden ASC";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	while ($row = $dbo->emaitza ())
		array_push ($hizkuntzak, $row["id"]);

	return ($hizkuntzak);
}

function nice_name_hizkuntzak ($taula, $eremua, $id, $kond=""){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$nice_name = array ();

	if (trim ($kond) != "")
		$sql_kond = "AND $kond";
	else
		$sql_kond = "";

	$hizkuntzak = hizkuntza_idak ();

	// Hacemos que nice_name no se repita
	foreach ($hizkuntzak as $h_id){
		$nice_name[$h_id] = sanitize_title_with_dashes ($_POST["${eremua}_${h_id}"]);

		$sql = "SELECT * FROM ${taula} AS A INNER JOIN ${taula}_hizkuntzak AS B ON A.id=B.fk_elem WHERE B.fk_hizkuntza='$h_id' AND TRIM(B.nice_name)<>'' AND B.nice_name='" . $nice_name[$h_id] . "' AND A.id<>'$id' $sql_kond";
		$dbo->query ($sql) or die ($dbo->ShowError ());

		if ($dbo->emaitza_kopurua () > 0){
			$k = 1;
			do{
				$k++;
				$sql = "SELECT * FROM ${taula} AS A INNER JOIN ${taula}_hizkuntzak AS B ON A.id=B.fk_elem WHERE B.fk_hizkuntza='$h_id' AND TRIM(B.nice_name)<>'' AND B.nice_name='" . $nice_name[$h_id] . "-$k' AND A.id<>'$id' $sql_kond";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			} while ($dbo->emaitza_kopurua () > 0);

			$nice_name[$h_id] .= "-$k";
		}
	}

	return ($nice_name);
}

function enum_aukerak ($taula, $eremua){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$aukerak = array ();

	$sql = "SHOW COLUMNS FROM $taula LIKE '$eremua'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	$row = $dbo->emaitza ();

	$motak = $row["Type"];

	//hasierako enum(' eta bukaerako ') kentzen ditugu
	$motak = substr($motak, 6, strlen($motak)-8);

	$aukerak = explode ("','", $motak);

	return ($aukerak);
}




// ikasgela funtzioak
function gorde_ikasleak($ikasgela_id, $ikasleak){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);
	
	//ezabatu
	$sql = "DELETE FROM ikasgelak_ikasleak WHERE fk_ikasgela = $ikasgela_id";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	
	if(is_array($ikasleak) and !empty($ikasleak)){
		
		foreach($ikasleak as $ikaslea){
			$sql = "INSERT INTO ikasgelak_ikasleak (fk_ikasgela, fk_ikaslea)
				VALUES ($ikasgela_id, $ikaslea)";
			$dbo->query ($sql) or die ($dbo->ShowError ());
				
		}	
	}
	return true;
}

function hidden_zerrendak_osatu($elementuak, $eremua='id'){
	$hidden_balioa = '';
	if(!empty($elementuak)){
		foreach($elementuak as $elementua){
			$balioak[] = $elementua[$eremua];
		}
		$hidden_balioa = implode(";", $balioak).";";
	}
	return $hidden_balioa;
}


function get_ikasgela_datuak($id){
		
		$datuak = array();
		
		// ikasleak
		$sql = "SELECT * FROM ikasgelak_ikasleak WHERE fk_ikasgela = $id";
		$ikasleak = get_query($sql);
		$datuak['ikasle_kop'] = count($ikasleak);
		
		// ikasgai kopurua (totala)
		$sql = "SELECT * FROM ikasgaiak WHERE fk_ikasgela = $id";
		$ikasgaiak = get_query($sql);
		$datuak['ikasgaiak_guztira'] = count($ikasgaiak);
		// ikasgai kopurua (irekiak)
		$sql = "SELECT * FROM ikasgaiak WHERE fk_ikasgela = $id AND CURDATE() BETWEEN hasiera_data AND bukaera_data";
		$ikasgaiak = get_query($sql);
		$datuak['ikasgai_irekiak'] = count($ikasgaiak);
		return $datuak;
}


function get_ikasgaia_datuak($id){
		
	$sql = "SELECT *
		    FROM ikasgaiak WHERE id=$id";
		$ikasgaiak = get_query($sql);
		$datuak['ikasgaia'] = $ikasgaiak[0];
			
		
	// ariketak lortu
	$sql = "SELECT *
		FROM ikasgaiak_ariketak ia LEFT JOIN ariketak a ON ia.fk_ariketa = a.id
		WHERE ia.fk_ikasgaia = $id";
	
	$datuak = array();
	$datuak['ariketak'] = get_query($sql);
	
	// emaitzak lortu
	// TODO
	return $datuak;
}


// erregistroa funtzioak
function save_erregistro_datuak($datuak){
		$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);
		
		if(!is_numeric($datuak['fk_elementua']))
				return false;
		
		// taulan badago, update
		$sql = "SELECT id FROM erregistroa
				WHERE fk_elementua=".$datuak['fk_elementua']."
				AND elementu_mota = '".$datuak['elementu_mota']."'";
		$emaitzak = get_query($sql);
		if(empty($emaitzak)){
				// insert
				$sql = "INSERT INTO erregistroa (fk_elementua, elementu_mota, fk_sortze_erabiltzailea, fk_aldatze_erabiltzailea, sortze_data, aldatze_data)
										VALUES	(".$datuak['fk_elementua'].", '".$datuak['elementu_mota']."', '".$datuak['fk_sortze_erabiltzailea']."', '".$datuak['fk_sortze_erabiltzailea']."', NOW(), NOW())";
		} else {
				// update
				$sql = "UPDATE erregistroa SET fk_aldatze_erabiltzailea = '".$datuak['fk_aldatze_erabiltzailea']."', aldatze_data = NOW()
						WHERE fk_elementua=".$datuak['fk_elementua']."
						AND elementu_mota = '".$datuak['elementu_mota']."'";
	
		}
		$dbo->query ($sql) or die ($dbo->ShowError ());
		return true;
}

function get_erregistro_datuak($fk_elementua, $elementu_mota){
		$erregistro_datuak = array();
		$sql = "SELECT *
				FROM erregistroa
				WHERE fk_elementua = $fk_elementua
				AND elementu_mota = '$elementu_mota'";
		$emaitzak = get_query($sql);
		if(!empty($emaitzak)){
				$erregistro_datuak = $emaitzak[0];
		}
		
		return $erregistro_datuak;
}


?>
