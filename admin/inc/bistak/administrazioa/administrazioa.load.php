<?php

	$url_base = URL_BASE_ADMIN . "administrazioa/";

	$menu_aktibo = "administrazioa";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	if ($url->hurrengoa () == "form"){
		$edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
		$pasahitz_ez_aldatua = "##$$@@&";
		$erab_existitzen = 0;
		
		// Borrado
		if (isset ($_GET["ezab_id"])){
			$ezab_id = $_GET["ezab_id"];

			//Borramos el elemento
			$sql = "DELETE FROM administrazioa WHERE id='$ezab_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());

			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Inserciones o modificaciones
		if (isset ($_POST["gorde"])){
			// Formularioko datuak eskuratuko ditugu.
			$edit_id = $_POST["edit_id"];
			$erab = testu_formatua_sql ($_POST["erabiltzailea"]);
			$p1 = testu_formatua_sql ($_POST["p1"]);
			$p2 = testu_formatua_sql ($_POST["p2"]);
			$sql_pasahitza = "";
			
			if (trim ($p1) != "" && trim ($p2) != "" && $p1 == $p2){
				//Comprobamos que el usuario no exista
				$sql = "SELECT * FROM administrazioa WHERE erabiltzailea='$erab' AND id <> '$edit_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
				if ($dbo->emaitza_kopurua () > 0){
					$erab_existitzen = 1;
				}
				else{
					if (is_dbtable_id ("administrazioa", $edit_id)){
						if ($p1 != $pasahitz_ez_aldatua){
							$sql_pasahitza = ", pasahitza='" . sha1 ($p1) . "'";
						}

						$sql = "UPDATE administrazioa SET erabiltzailea='$erab'$sql_pasahitza WHERE id='$edit_id'";
					}
					else{
						$sql = "INSERT INTO administrazioa (erabiltzailea, pasahitza) VALUES ('$erab', '" . sha1 ($p1) . "')";
					}
				}

				if (!$erab_existitzen){
					$dbo->query ($sql) or die ($dbo->ShowError ());
				}
			}

			if (!$erab_existitzen){
				//Redireccionamos.
				header ("Location: " . $url_base . $url_param);
				exit;
			}
		}

		// Si es una modificacion recogemos los datos para presentarlos en el formulario
		if (is_dbtable_id ("administrazioa", $edit_id)){
			$sql = "SELECT * FROM administrazioa WHERE id='$edit_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
			$row = $dbo->emaitza ();

			$erab = $row["erabiltzailea"];
			$pasahitza = $pasahitz_ez_aldatua;
		}
		
		$content = "inc/bistak/administrazioa/elementua.php";
	}
	else{
		$sql = "SELECT * FROM administrazioa WHERE id<>'1' ORDER BY erabiltzailea ASC";
		
		$orrikapena = orrikapen_datuak ($sql, $p);
		$sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];

		$elementuak = get_query ($sql);
		
		$content = "inc/bistak/administrazioa/elementuak.php";
	}

?>
