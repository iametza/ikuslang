<?php
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    if ($_POST) {
        
        $e_posta = isset($_POST["e-posta"]) ? testu_formatua_sql($_POST["e-posta"]) : "";
        
        // Ausazko gatz bat sortuko dugu.
        $gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
        
        // Ausazko pasahitz bat sortuko dugu.
        $pasahitza = generateRandomString();
        
        $gaia = "Ikuslang-eko pasahitza aldatzeko eskaera";
        
        $e_posta_mezua = "
        <html>
            <head>
                <title>Ikuslang-eko pasahitza aldatzea</title>
            </head>
            <body>
                <p>Erabili e-posta eta pasahitz hauek Ikuslang-en saioa hasteko:</p>
                <p>Erabiltzailea: $e_posta</p>
                <p>Pasahitza: $pasahitza</p>
            </body>
        </html>
        ";
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        
        // E-posta bidali.
        mail($e_posta, $gaia, $e_posta_mezua, $headers);
        
        // Pasahitzaren hash-a sortu gatza erabiliz.
        $pasahitza = hash("sha256", $gatza . $pasahitza);
        
		$sql = "UPDATE ikasleak
                SET gatza = '$gatza', pasahitza = '$pasahitza'
                WHERE e_posta = '$e_posta'";
		
        if ($dbo->query($sql)){
            
            $mezua = "Pasahitz berria bidali dizugu " . $e_posta . " helbide elektronikora. <a href='" . URL_BASE . "'>Hasi saioa</a>";
            
		} else {
            
            $dbo->ShowError();
            
        }
        
	}
    
	require("templates/ahaztu.php");

?>