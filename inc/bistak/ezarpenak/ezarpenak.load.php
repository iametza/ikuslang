<?php
    
    $menu_aktibo = "ezarpenak";
    
    // Ikasleak bere datuak aldatu baditu.
    if (isset($_POST["gorde"])) {
        
        $id_ikaslea = $erabiltzailea->get_id();
        
        $izena = isset($_POST["izena"]) ? testu_formatua_sql($_POST["izena"]) : "";
        $abizenak = isset($_POST["abizenak"]) ? testu_formatua_sql($_POST["abizenak"]) : "";
        $e_posta = isset($_POST["e_posta"]) ? testu_formatua_sql($_POST["e_posta"]) : "";
        $pasahitza = isset($_POST["pasahitza"]) ? testu_formatua_sql($_POST["pasahitza"]) : "";
        $pasahitza2 = isset($_POST["pasahitza2"]) ? testu_formatua_sql($_POST["pasahitza2"]) : "";
        
        // pasahitza aldatzea hautazkoa da.
        if ($pasahitza != "" && $pasahitza2 != "" && $pasahitza == $pasahitza2) {
            
            // Ausazko gatz berri bat sortuko dugu.
            $gatza = uniqid(mt_rand(), true); // openssl_random_pseudo_bytes() ez dago;
            
            // Pasahitzaren hash-a sortu gatza erabiliz.
            $pasahitza = hash("sha256", $gatza . $pasahitza);
            
            $sql = "UPDATE ikasleak
                    SET izena = '$izena', abizenak = '$abizenak', e_posta = '$e_posta', gatza = '$gatza', pasahitza = '$pasahitza'
                    WHERE id = $id_ikaslea";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
        // Pasahitza hutsik badago, ez dugu gorde behar.
        } else if ($pasahitza == "" && $pasahitza2 == "") {
            
            $sql = "UPDATE ikasleak
                    SET izena = '$izena', abizenak = '$abizenak', e_posta = '$e_posta'
                    WHERE id = $id_ikaslea";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
        } else {
            
            // JavaScript bidez egiaztatzen dut baina ondo legoke PHP bidezko egiaztapena pasatzen ez badu erabiltzaileari jakinaraztea
            // eta gainerako eremuak beteta agertzea.
            // Berriz ere formulariora bideratuko dugu baina eremuak hutsik agertuko dira.
            header("Location: " . $url_base . "ezarpenak");
            exit();
            
        }
        
    }
    
    $sql = "SELECT id, izena, abizenak, e_posta
            FROM ikasleak
            WHERE id = " . $erabiltzailea->get_id();
        
    $dbo->query($sql) or die($dbo->ShowError());
    
    if ($dbo->emaitza_kopurua() == 1) {
        
        $row = $dbo->emaitza();
        
        $ikaslea = new stdClass();
        
        $ikaslea->id = $row["id"];
        $ikaslea->izena = $row["izena"];
        $ikaslea->abizenak = $row["abizenak"];
        $ikaslea->e_posta = $row["e_posta"];
        
    }
    
    $content = "inc/bistak/ezarpenak/ezarpenak.php";
?>