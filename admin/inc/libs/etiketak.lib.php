<?php

class Etiketak {
    
    // Elementu bati dagozkion "elementuak"_etiketak taulako errenkadak ezabatzen ditu.
    // Ez ditu etiketak ezabatzen.
    // Hizkuntza guztietakoak kentzen ditu.
    public static function kenduElementuarenEtiketak($dbo, $id_elementua, $taula) {
        
        $sql = "DELETE FROM $taula
                WHERE fk_elementua = $id_elementua";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
    }
    
    
    
    // Elementu bati dagozkion etiketak gordetzen ditu "elementuak"_etiketak taulan.
    // Etiketa ez bada existitzen sortu egiten du (etiketak taulan).
    // tagsManager-en ezkutuko inputaren komaz banatutako etiketak hartzen ditu sarrera bezala.
    public static function gordeElementuarenEtiketak($dbo, $id_elementua, $id_hizkuntza, $etiketak, $taula) {
        
        // Etiketen arraya prestatu explode erabiliz.
        // Hutsik dauden etiketak kenduko ditugu arraytik array_filter erabiliz.
        $etiketak = array_filter(explode(',', $etiketak));
        
        foreach ($etiketak as $etiketa) {
            
            // Etiketa dagoeneko existitzen den begiratu, hala bada bere id_etiketa eskuratu.
            $sql = "SELECT id
                    FROM etiketak
                    WHERE izena = '$etiketa' AND fk_hizkuntza = $id_hizkuntza";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
            // Etiketa ez bada existitzen sortu egingo dugu.
            if ($dbo->emaitza_kopurua() == 0) {
                
                $sql = "INSERT INTO etiketak (izena, fk_hizkuntza)
                        VALUES ('$etiketa', $id_hizkuntza)";
                
                $dbo->query($sql) or die($dbo->ShowError());
                
                // Etiketaren id-a eskuratuko dugu.
                $id_etiketa = db_taula_azken_id("etiketak");
                
            // Existitzen bada bere id-a eskuratuko dugu.
            } else {
                
                $emaitza = $dbo->emaitza();
                $id_etiketa = $emaitza['id'];
                
            }
            
            $sql = "INSERT INTO $taula (fk_etiketa, fk_elementua)
                    VALUES ('$id_etiketa', '$id_elementua')";
            
            $dbo->query($sql) or die($dbo->ShowError());
            
        }
    }
    
   
}

?>