<link type="text/css" href="<?php echo URL_BASE; ?>css/ariketak.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo URL_BASE; ?>css/ordenatu_esaldiak/jMyPuzzle.css" />
	
<h2><?php echo $esaldiak_zuzendu->izena; ?></h2>

<div id="azalpena">
    <?php echo $esaldiak_zuzendu->azalpena; ?>
</div>

<?php if (count($esaldiak_zuzendu->dokumentuak) > 0) { ?>
    
    <div id="ariketa-dokumentuak">
        <div>Ariketa honen dokumentuak:</div>
        <ul>
        <?php foreach ($esaldiak_zuzendu->dokumentuak as $dokumentua) { ?>
            <li><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></li>
        <?php } ?>
        </ul>
    </div>
    
<?php } ?>

<div id="esaldiak-zuzendu-markagailua">
    <span id="zenbagarrena">
        <span id="unekoa">1</span>/<span id="guztira">4</span>
    </span>
    
    <span id="emaitzak">
        <span id="zuzenak_span">
            <img src="<?php echo URL_BASE; ?>/img/galdera_erantzunak/zuzen.png" id="zuzenak_img">
            <span id="zuzenak">0</span>
        </span>
        
        <span id="okerrak_span">
            <img src="<?php echo URL_BASE; ?>/img/galdera_erantzunak/oker.png" id="okerrak_img">
            <span id="okerrak">0</span>
        </span>
    </span>
</div>
<div class="jMyPuzzle"></div>

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

<script src="<?php echo URL_BASE; ?>js/jquery-ui-1.11.1.custom.min.js"></script>
<script src="<?php echo URL_BASE; ?>js/ordenatu_esaldiak/jMyPuzzle.iametza.js"></script>

<script>
    
    var esaldien_idak = <?php echo $esaldiak_zuzendu->esaldien_idak; ?>;
    var esaldiak = <?php echo $esaldiak_zuzendu->esaldiak; ?>;
    var ordenak = <?php echo $esaldiak_zuzendu->ordenak; ?>;
    
    var zenbagarren_esaldia = 0;
    
    // Esaldiak zein ordenatan erakutsi behar diren.
    var esaldien_ordena = [];
    
    var zuzen_kop = 0;
    var oker_kop = 0;
    var zuzen_idak = [];
    var oker_idak = [];
    
    // esaldiak arrayak dituen elementuak adina elementu gehituko ditugu array berrira.
    for (var i = 0; i < esaldiak.length; i++) {
        
        esaldien_ordena.push(i);
        
    }
    
    // Array berria desordenatuko dugu.
    esaldien_ordena = shuffle(esaldien_ordena);
    
    function bistaratu_zenbagarrena() {
        
        $("#unekoa").text(zenbagarren_esaldia + 1);
    }
    
    function bistaratu_zuzen_kopurua() {
        
        $("#zuzenak").text(zuzen_kop);
    }
    
    function bistaratu_oker_kopurua() {
        
        $("#okerrak").text(oker_kop);
        
    }
    
    function bistaratu_galdera_kopurua() {
        
        $("#guztira").text(esaldiak.length);
    }
    
    function shuffle(array) {
        
        var counter = array.length, temp, index;
        
        // While there are elements in the array
        while (counter > 0) {
            
            // Pick a random index
            index = Math.floor(Math.random() * counter);
            
            // Decrease counter by 1
            counter--;
            
            // And swap the last element with it
            temp = array[counter];
            array[counter] = array[index];
            array[index] = temp;
            
        }
        
        return array;
        
    }

    function bistaratuEsaldia() {
        
        $(".jMyPuzzle").html("");
        
        $(".jMyPuzzle").jMyPuzzle({
            phrase: esaldiak[esaldien_ordena[zenbagarren_esaldia]],
            answers: ordenak[esaldien_ordena[zenbagarren_esaldia]], //ordena_zuzenak,
            //phrase: ["a", "b", "c"],
            //answers: [[0, 1, 2], [2, 1, 0]],
            language: "eu",
            maxTrials: 1,
            showTrials: false,
            visible: '100%', // ez da erabiltzen ????
            fnOnCheck: function(jSonResults){  
                /*alert("Estatistikak:"
                      + "\n\tErantzun zuzenak: " + jSonResults.nb_valid
                      + "\n\tErantzun okerrak: " + jSonResults.nb_not_valid
                      + "\n\tErantzun erdi-zuzenak: " + jSonResults.nb_mi_valid
                      + "\n\tPortzentaia: %" + jSonResults.success_rate);*/
                
                if (jSonResults.nb_not_valid === 0) {
                    
                    zuzen_kop++;
                    
                    zuzen_idak.push(esaldien_idak[esaldien_ordena[zenbagarren_esaldia]]);
                    
                } else {
                    
                    oker_kop++;
                    
                    oker_idak.push(esaldien_idak[esaldien_ordena[zenbagarren_esaldia]]);
                    
                }
                
                bistaratu_zuzen_kopurua();
                
                bistaratu_oker_kopurua();
                
                zenbagarren_esaldia++;
                
                // Erabiltzaileari ordena aldatzen ez utzi.
                $("#parts li").draggable("disable");
                
                // Zuzendu eta berrezarri botoiak kendu.
                $(".jMyPuzzle input").remove();
                
                if (zenbagarren_esaldia < esaldiak.length) {
                    
                    // Aurrera botoia gehitu.
                    $(".jMyPuzzle").append("<input type='button' value='Aurrera' id='aurrera-botoia' />");
                    
                } else {
                    
                    console.log(zuzen_idak);
                    console.log(oker_idak);
                    
                    $.post("<?php echo URL_BASE; ?>API/v1/esaldiak-ordenatu", {
                            "id_ariketa": <?php echo $id_ariketa; ?>,
                            "id_ikasgaia": <?php echo $id_ikasgaia; ?>,
                            "id_ikaslea": <?php echo $erabiltzailea->get_id(); ?>,
                            "zuzenak": zuzen_idak,
                            "okerrak": oker_idak
                        }
                    )
                    .done(function(data) {
                        
                        $("#emaitzak-modala-zuzenak").text(zuzen_kop);
                        $("#emaitzak-modala-okerrak").text(oker_kop);
                        
                        $("#emaitzak-modala").modal("show", {
                            backdrop: "static"
                        });
                        
                        // Berriz hasi botoia gehitu.
                        $(".jMyPuzzle").append("<input type='button' value='Berriz hasi' id='berriz-hasi-botoia' />");
                        
                    })
                    .fail(function() {
                    });
                    
                }
            }
        });
        
    }
    
    $(document).ready(function() {
        
        bistaratuEsaldia();
        
        bistaratu_zuzen_kopurua();
        
        bistaratu_oker_kopurua();
        
        bistaratu_zenbagarrena();
        
        bistaratu_galdera_kopurua();
        
        $(document).on("click", ".jMyPuzzle #aurrera-botoia", function() {
            
            bistaratu_zenbagarrena();
            
            bistaratuEsaldia();
            
        });
        
        $(document).on("click", ".jMyPuzzle #berriz-hasi-botoia", function() {
            
            // Aldagaiak zeroratuko ditugu.
            zuzen_kop = 0;
            oker_kop = 0;
            zenbagarren_esaldia = 0;
            
            // Arraya berriz desordenatuko dugu.
            esaldien_ordena = shuffle(esaldien_ordena);
            
            bistaratuEsaldia();
            
            bistaratu_zuzen_kopurua();
            
            bistaratu_oker_kopurua();
            
            bistaratu_zenbagarrena();
            
            bistaratu_galdera_kopurua();
            
        });
        
        $("#emaitzak-modala-ados").click(function() {
            
            $("#emaitzak-modala").modal("hide");
            
        });
    });
</script>