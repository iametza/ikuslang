<?php

class URL{
	var $idak;
	var $uneko_id;

	function URL (){
		$this->idak = Array ();
		$this->uneko_id = 0;

		if (isset ($_GET["ids"]))
			$this->idak = explode ("/", $_GET["ids"]);
		else
			$this->idak = Array ();
	}

	function hurrengoa (){
		if ($this->uneko_id < count ($this->idak)){
			return ($this->idak[$this->uneko_id++]);
		}
		else{
			return ("");
		}
	}

	function aurrekoa (){
		if ($this->uneko_id > 0){
			return ($this->idak[$this->uneko_id--]);
		}
		else{
			return ("");
		}
	}

	function unekoa (){
		return ($this->idak[$this->uneko_id]);
	}

	function hasierara (){
		$this->uneko_id = 0;
	}

	function id ($i){
		if ($i >= 0 && $i < count ($this->idak)){
			return ($this->idak[$i]);
		}
		else{
			return ("");
		}
	}

	function kopurua (){
		return (count ($this->idak));
	}

	function posizioa (){
		return ($this->uneko_id);
	}

	function kokatu ($posizioa){
		if ($posizioa >= 0 && $posizioa < count ($this->idak)){
			$this->uneko_id = $posizioa;
		}
	}

	function maila (){
		if (count ($this->idak) > 1)
			return (str_repeat ("../", count ($this->idak) - 1));
		else
			return ("");
	}
}

?>
