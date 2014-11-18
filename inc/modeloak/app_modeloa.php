<?php

class AppModeloa {
    
    var $taula = '';
    var $taula_hizkuntzak = '';
    var $hizkuntza_eremuak = '';
    
    function __construct() {
        // lortu dbo
        $this->dbo = new DBO(DB_SERV, DB_USER, DB_PASS, DB_NAME);
    }
    
    function get($id){
        
        $sql = "SELECT * FROM ".$this->taula." WHERE id=$id";
        $emaitza = $this->dbo->query_emaitza($sql, 0);
        if($emaitza){
            
            // begiratu definitua dagoen hizkuntzak taula
            if($this->taula_hizkuntzak != ''){
                       
                $emaitza->hizkuntzak = array();
                
                $hizkuntza_eremuak_sql = implode(',', $this->hizkuntza_eremuak);
            
                foreach (hizkuntza_idak() as $h_id) {
                    
                    $sql = "SELECT ".$hizkuntza_eremuak_sql."
                            FROM ".$this->taula_hizkuntzak."
                            WHERE fk_elem = $id
                            AND fk_hizkuntza = $h_id";
                   
                    $rowHizk = $this->dbo->query_emaitza($sql, 0);
                    if($rowHizk)
                        $emaitza->hizkuntzak[$h_id] = $rowHizk;
                    else
                        $emaitza->hizkuntzak[$h_id] = new stdClass();
                    
                }
            }
            
            
            return $emaitza;
        }
        else
            return new stdClass();
        
    }
    
    function save($datuak) {
       
         // Ikasgaia dagoeneko existitzen ez bada, taulan txertatuko dugu.
        if (!is_dbtable_id($this->taula, $datuak['id'])) {
            $id = $this->dbo->insert($this->taula, $datuak);
        } else {
            $id = $this->dbo->update($this->taula, $datuak);    
		}
        return $id;
    }
    
    
    function save_hizkuntza_datuak($datuak_hizkuntzak){
        // Errenkada dagoeneko existitzen den egiaztatuko dugu.
			$sql = "SELECT *
					FROM ".$this->taula_hizkuntzak."
					WHERE fk_elem = '".$datuak_hizkuntzak['fk_elem']."' AND fk_hizkuntza = '".$datuak_hizkuntzak['fk_hizkuntza']."'";
			
			$this->dbo->query($sql) or die($this->dbo->ShowError());
			
			if ($this->dbo->emaitza_kopurua() == 0) {
				
				$this->dbo->insert($this->taula_hizkuntzak, $datuak_hizkuntzak);
				
			} else {
				// definiu updatearen where
                $where = array('fk_elem', 'fk_hizkuntza');
                $this->dbo->update($this->taula_hizkuntzak, $datuak_hizkuntzak, $where);
				
			}
			
    }
    
    function delete($id){
         // Ezabatu beharreko ikasgaiaren id-a eskuratuko dugu.
        $ezab_id = (int) $id;
        
        if ($ezab_id > 0) {
        
            // begiratu definitua dagoen hizkuntzak taula
            if($this->taula_hizkuntzak != ''){
                // Lehenik bere hizkuntza desberdinetako datuak.
                $sql = "DELETE
                        FROM ".$this->taula_hizkuntzak."
                        WHERE fk_elem = $ezab_id";
                
                $this->dbo->query($sql) or die($this->dbo->ShowError());
            }
        
            // Ondoren bere datuak.
            $sql = "DELETE
                    FROM ".$this->taula."
                    WHERE id = $ezab_id";
            
            $this->dbo->query($sql) or die($this->dbo->ShowError());
        }
        
    }
    
    
    function get_zerrenda($paginazioa = 1){
        $sql = "SELECT *
                FROM {$this->taula}
                ORDER BY id ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $p);
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        return get_query($sql);
        
    }
    
    
    
}