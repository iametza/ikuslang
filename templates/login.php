<!DOCTYPE html>
<html lang="<?php echo $hizkuntza["gako"]; ?>">
<head>
	
<?php require ("templates/head.inc.php"); ?>

</head>

<body onload="javascript:document.getElementById('erabiltzailea').focus();">

    <div class="container">
        
        <?php require ("templates/burua_login.inc.php"); ?>
        
    </div>

    <div class="row">
        
        <div class="col-md-4">&nbsp;</div>
        
        <div class="col-md-4">
            <form id="f1" name="f1" method="post" action="<?php echo URL_BASE; ?>" class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="e-posta">E-posta</label>
                    <div class="col-xs-8">
                        <input type="text" id="e-posta" placeholder="E-posta" class="required" name="e-posta" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="pasahitza">Pasahitza</label>
                    <div class="col-xs-8">
                        <input type="password" id="pasahitza" placeholder="Pasahitza" class="required" name="pasahitza" />
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-offset-4 col-xs-8">
                        <button type="submit" class="btn">Sartu</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-offset-4 col-xs-8">
                        <a href="<?php echo URL_BASE . 'ahaztu'; ?>">Pasahitza ahaztu zait</a>
                    </div>
                </div>
                
            </form>
        </div>
        
        <div class="col-md-4">&nbsp;</div>
        
    </div>
    
    <?php //require ("templates/oina.inc.php"); ?>
    
</body>

</html>
