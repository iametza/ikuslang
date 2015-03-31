<?php

require_once ("app_modeloa.php");

class IkasgelaModeloa extends AppModeloa {
  
  var $taula = 'ikasgelak';
  var $taula_hizkuntzak = '';
  var $hizkuntza_eremuak = array();
  
  function __construct(){
    parent::__construct();
  }
  
  function get($id){
    $emaitza = parent::get($id);
    if($emaitza){
        
       
		// ikasleak lortu
        $sql = "SELECT i.id as id, CONCAT(i.izena, ' ', i.abizenak) as izen_abizenak, e_posta
            FROM ikasgelak_ikasleak ii LEFT JOIN ikasleak i ON ii.fk_ikaslea = i.id
            WHERE ii.fk_ikasgela = $id";
        //echo $sql;
        $emaitza->ikasleak = get_query($sql);
        if(!empty($ikasleak)){
            foreach($ikasleak as $ikaslea){
                $ikasle_idak[] = $ikaslea['id'];
            }
        }
        
        
        // ikasgaiak lortu
        $sql = "SELECT *
            FROM ikasgaiak
            WHERE fk_ikasgela = $id
            ORDER BY hasiera_data";
        $emaitza->ikasgaiak = get_query($sql);
    }
        
    return $emaitza;
  }
  
  
  function get_zerrenda($paginazio_datuak = '', $kriterioak = ''){
        $where_irakaslea = '';
        if(isset($kriterioak['fk_irakaslea']) and $kriterioak['fk_irakaslea'] != ''){
            $where_irakaslea = " AND fk_irakaslea = ".$kriterioak['fk_irakaslea'];
        }
        $sql = "SELECT * FROM ikasgelak
                WHERE 1
                $where_irakaslea
                ORDER BY izena ASC";
       
        $orrikapena = orrikapen_datuak ($sql, $p);
        
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
         return get_query($sql);
        
    }
  
  
    
}

?>