<div class="row">
    
    <div class="col-md-8">
        
        <h3>Ezarpenak</h3>
        
        <form id="f1" name="f1" method="post" action="<?php echo $url_base . "ezarpenak"; ?>" class="form-horizontal" role="form" enctype="multipart/form-data" onsubmit="javascript: return verif();">
            <input type="hidden" name="gorde" value="BAI" />
            
            <fieldset>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="izena">Izena:</label>
                    <div class="col-xs-8">
                        <input type="text" id="izena" name="izena" value="<?php echo testu_formatua_input ($ikaslea->izena); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="abizenak">Abizenak:</label>
                    <div class="col-xs-8">
                        <input class="input-xxlarge" type="text" id="abizenak" name="abizenak" value="<?php echo testu_formatua_input ($ikaslea->abizenak); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="e_posta">e-posta:</label>
                    <div class="col-xs-8">
                        <input class="input-xxlarge" type="text" id="e_posta" name="e_posta" value="<?php echo testu_formatua_input ($ikaslea->e_posta); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="pasahitza">Aldatu pasahitza:</label>
                    <div class="col-xs-8">
                        <input class="input-xxlarge" type="password" id="pasahitza" name="pasahitza" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="pasahitza2">Berretsi pasahitza:</label>
                    <div class="col-xs-8">
                        <input class="input-xxlarge" type="password" id="pasahitza2" name="pasahitza2" value="" />
                    </div>
                </div>
            </fieldset>
            
            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-8">
                    <button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
                    <button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
                </div>
            </div>
        </form>
        <div class="hutsa"></div>
        
    </div>
    
    <div class="col-md-4">&nbsp;</div>

</div>