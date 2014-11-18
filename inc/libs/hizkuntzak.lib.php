<?php

/*
La clase TXT es simplemente para sacar de la base de datos los "literales" (tablas txt y txt_hizkuntzak. Tiene administracion
"oculta" en /admin/literalak.php).

Guarda una conexion a la base de datos y el id del idioma en el que se esta navegando.
*/
class TXT {
	var $dbo;
	var $uneko_hizkuntza_id;

	function TXT ($hizkuntza){
		$this->dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

		$this->uneko_hizkuntza_id = (is_array ($hizkuntza) && array_key_exists ("id", $hizkuntza)) ? $hizkuntza["id"] : 0;

		return (true);
	}

	private function get_db_eremua ($gako, $hizkuntza_id, $eremua){
		if ($hizkuntza_id == 0)
			$hizkuntza_id = $this->uneko_hizkuntza_id;

		$sql = "SELECT $eremua FROM txt_hizkuntzak WHERE fk_elem='$gako' AND fk_hizkuntza='$hizkuntza_id'";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());
		if ($this->dbo->emaitza_kopurua () == 1){
			$row = $this->dbo->emaitza ();

			return ($row[$eremua]);
		}
		else
			return ("");
	}

	public function motz ($gako, $hizkuntza_id=0){
		return ($this->get_db_eremua ($gako, $hizkuntza_id, "motz"));
	}

	public function nice ($gako, $hizkuntza_id=0){
		return ($this->get_db_eremua ($gako, $hizkuntza_id, "nice_name"));
	}

	public function luze ($gako, $hizkuntza_id=0){
		return (CKEditor_path_aldatu ($this->get_db_eremua ($gako, $hizkuntza_id, "luze")));
	}
}

/*
La clase hizkuntzak es para "calcular" la URL de los distintos idiomas y a la hora de cambiar entre ellos quedarnos
en el mismo sitio (o lo mas cerca posible). Hereda de TXT porque me conviene (necesitaba la funcion "nice" y la
conexion a la base de datos), mayormente porque asi tengo todas las funciones en un solo objeto, una sola variable.
*/
class hizkuntzak extends TXT {
	var $url_lista;

	function hizkuntzak ($hizkuntza){
		parent::TXT ($hizkuntza);

		$this->url_lista = Array ();

		foreach (hizkuntza_guztien_datuak () as $h){
			$h_id = $h["id"];

			if ($h_id == $this->uneko_hizkuntza_id)
				$this->url_lista[$h_id] = Array ("url" => "#", "up" => false);
			else
				$this->url_lista[$h_id] = Array ("url" => URL_BASE . $h["nice_name"], "up" => true);
		}

		return (true);
	}

	// Funcion para verificar que existe un idioma en la lista y que es actualizable
	private function upgradeable ($id){
		return (array_key_exists ($id, $this->url_lista) && $this->url_lista[$id]["up"]);
	}

	// Funcion que devuelve solo los idiomas que son actualizables
	private function upgradeables (){
		$hizkuntzak = Array ();

		foreach (hizkuntza_guztien_datuak () as $h){
			if ($this->upgradeable ($h["id"]))
				array_push ($hizkuntzak, $h);
		}

		return ($hizkuntzak);
	}

	// Funcion que actualiza la URL de cada idioma agregando el "nice" del codigo pasado como parametro
	public function add_atala ($kodea){
		foreach ($this->upgradeables () as $h){
			$h_id = $h["id"];

			if (trim ($url = $this->nice ($kodea, $h_id)) != "")
				$this->url_lista[$h_id]["url"] .= "/" . $url;
			else
				$this->url_lista[$h_id]["up"] = false;
		}
	}

	// Funcion que actualiza la URL de cada idioma agregando el parametro pasado
	public function add_param ($param){
		foreach ($this->upgradeables () as $h){
			$h_id = $h["id"];

			$this->url_lista[$h_id]["url"] .= $param;
		}
	}

	// Funcion que actualiza la URL de cada idioma agregando el "nice_name" obtenido con la tabla e id pasados como parametro
	public function add_db_elementua ($taula, $id){
		foreach ($this->upgradeables () as $h){
			$h_id = $h["id"];

			// Comprobamos si existe el elemento en el idioma
			$sql = "SELECT nice_name FROM ${taula}_hizkuntzak WHERE fk_elem='$id' AND fk_hizkuntza='$h_id' AND TRIM(nice_name)<>''";
			$this->dbo->query ($sql) or die ($this->dbo->ShowError ());
			if ($this->dbo->emaitza_kopurua () == 1){
				$row = $this->dbo->emaitza ();

				$this->url_lista[$h_id]["url"] .= "/" . $row["nice_name"];
			}
			else
				$this->url_lista[$h_id]["up"] = false;
		}
	}

	public function add_db_elementua_direct ($taula, $id){
		foreach ($this->upgradeables () as $h){
			$h_id = $h["id"];

			// Comprobamos si existe el elemento
			$sql = "SELECT nice_name FROM ${taula} WHERE id='$id' AND TRIM(nice_name)<>''";
			$this->dbo->query ($sql) or die ($this->dbo->ShowError ());
			if ($this->dbo->emaitza_kopurua () == 1){
				$row = $this->dbo->emaitza ();

				$this->url_lista[$h_id]["url"] .= "/" . $row["nice_name"];
			}
			else
				$this->url_lista[$h_id]["up"] = false;
		}
	}

	// Funcion que devuelve la URL del idioma pasado como parametro
	public function get_url ($id){
		if (array_key_exists ($id, $this->url_lista))
			return ($this->url_lista[$id]["url"]);
		else
			return ("");
	}
}

?>
