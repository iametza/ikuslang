<link type="text/css" href="<?php echo URL_BASE; ?>css/ariketak.css" rel="stylesheet" />

<h3><?php echo $multzokatu->izena; ?></h3>

<div id="azalpena">
    <?php echo $multzokatu->azalpena; ?>
</div>

<?php if (count($multzokatu->dokumentuak) > 0) { ?>
    
    <div id="ariketa-dokumentuak">
        <div>Ariketa honen dokumentuak:</div>
        <ul>
        <?php foreach ($multzokatu->dokumentuak as $dokumentua) { ?>
            <li><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></li>
        <?php } ?>
        </ul>
    </div>
    
<?php } ?>

<section class="col-md-4">
    <h3>Elementuak</h3>
    <ul id="jatorria" class="sortable list-group">
        <?php for ($i = 0; $i < count($multzokatu->elementuak); $i++) { ?>
        <li class='list-group-item' data-elementua='<?php echo $multzokatu->elementuak[$i]->id; ?>' data-taldea='<?php echo $multzokatu->elementuak[$i]->id_taldea; ?>'><?php echo $multzokatu->elementuak[$i]->izena; ?></li>
        <?php } ?>
    </ul>
</section>

<?php for ($i = 0; $i < count($multzokatu->taldeak); $i++) { ?>
<section class="col-md-4">
    <h3><?php echo $multzokatu->taldeak[$i]->izena; ?></h3>
    <ul id="helburua_<?php echo $multzokatu->taldeak[$i]->id ?>" class="sortable list-group helburua" data-taldea="<?php echo $multzokatu->taldeak[$i]->id ?>"></ul>
</section>
<?php } ?>

<div id="beheko-botoiak">
    <button id="berriz-hasi-botoia" class="btn">Berriz hasi</button>
    <button id="zuzendu-botoia" class="btn">Zuzendu</button>
</div>

<div id="emaitzak-modala" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                
                <div id="emaitzak-modala-goikoa"><strong>Emaitzak: <?php echo $hitzak_markatu->izena; ?></strong></div>
                
            </div>
            
            <div class="modal-body">    
                <span id="emaitzak-modala-emaitzak">
                    <span id="emaitzak-modala-zuzenak-kontainer">
                        <img id="emaitzak-modala-zuzenak-irudia" src="<?php echo URL_BASE; ?>img/galdera_erantzunak/zuzen.png">
                        <span id="emaitzak-modala-zuzenak"></span>
                    </span>
                    
                    <span id="emaitzak-modala-okerrak-kontainer">
                        <img id="emaitzak-modala-okerrak-irudia" src="<?php echo URL_BASE; ?>img/galdera_erantzunak/oker.png">
                        <span id="emaitzak-modala-okerrak"></span>
                    </span>
                </span>
            </div>
            
            <div class="modal-footer">
                <button id="emaitzak-modala-ados" type="button" class="btn btn-default">Ados</button>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
    .sortable {
        height: 403px;
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 4px;
    }
    
    #beheko-botoiak {
        text-align: center;
    }
    
    .erantzun-zuzena {
        color: #00FF00;
    }
    
    .erantzun-okerra {
        color: #FF0000;
    }
    
</style>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery-ui-1.11.1.custom.min.js"></script>
<script>
$(function() {
    
    $(".sortable").sortable({
        connectWith: ".sortable"
    });
    
    /*$("#egiaztatu-botoia").click(function() {
        
        // Helburuko zutabe bakoitza pasako dugu.
        $(".helburua").each(function() {
            
            // Taldearen id-a eskuratuko dugu.
            var id_taldea = $(this).attr("data-taldea");
            
            // Talde honetako elementu guztiak pasako ditugu.
            $(this).children("li").each(function() {
                
                // Elementuaren taldearen id-a eskuratuko dugu.
                var id_elementuaren_taldea = $(this).attr("data-taldea");
                
                // Elementua ez badago dagokion taldean.
                if (id_elementuaren_taldea !== id_taldea) {
                    
                    // Jatorrizko zerrendara eraman.
                    $("#jatorria").append($(this));
                }
            });
        });
    });*/
    
    $("#zuzendu-botoia").click(function() {
        
        var zuzenak = [];
        var okerrak = [];
        
        // Helburuko zutabe bakoitza pasako dugu.
        $(".helburua").each(function() {
            
            // Taldearen id-a eskuratuko dugu.
            var id_taldea = $(this).attr("data-taldea");
            
            // Talde honetako elementu guztiak pasako ditugu.
            $(this).children("li").each(function() {
                
                // Elementuaren id-a eskuratuko dugu.
                var id_elementua = $(this).attr("data-elementua");
                
                // Elementuaren taldearen id-a eskuratuko dugu.
                var id_elementuaren_taldea = $(this).attr("data-taldea");
                
                // Elementu hau ez badago dagoeneko zuzenduta.
                if (!$(this).attr("data-zuzenduta")) {
                    
                    // Elementua ez badago dagokion taldean.
                    if (id_elementuaren_taldea !== id_taldea) {
                        
                        // Elementua zuzenduta dagoela adieraziko dugu.
                        // Bestela dagokion zerrendara eraman ondoren bigarren aldiz kontatzeko arriskua dago.
                        $(this).attr("data-zuzenduta", true);
                        
                        // Dagokion zerrendara eramango dugu.
                        //$("#helburua_" + id_elementuaren_taldea).append($(this));
                        
                        $(this).append('<span class="glyphicon glyphicon-remove pull-right erantzun-okerra"></span>');
                        
                        // Okerren zerrendara gehituko dugu.
                        okerrak.push(id_elementua);
                        
                    } else {
                        
                        $(this).append('<span class="glyphicon glyphicon-ok pull-right erantzun-zuzena"></span>');
                        
                        // Zuzenen zerrendara gehituko dugu.
                        zuzenak.push(id_elementua);
                        
                    }
                    
                }
                
            });
            
        });
        
        // Jatorrizko zutabeko elementu guztiak pasako dugu.
        $("#jatorria").children("li").each(function() {
            
            // Elementuaren id-a eskuratuko dugu.
            var id_elementua = $(this).attr("data-elementua");
            
            // Elementuaren taldearen id-a eskuratuko dugu.
            var id_elementuaren_taldea = $(this).attr("data-taldea");
            
            // Dagokion zerrendara eramango dugu.
            //$("#helburua_" + id_elementuaren_taldea).append($(this));
            
            $(this).append('<span class="glyphicon glyphicon-remove pull-right erantzun-okerra"></span>');
            
            // Okerren zerrendara gehituko dugu.
            okerrak.push(id_elementua);
            
        });
        
        //alert("Zuzenak: " + zuzenak.length + " - Okerrak: " + okerrak.length);
        
        $.post("<?php echo URL_BASE; ?>API/v1/multzokatu",
            {
                "id_ariketa": <?php echo $id_ariketa; ?>,
                "id_ikasgaia": <?php echo $id_ikasgaia; ?>,
                "id_ikaslea": <?php echo $erabiltzailea->get_id(); ?>,
                "zuzenak": zuzenak,
                "okerrak": okerrak
            }
        )
        .done(function(data) {
            
            $("#emaitzak-modala-zuzenak").text(zuzenak.length);
            $("#emaitzak-modala-okerrak").text(okerrak.length);
            
            $("#emaitzak-modala").modal("show", {
                backdrop: "static"
            });
            
        })
        .fail(function() {
        });
        
        console.log(zuzenak);
        console.log(okerrak);
        
    });
    
    $("#berriz-hasi-botoia").click(function() {
        
        $(".helburua").each(function() {
            
            // Helburuko zutabeetan dauden elementu guztiak pasako ditugu.
            $(this).children("li").each(function() {
                
                // Zuzena ala okerra den adierazten duen ikonoa kendu.
                $(this).children().remove();
                
                // data-zuzenduta atributua hasieratu.
                $(this).attr("data-zuzenduta", "");
                
                // Hasierako zutabera gehitu.
                $("#jatorria").append($(this));
                
            });
        });
        
        // Helburuko zutabeetan dauden elementu guztiak pasako ditugu.
        $("#jatorria").children("li").each(function() {
            
            // Zuzena ala okerra den adierazten duen ikonoa kendu.
            $(this).children().remove();
            
            // data-zuzenduta atributua hasieratu.
            $(this).attr("data-zuzenduta", "");
            
        });
        
    });
    
    $("#emaitzak-modala-ados").click(function() {
        
        $("#emaitzak-modala").modal("hide");
        
    });
    
});
</script>