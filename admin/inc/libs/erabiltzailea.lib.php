<?php

class erabiltzailea {
	protected $dbo;
	protected $admin_id;
	protected $rola;
	protected $fk_irakaslea;

	function erabiltzailea (){
		@session_start ();

		$this->dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

		$this->admin_id = (isset ($_SESSION["admin_id"])) ? (int) $_SESSION["admin_id"] : 0;
		$this->rola = (isset ($_SESSION["rola"])) ? $_SESSION["rola"] : "";
		$this->fk_irakaslea = (isset ($_SESSION["fk_irakaslea"])) ? $_SESSION["fk_irakaslea"] : "";

		return (true);
	}

	public function get_id (){
		return ($this->admin_id);
	}
	public function get_rola (){
		return ($this->rola);
	}
	public function get_fk_irakaslea (){
		return ($this->fk_irakaslea);
	}

	public function get_erabiltzailea (){
		return (get_dbtable_field_by_id ("administrazioa", "erabiltzailea", $this->admin_id));
	}

	public function login ($id){
		$_SESSION["admin_id"] = $this->admin_id = $id;
		
		// Jaso rola
		$_SESSION["rola"] = $this->rola = get_dbtable_field_by_id ("administrazioa", "rola", $this->admin_id);
		if($this->rola == 'irakaslea'){
			
			$erabiltzailea = $this->get_erabiltzailea();
			$irakaslea = get_query("SELECT * FROM irakasleak WHERE e_posta='$erabiltzailea'");
			
			if(!empty($irakaslea)){
				$_SESSION["fk_irakaslea"] = $this->fk_irakaslea = $irakaslea[0]['id'];
			}
			
			
		}

		// Porsiaka: Borramos sesiones colgadas (del mismo usuario y con la misma session. Puede pasar que no cierre el navegador
		// y despues de mucho rato sin entrar la sesion este colgada...)
		$sql = "DELETE FROM sesioak WHERE fk_erabiltzailea='$id' AND session_id='" . session_id () . "'";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		// Creamos la sesion
		$sql = "INSERT INTO sesioak (time, fk_erabiltzailea, session_id, ip, user_agent) VALUES ('" . time () . "', '$id', '" . session_id () . "', '$_SERVER[REMOTE_ADDR]', '$_SERVER[HTTP_USER_AGENT]')";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		// Hacemos limpieza de sesiones "colgadas" (de cualquiera)
		$this->sesioak_garbitu ();
	}

	public function logout (){
		// Borramos los datos de la sesion (base de datos)
		$sql = "DELETE FROM sesioak WHERE fk_erabiltzailea='" . $this->admin_id . "' AND session_id='" . session_id () . "'";
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		$this->admin_id = 0;

		// Borramos los datos de la sesion (cookies)
		unset ($_SESSION["admin_id"]);
	}

	public function logged (){
		if (is_dbtable_id ("administrazioa", $this->admin_id)){
			// Comprobamos si se ha iniciado sesion...
			$sql = "SELECT * FROM sesioak WHERE fk_erabiltzailea='" . $this->admin_id . "' AND session_id='" . session_id () . "'";
			$this->dbo->query ($sql) or die ($this->dbo->ShowError ());
			if ($this->dbo->emaitza_kopurua () == 1){
				$rowSesioa = $this->dbo->emaitza ();

				// Comprobamos que no se haya pasado el tiempo...
				if ((time () - $rowSesioa["time"]) < 1800){
					// Actualizamos el tiempo de la sesion
					$sql = "UPDATE sesioak SET time = '" . time () . "' WHERE fk_erabiltzailea='" . $this->admin_id . "' AND session_id='" . session_id () . "'";
					$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

					return (true);
				}
			}
		}

		return (false);
	}

	private function sesioak_garbitu (){
		// Borramos las sesiones que llevan un dia "sin usar"...
		$sql = "DELETE FROM sesioak WHERE time < " . (time () - 86400);
		$this->dbo->query ($sql) or die ($this->dbo->ShowError ());

		// Optimizamos la tabla. Es una pijada, pero creo que asegura que se actualicen los indices creados para la tabla
		db_taula_optimizatu ("sesioak");
	}

}

?>
