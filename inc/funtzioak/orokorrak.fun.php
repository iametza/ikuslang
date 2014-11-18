<?php

require ("inc/funtzioak/globalak.fun.php");
require ("inc/funtzioak/sanitize.php");

function hizkuntza_datuak ($nice_name, $hutsa_baimendu=1){
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$datuak = "";

	if (trim ($nice_name) != ""){
		$sql = "SELECT * FROM hizkuntzak WHERE orden > 0 AND nice_name='$nice_name'";
		$dbo->query ($sql) or die ($dbo->ShowError ());
		if ($dbo->emaitza_kopurua () == 1)
			$datuak = $dbo->emaitza ();
	}
	elseif ($hutsa_baimendu){
		$sql = "SELECT * FROM hizkuntzak WHERE orden > 0 ORDER BY orden ASC LIMIT 1";
		$dbo->query ($sql) or die ($dbo->ShowError ());
		if ($dbo->emaitza_kopurua () == 1)
			$datuak = $dbo->emaitza ();
	}

	return ($datuak);
}

function esteka ($lotura){
	if (trim ($lotura) == ""){
		return ("#");
	}
	elseif (preg_match ("@^/(.*)$@", $lotura, $datuak)){
		return (URL_BASE . $datuak[1]);
	}
	else{
		if (strtoupper (substr ($lotura,0,7)) == "HTTP://" ||
		strtoupper (substr ($lotura,0,8)) == "HTTPS://"){
			return ($lotura);
		}
		else{
			return ("http://$lotura");
		}
	}
}

function CKEditor_path_aldatu ($testua){
	return (str_replace ("###MAILA###", URL_BASE . "fitxategiak/ckfinder/", $testua));
}

function hilabete_izena ($hilabetea){
	global $hto;
	$izena = "";

	switch ($hilabetea){
		case 1:
			$izena = $hto->motz ("orok_urtarrila");
			break;
		case 2:
			$izena = $hto->motz ("orok_otsaila");
			break;
		case 3:
			$izena = $hto->motz ("orok_martxoa");
			break;
		case 4:
			$izena = $hto->motz ("orok_apirila");
			break;
		case 5:
			$izena = $hto->motz ("orok_maiatza");
			break;
		case 6:
			$izena = $hto->motz ("orok_ekaina");
			break;
		case 7:
			$izena = $hto->motz ("orok_uztaila");
			break;
		case 8:
			$izena = $hto->motz ("orok_abuztua");
			break;
		case 9:
			$izena = $hto->motz ("orok_iraila");
			break;
		case 10:
			$izena= $hto->motz ("orok_urria");
			break;
		case 11:
			$izena = $hto->motz ("orok_azaroa");
			break;
		case 12:
			$izena = $hto->motz ("orok_abendua");
			break;
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

function data_formatua_egokitu ($data, $formatua=0){
	global $hizkuntza;
	global $hto;

	switch ($formatua){
		case 1:
			return (strtr ($hto->motz ("orok_data_formatua"), array("##URTEA##" => urtea ($data), "##HILABETEA##" => hilabete_izena (hilabetea ($data)), "##EGUNA##" => eguna ($data))));
			break;
		default:
			// Separamos la hora de la fecha (si es que tiene...)
			$zatiak = explode (" ", $data);

			$em = strtr ($hizkuntza["data_formatua"], array("U" => urtea ($zatiak[0]), "H" => hilabetea ($zatiak[0]), "E" => eguna ($zatiak[0])));

			/*if (count ($zatiak) == 2)
				$em .= "&nbsp;/&nbsp;" . $zatiak[1];*/

			return ($em);
			//return (strtr ($hizkuntza["data_formatua"], array("U" => urtea ($data), "H" => hilabetea ($data), "E" => eguna ($data))));
	}
}

function orrikapen_indizeak ($datuak, $helb){
	global $url;
	global $hto;
?>
<div class="pagination">
<ul>
<?php
	if($datuak["pagina"] > 1)
		echo '<li><a href="' . $helb . '?p=' . ($datuak["pagina"]-1) . querystring_param_kendu ("ids", "p") . '">&laquo; ' . $hto->motz ("orok_aurrekoak") . '</a></li>' . "\n";

	for ($i = $datuak["inicio"]; $i <= $datuak["final"]; $i++){
		if ($i == $datuak["pagina"]){
			echo '<li><a href="#" class="active" title="' . $i . '">' . $i . '</a></li>' . "\n";
		}
		else{
			echo '<li><a href="' . $helb . '?p=' . $i . querystring_param_kendu ("ids", "p") . '" title="' . $i . '">' . $i . '</a></li>' . "\n";
		}
	}

	if ($datuak["pagina"] < $datuak["numPags"])
		echo '<li><a href="' . $helb . '?p=' . ($datuak["pagina"]+1) .  querystring_param_kendu ("ids", "p") . '">' . $hto->motz ("orok_hurrengoak") . ' &raquo;</a></li>' . "\n";
?>
</ul>
</div>
<?php
}

function tamaina ($fitx){
	if (is_file ($fitx)){
		$bytes = filesize ($fitx);

		$position = 0;
		$units = array(" bytes", " Kb.", " Mb.", " Gb.", " Tb." );
		while( $bytes >= 1024 && ( $bytes / 1024 ) >= 1 ) {
			$bytes /= 1024;
			$position++;
		}

		return round ($bytes, 1) . $units[$position];
	}
	else{
		return ("0 bytes");
	}
}

function get_testua ($id){
	global $hizkuntza;

	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$sql = "SELECT A.id, A.irudia, A.path, B.testua FROM testuak AS A INNER JOIN testuak_hizkuntzak AS B ON A.id=B.fk_elem WHERE B.fk_hizkuntza='" . $hizkuntza["id"] . "' AND A.id = '$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1)
		return ($dbo->emaitza ());
	else
		return ("");
}


function get_eremua_hizkuntzan ($taula, $eremua, $id){
	global $hizkuntza;

	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

	$testua = "";

	$sql = "SELECT B.$eremua FROM $taula AS A INNER JOIN ${taula}_hizkuntzak AS B ON A.id=B.fk_elem AND B.fk_hizkuntza='" . $hizkuntza["id"] . "' WHERE A.id='$id'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();

		$testua = $row[$eremua];
	}

	return ($testua);
}

function albistea_erantzun_kopurua ($id){
	global $hizkuntza;
	
	$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);
	
	$sql = "SELECT COUNT(id) AS kopurua FROM albisteak_erantzunak WHERE fk_albistea='$id' AND fk_hizkuntza='$hizkuntza[id]' AND publikoa='1'";
	$dbo->query ($sql) or die ($dbo->ShowError ());
	if ($dbo->emaitza_kopurua () == 1){
		$row = $dbo->emaitza ();
		
		return ($row["kopurua"]);
	}
	else
		return (0);
}

function kortito ($testua, $hitz_kop = 10){
	$testua = strip_tags ($testua);

	$hitzak = preg_split ("/[\n\r\t ]+/", $testua, $hitz_kop + 1, PREG_SPLIT_NO_EMPTY);

	if (count ($hitzak) > $hitz_kop){
		array_pop ($hitzak);
		$testua = implode (' ', $hitzak);
		$testua = $testua . " [...]";
	}
	else
		$testua = implode (' ', $hitzak);

	return ($testua);
}

function gorde_emaitzak($datuak){
		$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);
		extract($datuak);
		
		$sql = "INSERT INTO ariketa_emaitza (data, fk_ikaslea, fk_ariketa, fk_ikasgaia)
                        VALUES ('" . date("Y-m-d H:i:s"). "', $id_ikaslea, $id_ariketa, $id_ikasgaia)";
                
        if ($dbo->query($sql)) {
                    
                $fk_ariketa_emaitza = db_taula_azken_id("ariketa_emaitza");
                    
				foreach($zuzenak as $zuzena) {
					
					$sql = "INSERT INTO ariketa_emaitza_zuzenak (fk_erantzuna, fk_ariketa_emaitza)
							VALUES (" . (int) $zuzena . ", $fk_ariketa_emaitza)";
					
					if (!$dbo->query($sql)) {
						return false;	
					}
					
				}
				
				foreach($okerrak as $okerra) {
					
					$sql = "INSERT INTO ariketa_emaitza_okerrak (fk_erantzuna, fk_ariketa_emaitza)
							VALUES (" . (int) $okerra . ", $fk_ariketa_emaitza)";
					
					if (!$dbo->query($sql)) {
						return false;
					}
					
				}
				return true;
				
                    
        } else {
                    
                return false;
                    
        }
}


?>
