<h2><?php echo $hitzak_markatu->izena; ?></h2>
<div id="azalpena">
    <?php echo $hitzak_markatu->azalpena; ?>    
</div>

<?php if (count($hitzak_markatu->dokumentuak) > 0) { ?>
    
    <div id="ariketa-dokumentuak">
        <div>Ariketa honen dokumentuak:</div>
        <ul>
        <?php foreach ($hitzak_markatu->dokumentuak as $dokumentua) { ?>
            <li><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></li>
        <?php } ?>
        </ul>
    </div>
    
<?php } ?>

<div id="ikus-entzunezkoa-edukinontzia" class="col-md-6">
	
	<div id="jp_container_1" class="jp-video jp-video-270p">
		
		<div class="jp-type-single">
			
			<div id="jquery_jplayer_1" class="jp-jplayer"></div>
			
			<div class="jp-gui">
				
				<div class="jp-video-play">
					<a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
				</div>
				
				<div class="jp-interface">
                    
                    <div class="jp-controls-holder">
                        
                        <a href="javascript:;" class="jp-play btn btn-default" tabindex="1"><i class="glyphicon glyphicon-play"></i></a>
                        <a href="javascript:;" class="jp-pause btn btn-default" tabindex="1"><i class="glyphicon glyphicon-pause"></i></a>
                        
                    </div><!--end jp-controls-holder-->
                    
                    <div class="jp-aurrerapen-barra-edukinontzia">
                        
                        <div class="jp-progress">
                            
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar"></div>
                            </div>
                            
                        </div>
                        
                        <div class="jp-current-time"></div>
                        <div class="jp-duration"></div>
                        
                    </div>
                    
                </div><!--end jp-interface-->
				
			</div><!--end jp-gui-->
			
			<div id="bideoa-azpitituluak" class="bideoa-azpitituluak-behean"></div>
			
			<div class="jp-no-solution">
				<span>Beharrezkoa da eguneratzea</span>
				Edukiak erreproduzitzeko nabigatzailea edo <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugina</a> eguneratu behar dituzu.
			</div>
			
		</div><!--end jp-type-single-->
		
	</div><!--end jp_container_1-->
    
    <div id="zerrenda-edukinontzia-botoiak">
        <button id="hasi-berriz-botoia" class="btn">Berriz hasi</button>
        <button id="zuzendu-botoia" class="btn">Zuzendu</button>
        <!--<button id="erakutsi-erantzunak-botoia" class="btn">Erakutsi erantzunak</button>-->
    </div>
    
</div>

<div id="zerrenda-edukinontzia" class="col-md-6">
    <div id="hitz-ontzia"></div>
    <div id="transkribapena-edukia"></div>
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

<link type="text/css" href="<?php echo URL_BASE; ?>css/ariketak.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/jplayer-skin/iametza.minimalista/jplayer.iametza.minimalista.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/hitzak_markatu.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/bideotranskribapena.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery-ui-1.11.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.ui.touch-punch.min.js"></script>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/popcorn.js"></script>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/modules/player/popcorn.player.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/modules/parser/popcorn.parser.js"></script>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/plugins/code/popcorn.code.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/plugins/subtitle/popcorn.subtitle.js"></script>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/parsers/parserSRT/popcorn.parserSRT.js"></script>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/popcorn.transcript.js"></script>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/popcorn.jplayer.js"></script>

<script type="text/javascript">
	$(document).ready(function () {
	
		var pop = Popcorn.jplayer("#jquery_jplayer_1", {
			media: {
            <?php if ($hitzak_markatu->mota == "bideoa") { ?>
				m4v: "<?php echo URL_BASE . $hitzak_markatu->bideo_path . $hitzak_markatu->bideo_mp4; ?>",
				webmv: "<?php echo URL_BASE . $hitzak_markatu->bideo_path . $hitzak_markatu->bideo_webm; ?>"
            <?php } else if ($hitzak_markatu->mota == "audioa") { ?>
                mp3: "<?php echo URL_BASE . $hitzak_markatu->audio_path . $hitzak_markatu->audio_mp3; ?>",
                oga: "<?php echo URL_BASE . $hitzak_markatu->audio_path . $hitzak_markatu->audio_ogg; ?>"
            <?php } ?>
			},
			options: {
                solution: "html",
            <?php if ($hitzak_markatu->mota == "bideoa") { ?>
				supplied: "m4v, webmv",
            <?php } else if ($hitzak_markatu->mota == "audioa") { ?>
                supplied: "mp3, oga",
            <?php } ?>
                size: {width: "300px", height: "225px"}
			}
		});
		
		var dataMs = "data-ms";
        
		// Zertarako ziren hauek?
		var playSource = true;
		var tPause = 0;
		var endTime = null;
        
        var akatsak = <?php echo json_encode($hitzak_markatu->akatsak); ?>;
        
		function initTranscript(p) {
            
            var akats_kopurua = akatsak.length;
            var hitz_kopurua;
            
			//console.log("initTranscript in "+(new Date()-startTimer));
			$("#transkribapena-edukia span").each(function(i) {  
				// doing p.transcript on every word is a bit inefficient - wondering if there is a better way
				p.transcript({
					time: $(this).attr(dataMs) / 1000, // seconds
					futureClass: "transcript-grey",
					target: this,
					onNewPara: function(parent) {
						$("#transkribapena-edukia").stop().scrollTo($(parent), 800, {axis:'y',margin:true,offset:{top:0}});
					}
				});
                
			});
            
            for (var i = 0; i < akats_kopurua; i++) {
                
                hitz_kopurua = akatsak[i].hitzak.length;
                
                for (var j = 0; j < hitz_kopurua; j++) {
                    
                    $("span[data-ms='" + akatsak[i].hitzak[j].denbora + "']").text(akatsak[i].hitzak[j].okerra);
                    
                    $("span[data-ms='" + akatsak[i].hitzak[j].denbora + "']").attr("data-id-akatsa", akatsak[i].id);
                }
                
            }
		}
        
		// Azpitituluen fitxategia parseatu bistaratzeko.
		//pop.parseSRT("<?php echo URL_BASE . $hitzak_markatu->path_azpitituluak . $hitzak_markatu->azpitituluak; ?>", {target: "bideoa-azpitituluak"});
		
		// Hipertranskribapenaren testua bistaratu
		$('#transkribapena-edukia').html(<?php echo $hitzak_markatu->hipertranskribapena; ?>);
		
		// Hipertranskribapenaren oinarrizko funtzionalitatea hasieratu
        initTranscript(pop);
		
		// Erabiltzaileak transkribapeneko hitz bat klikatzen duenean.
		$('#transkribapena-edukia').delegate('span','click',function(e){ 
			playSource = true;
			tPause = 0;
			endTime = null;
			
			// Klikatutako hitza bideoko zein momenturi dagokion kalkulatu.
			var jumpTo = $(this).attr(dataMs) / 1000;
			
			// Dagokion momentuan hasi bideoa erreproduzitzen.
			pop.play(jumpTo);
			
			//_gaq.push(['_trackEvent', 'USElect', 'Word clicked', 'word '+$(this).text()]);
			
			return false;
		});
		
        $("#transkribapena-edukia span").draggable({
            
            revert: "invalid",
            helper: "clone",
            drag: function(event, ui) {
                ui.helper.removeClass("transcript-grey");
                ui.helper.addClass("hitz-ontzia-spana");
            }
            
        });
        
        $("#hitz-ontzia").droppable({
            
            accept: "#transkribapena-edukia span",
            drop: function(event, ui) {
                
                var spana = $(ui.draggable).clone();
                
                var zerrendan = false;
                
                $("#hitz-ontzia .hitz-ontzia-spana").each(function() {
                    
                    // Zerrendako elementuaren denborak bat badatoz hautapenarekin.
					if ($(this).attr("data-ms") == spana.attr("data-ms")) {
                        
						zerrendan = true;
                        
					}
                    
				});
                
                if (!zerrendan) {
                    
                    // Puntuazio karaktereak kenduko dizkiogu hitzari.
                    while(/[^a-zA-Z0-9]/.test(spana.text().charAt(spana.text().length - 1))) {
                        
                        spana.text(spana.text().substring(0, spana.text().length - 1));
                        
                    }
                    
                    spana.removeClass("transcript-grey");
                    spana.addClass("hitz-ontzia-spana");
                    
                    spana.append("<span class='hitz-ontzia-spana-x'>x</span>");
                    
                    // Hitzik ez badago...
                    if ($("#hitz-ontzia .hitz-ontzia-spana").length === 0) {
                        
                        $(this).append(spana);
                        
                    } else {
                        
                        // Hitz guztiak banan bana pasako ditugu hitz berria non txertatu erabakitzeko.
                        $("#hitz-ontzia .hitz-ontzia-spana").each(function() {
                            
                            // Hitz berria uneko hitza baino lehenago bada...
                            if (parseInt($(this).attr("data-ms"), 10) > parseInt(spana.attr("data-ms"), 10)) {
                                
                                // Uneko hitzaren aurretik txertatuko dugu.
                                $(this).before(spana);
                                
                                return false;
                            }
                            
                            // Azken hitzean bagaude eta oraindik ez badugu hitz berria txertatu, azkenaren ondoren txertatuko dugu
                            $(this).after(spana);
                            
                        });
                        
                    }
                    
                } else {
                    
                    alert("Hautapena dagoeneko zerrendan dago!");
                    
                }
                
            }
            
        });
        
        $(document).on("click", ".hitz-ontzia-spana-x", function() {
            
            // Hautatutako elementua zerrendatik kendu.
			$(this).parent().remove();
            
        });
		
		$("#zuzendu-botoia").click(function() {
			
            // Momentuko erantzuna zuzena den ala ez adierazten du.
            var zuzena_da = false;
            
            var akats_kopurua = akatsak.length;
            
			var zuzenak = [];
			var okerrak = [];
			
            // Erabiltzaileak zerrendara gehitutako erantzun zuzen eta okerren hitzen denborak, hipertranskribapenean nabarmentzeko.
            var zuzenen_denborak = [];
			var okerren_denborak = [];
            
            // Elementuaren id-a eskuratuko dugu.
            var id_akatsa;
            
            // Ikus-entzunezkoa gelditu.
            pop.pause();
            
            $("#hitz-ontzia .hitz-ontzia-spana").each(function() {
                
                // Erantzunaren denborak eskuratuko ditugu.
                var denborak = [$(this).attr("data-ms")];
                
                // Kontrakoa frogatu bitartean erantzuna okerra da:
                zuzena_da = false;
                
                // Erantzuna zuzena den ala ez ikusteko akats guztiak pasako ditugu banan bana.
                for (var i = 0; i < akats_kopurua; i++) {
                    
                    // Erantzunaren hitzen denborak akatsekoekin bat datozen begiratuko dugu.
                    for (var j = 0; j < akatsak[i].hitzak.length; j++) {
                        
                        if (akatsak[i].hitzak[j].denbora == denborak[j]) {
                            
                            zuzena_da = true;
                            
                        } else {
                            
                            // Erantzuneko denbora bat ez datorrenez bat ez dago egiaztatzen jarraitu beharrik.
                            break;
                            
                        }
                        
                    }
                    
                    if (zuzena_da) {
                        
                        // Erantzuna zuzena denez begiztatik atera gaitezke.
                        break;
                        
                    }
                    
                }
                
                if (zuzena_da) {
                    
                    // Erantzun zuzenaren estiloa eman zerrendako elementu honi.
                    $(this).addClass("zerrenda-erantzun-zuzena");
                    
                    // Hitzen denborak zuzenen zerrendan gordeko ditugu.
                    zuzenen_denborak = zuzenen_denborak.concat(denborak);
                    
                    id_akatsa = $(this).attr("data-id-akatsa");
                    
                    zuzenak.push(id_akatsa);
                    
                } else {
                    
                    // Erantzun okerraren estiloa eman zerrendako elementu honi.
                    $(this).addClass("zerrenda-erantzun-okerra");
                    
                    // Hitzen denborak okerren zerrendan gordeko ditugu.
                    okerren_denborak = okerren_denborak.concat(denborak);
                    
                }
                
            });
            
            // zuzenen_denborak: Erabiltzaileak aurkitutako akatsen denboren zerrenda -> berdez.
            // okerren_denborak: Erabiltzaileak zerrendara gehitu dituen baina akatsak ez ziren hitzen zerrenda. -> Oraingoz ez dugu erabiliko.
            // akatsak: Aurkitu beharreko akats guztiak biltzen dituen objektuen arraya.
            
            zuzen_kopurua = zuzenen_denborak.length;
            
            // Zerrendara gehitutako akatsei (asmatutakoak) dagokien estiloa emango diegu transkribapenean.
            for (var i = 0; i < zuzen_kopurua; i++) {
                
                // Erantzun zuzenaren estiloa eman hipertranskribapeneko elementu honi.
                $("#transkribapena-edukia span[data-ms='" + zuzenen_denborak[i] + "']").addClass("hipertranskribapena-erantzun-zuzena");
                
            }
            
            // Erabiltzaileak aurkitu ez dituen akatsak aurkitu behar ditugu.
            for (var i = 0; i < akats_kopurua; i++) {
                
                hitz_kopurua = akatsak[i].hitzak.length;
                
                for (var j = 0; j < hitz_kopurua; j++) {
                    
                    // Dagoeneko erantzun zuzenaren estiloa eman ez badiogu erantzun okerraren estiloa eman hipertranskribapeneko elementu honi.
                    if (!$("#transkribapena-edukia span[data-ms='" + akatsak[i].hitzak[j].denbora + "']").hasClass("hipertranskribapena-erantzun-zuzena")) {
                        
                        // Akatsaren lehen hitza bada okerren zerrendara gehituko dugu bere id-a.
                        if (j === 0) {
                            
                            id_akatsa = $("#transkribapena-edukia span[data-ms='" + akatsak[i].hitzak[j].denbora + "']").attr("data-id-akatsa");
                            
                            // Erantzun okerraren estiloa eman hipertranskribapeneko elementu honi.
                            $("#transkribapena-edukia span[data-ms='" + akatsak[i].hitzak[j].denbora + "']").addClass("hipertranskribapena-erantzun-okerra");
                            
                            okerrak.push(id_akatsa);
                            
                        }
                        
                    }
                    
                }
                
            }
            
			//alert("Emaitza: " + zuzenak.length + "/" + akatsak.length);
            
            console.log(zuzenak);
            console.log(okerrak);
            
            $.post("<?php echo URL_BASE; ?>API/v1/hitzak-markatu", {
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
            
		});
		
        $("#hasi-berriz-botoia").click(function() {
            
            // Hitz ontzia hustu.
            $("#hitz-ontzia").empty();
            
            // Hipertranskribapeneko hitzei klaseak kendu.
            $("#transkribapena-edukia span").each(function(i) {
                $(this).removeClass("hipertranskribapena-erantzun-okerra").removeClass("hipertranskribapena-erantzun-zuzena");
            })
            
            $("#emaitzak-modala").modal("hide");
            
            $("#zuzendu-botoia").prop('disabled', false);
            
            // Ikus-entzunezkoa hasierara eraman.
            pop.currentTime(0);
            
        });
        
        $("#emaitzak-modala-ados").click(function() {
            
            $("#emaitzak-modala").modal("hide");
            
            $("#zuzendu-botoia").prop('disabled', true);
            
        });
	});
</script>