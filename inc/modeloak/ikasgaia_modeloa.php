<?php

require_once ("app_modeloa.php");

class IkasgaiaModeloa extends AppModeloa {
  
  var $taula = 'ikasgaiak';
  var $taula_hizkuntzak = 'ikasgaiak_hizkuntzak';
  var $hizkuntza_eremuak = array('izenburua', 'azalpena');
  
  function __construct(){
    parent::__construct();
  }
  
  function get($id){
    $emaitza = parent::get($id);
    if($emaitza){
        // ariketak lortu
		$sql = "SELECT a.id as id
				FROM ikasgaiak_ariketak ai LEFT JOIN ariketak a ON ai.fk_ariketa = a.id
				WHERE ai.fk_ikasgaia = " . $id;
		$ariketak = get_query($sql);
		$emaitza->ariketak = $ariketak;
        
        // emaitzak lortu
        $sql = "SELECT *
                FROM ariketa_emaitza
                WHERE fk_ikasgaia = " . $id . "
                ORDER BY data DESC"
                ;
        $emaitzak = get_query($sql);
        for($i=0;$i<count($emaitzak);$i++){
            // lortu zuzen eta okerrak
            $sql = "SELECT *
                    FROM ariketa_emaitza_zuzenak
                    WHERE fk_ariketa_emaitza =".$emaitzak[$i]['id'];
            $zuzenak = get_query($sql);
            $emaitzak[$i]['zuzenak'] = count($zuzenak);
            $sql = "SELECT *
                    FROM ariketa_emaitza_okerrak
                    WHERE fk_ariketa_emaitza =".$emaitzak[$i]['id'];
            $okerrak = get_query($sql);
            $emaitzak[$i]['okerrak'] = count($okerrak);
        }
        $emaitza->emaitzak = $emaitzak;
    }
    return $emaitza;
  }
  
  
  function get_zerrenda($paginazio_datuak = ''){
        $sql = "SELECT A.id, A.hasiera_data, A.bukaera_data, A.fk_ikasgela, B.izenburua
                FROM ikasgaiak AS A
                INNER JOIN ikasgaiak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
                ORDER BY B.izenburua ASC";
        
        $orrikapena = orrikapen_datuak ($sql, $paginazio_datuak);
        
        $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
        return get_query($sql);
      
   }
  
  
    
}

?>