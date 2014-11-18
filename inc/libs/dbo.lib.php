<?php

class DBO
{
	var $server;
	var $username;
	var $password;
	var $database;
	
	var $konexioa;
	var $emaitza;
	var $query;
	
	function DBO($server, $username, $password, $database)
	{
		$this->server=$server;
		$this->username=$username;
		$this->password=$password;
		$this->database=$database;
		
		if ($konexioa=mysql_connect($server, $username, $password))
			if (mysql_select_db($database, $konexioa))
			{
				// Hau gabe testu-kateak iso-8859 balira bezala irakurtzen ditu eta ?ak agertzen dira ñ-en ordez.
				mysql_set_charset("utf8");
				
				$this->konexioa=$konexioa;
				return true;
			}
			else
			{
				echo "Ez da $database datubasea aurkitu.";
				return false;
			}
		else
		{
			echo "Ezin izan da datubase zerbitzariarekin konektatu.";
			return false;
		}
	}
	
	function query($sql)
	{
		
		if($this->query=mysql_query($sql, $this->konexioa))
			return true;
		else 
			return false;
	}
    
    function query_emaitza($sql, $indizea=false)
	{
		$emaitza = array();
		if($this->query=mysql_query($sql, $this->konexioa)){
            while ($row = mysql_fetch_object($this->query)) {
                $emaitza[] = $row;
            }
            
            if($indizea !== false){
                if(array_key_exists($indizea, $emaitza)){
                    return $emaitza[$indizea];
                }else{
                    return false;
                }
            }else
                return $emaitza;
		
		}
		else 
			return false;
	}
    
    function insert($taula, $datuak){
        if(isset($datuak['id']))
           $datuak['id'] = '';
        
        $eremuak_sql = implode(',', array_keys($datuak));
        
        $balioak_sql = implode(',', array_map('prestatu_balioa', $datuak));
        
        $sql = "INSERT INTO $taula ($eremuak_sql) VALUES ($balioak_sql)";
        if($this->query($sql))
            return mysql_insert_id($this->konexioa);
        else
            return false;
    }
    
    function update($taula, $datuak, $where = array('id')){
                
        if(in_array('id', $where)){
            $id = $datuak['id'];
        }
        $where_conditions = array();
        foreach ($where as $eremua){
            if(!isset($datuak[$eremua]))
                return false;
            $where_conditions[] = " $eremua = ". prestatu_balioa($datuak[$eremua])."";
            unset($datuak[$eremua]);
        }
        $where_sql = implode (' AND ', $where_conditions);
        
        $update_sql = "";
        if(empty ($datuak))
            return false;
        
        foreach($datuak as $eremua => $balioa){
            $update_sql .= " $eremua = ". prestatu_balioa($balioa) .", ";
        }
        $update_sql = trim($update_sql, ', ');
        
        $sql = "UPDATE ".$taula."
				SET $update_sql
				WHERE $where_sql";
                

        if($this->query($sql)){
            // where-n id-a badago hau itzuli, bestela true
            if(isset($id))
                return $id;
            else
                return true;
        }
        else
            return false;
    }
    
   
	function emaitza()
	{
		$this->emaitza=mysql_fetch_array($this->query);	
		return $this->emaitza;
	}
	
	function itxi()
	{
		return mysql_close($this->konexioa);
	}
		
	function lehenengoa()
	{
		$this->emaitza=mysql_fetch_array($this->query);
		return $this->emaitza[0];
	}
	
	function ShowError()
	{
  die("Error " . mysql_errno() . " : " . mysql_error());
	}

	function emaitza_kopurua()
	{
		return mysql_num_rows($this->query);
	}

	function hasierara()
	{
		if (mysql_field_seek ($this->query, 0))
			return true;
		else
			return false;
	}


}

?>
