<?php

class erabiltzailea {
	protected $dbo;
	protected $erabiltzailea_id;

	function erabiltzailea (){
		@session_start ();

		$this->dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

		$this->erabiltzailea_id = (isset ($_SESSION["erabiltzailea_id"])) ? (int) $_SESSION["erabiltzailea_id"] : 0;

		return (true);
	}

	public function get_id (){
		return ($this->erabiltzailea_id);
	}

	public function get_erabiltzailea (){
		return (get_dbtable_field_by_id ("ikasleak", "izena", $this->erabiltzailea_id) . " " . get_dbtable_field_by_id ("ikasleak", "abizenak", $this->erabiltzailea_id));
	}

	public function login ($id){
		$_SESSION["erabiltzailea_id"] = $this->erabiltzailea_id = $id;

		// Porsiaka: Borramos sesiones colgadas (del mismo usuario y con la misma session. Puede pasar que no cierre el navegador
		// y despues de mucho rato sin entrar la sesion este colgada...)
		$sql = "DELETE FROM sesioak_erabiltzailea WHERE fk_erabiltzailea='$id' AND session_id='" . session_id () . "'";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		// Creamos la sesion
		$sql = "INSERT INTO sesioak_erabiltzailea (time, fk_erabiltzailea, session_id, ip, user_agent) VALUES ('" . time () . "', '$id', '" . session_id () . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $_SERVER["HTTP_USER_AGENT"] . "')";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		// Hacemos limpieza de sesiones "colgadas" (de cualquiera)
		$this->sesioak_garbitu ();
	}

	public function logout (){
		// Borramos los datos de la sesion (base de datos)
		$sql = "DELETE FROM sesioak_erabiltzailea WHERE fk_erabiltzailea='" . $this->erabiltzailea_id . "' AND session_id='" . session_id () . "'";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		$this->erabiltzailea_id = 0;

		// Borramos los datos de la sesion (cookies)
		unset ($_SESSION["erabiltzailea_id"]);
	}

	public function logged (){
		if (is_dbtable_id ("ikasleak", $this->erabiltzailea_id)){
			// Comprobamos si se ha iniciado sesion...
			$sql = "SELECT * FROM sesioak_erabiltzailea WHERE fk_erabiltzailea='" . $this->erabiltzailea_id . "' AND session_id='" . session_id () . "'";
			$this->dbo->query ($sql) or die ($this->dbo->ShowError ());
			if ($this->dbo->emaitza_kopurua () == 1){
				$rowSesioa = $this->dbo->emaitza ();

				// Comprobamos que no se haya pasado el tiempo...
				if ((time () - $rowSesioa["time"]) < 1800){
					// Actualizamos el tiempo de la sesion
					$sql = "UPDATE sesioak_erabiltzailea SET time = '" . time () . "' WHERE fk_erabiltzailea='" . $this->erabiltzailea_id . "' AND session_id='" . session_id () . "'";
					$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

					return (true);
				}
			}
		}

		return (false);
	}

	private function sesioak_garbitu (){
		// Borramos las sesiones que llevan un dia "sin usar"...
		$sql = "DELETE FROM sesioak_erabiltzailea WHERE time < " . (time () - 86400);
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		// Optimizamos la tabla. Es una pijada, pero creo que asegura que se actualicen los indices creados para la tabla
		db_taula_optimizatu ("sesioak_erabiltzailea");
	}

}

?>
