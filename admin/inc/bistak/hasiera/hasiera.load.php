<?php
    
    $url_base = URL_BASE_ADMIN . "hasiera/";
    
    
    
    $menu_aktibo = "hasiera";
    
    $p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
    
    $hurrengoa = $url->hurrengoa();
    
    //------------------------------
    // Define custom GCM function
    //------------------------------
    
    function sendGoogleCloudMessage( $data, $ids )
    {
        //------------------------------
        // Replace with real GCM API 
        // key from Google APIs Console
        // 
        // https://code.google.com/apis/console/
        //------------------------------
    
        $apiKey = 'AIzaSyD-g8xPeGYkNbUSFeAzErG4LBOzIawKWJo';
    
        //------------------------------
        // Define URL to GCM endpoint
        //------------------------------
    
        $url = 'https://android.googleapis.com/gcm/send';
    
        //------------------------------
        // Set GCM post variables
        // (Device IDs and push payload)
        //------------------------------
    
        $post = array(
                        'registration_ids'  => $ids,
                        'data'              => $data,
                        );
    
        //------------------------------
        // Set CURL request headers
        // (Authentication and type)
        //------------------------------
    
        $headers = array( 
                            'Authorization: key=' . $apiKey,
                            'Content-Type: application/json'
                        );
    
        //------------------------------
        // Initialize curl handle
        //------------------------------
    
        $ch = curl_init();
    
        //------------------------------
        // Set URL to GCM endpoint
        //------------------------------
    
        curl_setopt( $ch, CURLOPT_URL, $url );
    
        //------------------------------
        // Set request method to POST
        //------------------------------
    
        curl_setopt( $ch, CURLOPT_POST, true );
    
        //------------------------------
        // Set our custom headers
        //------------------------------
    
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
    
        //------------------------------
        // Get the response back as 
        // string instead of printing it
        //------------------------------
    
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    
        //------------------------------
        // Set post data as JSON
        //------------------------------
    
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $post ) );
    
        //------------------------------
        // Actually send the push!
        //------------------------------
    
        $result = curl_exec( $ch );
    
        //------------------------------
        // Error? Display it!
        //------------------------------
    
        if ( curl_errno( $ch ) )
        {
            echo 'GCM error: ' . curl_error( $ch );
        }
    
        //------------------------------
        // Close curl handle
        //------------------------------
    
        curl_close( $ch );
    
        //------------------------------
        // Debug GCM response
        //------------------------------
    
        echo $result;
    }
    
    if ($_POST["alerta"]) {
        
        //------------------------------
        // Payload data you want to send 
        // to Android device (will be
        // accessible via intent extras)
        //------------------------------
        
        $data = array( 'message' => 'Ariketa berri bat dago!', 'id_elementua' => '1704' );
        
        //------------------------------
        // The recipient registration IDs
        // that will receive the push
        // (Should be stored in your DB)
        // 
        // Read about it here:
        // http://developer.android.com/google/gcm/
        //------------------------------
        
        $sql = "SELECT mota, id_gailua
                FROM alerta_eskaerak";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        $id_ak = array();
        
        while($row = $dbo->emaitza()) {
            
            $id_ak[] = $row["id_gailua"];
        
        }
        
        //------------------------------
        // Call our custom GCM function
        //------------------------------
        
        sendGoogleCloudMessage( $data, $id_ak );
        
    }
    
    // irakaslea bada, bere informazioa lortu
    if($erabiltzailea->get_rola() == 'irakaslea'){
        
        // ikasgleak
        $sql = "SELECT *
                FROM ikasgelak
                WHERE fk_irakaslea = ".$erabiltzailea->get_fk_irakaslea();   
        
        $ikasgelak = get_query($sql);
        
        // bere ikasgeletan dauden ikasleak
        
        // ariketak
         $sql = "SELECT A.id, B.izena, A.egoera, A.fk_ariketa_mota
            FROM ariketak AS A
            INNER JOIN ariketak_hizkuntzak AS B
            ON A.id = B.fk_elem
            INNER JOIN erregistroa AS E
            ON B.fk_elem = E.fk_elementua AND E.elementu_mota = 'ariketa'
            WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
            AND E.fk_sortze_erabiltzailea = ".$erabiltzailea->get_id()."
            ORDER BY B.izena ASC";
        $ariketak = get_query($sql);
    
        
    }
  
    
    $sql = "SELECT A.id, A.hasiera_data, A.bukaera_data, A.fk_ikasgela, B.izenburua
            FROM ikasgaiak AS A
            INNER JOIN ikasgaiak_hizkuntzak AS B
            ON A.id = B.fk_elem
            WHERE B.fk_hizkuntza = " . $hizkuntza["id"] . "
            ORDER BY B.izenburua ASC";
    
    $orrikapena = orrikapen_datuak ($sql, $p);
    $sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];
    
    $elementuak = get_query($sql);
    
    $content = "inc/bistak/hasiera/hasiera.php";
        
    
?>