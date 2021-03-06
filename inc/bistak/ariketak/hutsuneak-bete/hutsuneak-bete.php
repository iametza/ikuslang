<h3><?php echo $hutsuneak_bete->izena; ?></h3>

<div id="azalpena">
    <?php echo $hutsuneak_bete->azalpena; ?>    
</div>

<?php if (count($hutsuneak_bete->dokumentuak) > 0) { ?>
    
    <div id="ariketa-dokumentuak">
        <div>Ariketa honen dokumentuak:</div>
        <ul>
        <?php foreach ($hutsuneak_bete->dokumentuak as $dokumentua) { ?>
            <li><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></li>
        <?php } ?>
        </ul>
    </div>
    
<?php } ?>

<div id="transkribapena-edukinontzia">
	
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
	
	<div id="transkribapena-edukia"></div>
</div>

<div id="beheko-botoiak">
    <button id="berriz-hasi-botoia" class="btn">Berriz hasi</button>
    <!--<button id="egiaztatu-botoia" class="btn">Egiaztatu</button>-->
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

<link type="text/css" href="<?php echo URL_BASE; ?>css/ariketak.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/jplayer-skin/iametza.minimalista/jplayer.iametza.minimalista.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/bideotranskribapena.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/hutsuneak_bete.css" rel="stylesheet" />

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
            <?php if ($hutsuneak_bete->mota == "bideoa") { ?>
				m4v: "<?php echo URL_BASE . $hutsuneak_bete->bideo_path . $hutsuneak_bete->bideo_mp4; ?>",
				webmv: "<?php echo URL_BASE . $hutsuneak_bete->bideo_path . $hutsuneak_bete->bideo_webm; ?>"
            <?php } else if ($hutsuneak_bete->mota == "audioa") { ?>
                mp3: "<?php echo URL_BASE . $hutsuneak_bete->audio_path . $hutsuneak_bete->audio_mp3; ?>",
                oga: "<?php echo URL_BASE . $hutsuneak_bete->audio_path . $hutsuneak_bete->audio_ogg; ?>"
            <?php } ?>
			},
			options: {
                solution: "html",
				<?php if ($hutsuneak_bete->mota == "bideoa") { ?>
                    supplied: "m4v, webmv",
                <?php } else if ($hutsuneak_bete->mota == "audioa") { ?>
                    supplied: "mp3, oga",
                <?php } ?>
                size: {width: "300px", height: "168.5px"}
			}
		});
		
		var dataMs = "data-ms";
        
		// Zertarako ziren hauek?
		var playSource = true;
		var tPause = 0;
		var endTime = null;
        
		function initTranscript(p) {
			
            // Hutsuneak ze data-ms-tan jarri behar diren.
            // Hutsuneak hitz bat baino gehiagokoak izan daitezke.
            var hutsuneak = <?php echo json_encode($hutsuneak_bete->hutsuneak); ?>;
            
            var hutsune_kopurua = hutsuneak.length;
            var hitz_kopurua;
            var hutsunearen_testua = "";
            var $spana;
            
            // Hutsunearen testuari kendutako !az karaktereak
            var kendutako_karaktereak = "";
            
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
			
            // Hutsuneak gehitu dagokion lekuan.
            for (var i = 0; i < hutsune_kopurua; i++) {
                
                hitz_kopurua = hutsuneak[i].hitzak.length;
                
                hutsunearen_testua = "";
                
                kendutako_karaktereak = "";
                
                // Hitz bat baino gehiagoko hutsueneen kasuan bakarrik sartzen da while begizta honetan.
                while (--hitz_kopurua) {
                    
                    // Hutsunearen testua osatzen joan.
                    hutsunearen_testua = hutsuneak[i].hitzak[hitz_kopurua].testua + " " + hutsunearen_testua;
                    
                    // Span-a ezabatu.
                    $("span[data-ms='" + hutsuneak[i].hitzak[hitz_kopurua].denbora + "']").remove();
                    
                }
                
                // Lehen hitza gehitu hutsunearen testuari. Hitz bakarreko hutsunea bada, hau izango da hitz bakarra.
                hutsunearen_testua = hutsuneak[i].hitzak[0].testua + " " + hutsunearen_testua;
                
                // Bukaerako zuriunea kendu.
                hutsunearen_testua = $.trim(hutsunearen_testua);
                
                console.log(hutsunearen_testua);
                //console.log(hutsunearen_testua.replace(/\W/g, ''));
                
                while(/[^a-zA-Z0-9]/.test(hutsunearen_testua.charAt(hutsunearen_testua.length - 1))) {
                    
                    kendutako_karaktereak = hutsunearen_testua.charAt(hutsunearen_testua.length - 1) + kendutako_karaktereak;
                    
                    hutsunearen_testua = hutsunearen_testua.substring(0, hutsunearen_testua.length - 1);
                    
                }
                
                console.log(kendutako_karaktereak);
                console.log(hutsunearen_testua);
                
                // Lehen hitzaren span-a input text batekin ordezkatu.
                $("span[data-ms='" + hutsuneak[i].hitzak[0].denbora + "']").replaceWith("<input type='text' data-id-hutsunea='" + hutsuneak[i].id + "' data-testua='" + hutsunearen_testua + "' />");
                
                if (kendutako_karaktereak.length > 0) {
                    
                    $("input[data-id-hutsunea='" + hutsuneak[i].id + "']").after("<span>" + kendutako_karaktereak + "</span>");
                    
                }
            }
            
		}
		
		// select text function
		function getSelText() {
			var txt = '';
			if (window.getSelection){
				txt = window.getSelection();
			}
			else if (document.getSelection){
				txt = document.getSelection();
			}
			else if (document.selection){
				txt = document.selection.createRange().text;
			}          
			
			return txt;
		}
		
		// Azpitituluen fitxategia parseatu bistaratzeko.
		//pop.parseSRT("<?php echo URL_BASE . $hutsuneak_bete->path_azpitituluak . $hutsuneak_bete->azpitituluak; ?>", {target: "bideoa-azpitituluak"});
		
		// Hipertranskribapenaren testua bistaratu
		$('#transkribapena-edukia').html(<?php echo $hutsuneak_bete->hipertranskribapena; ?>);
        
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
        
        $("#berriz-hasi-botoia").click(function() {
            
            $("#transkribapena-edukia input").each(function() {
                $(this).val("");
                $(this).removeClass("zuzena").removeClass("okerra");
            });
            
            $("#zuzendu-botoia").prop('disabled', false);
            
            // Ikus-entzunezkoa hasierara eraman.
            pop.currentTime(0);
            
        });
        
        $("#zuzendu-botoia").click(function() {
            
            var zuzenak = [];
            var okerrak = [];
            
            // Ikus-entzunezkoa gelditu.
            pop.pause();
            
            $("#transkribapena-edukia input").each(function(index, elem) {
                
                // Elementuaren id-a eskuratuko dugu.
                var id_hutsunea = $(this).attr("data-id-hutsunea");
                
                if($(this).attr("data-testua") === $(this).val()) {
                    
                    $(this).addClass("zuzena");
                    
                    zuzenak.push(id_hutsunea);
                    
                } else {
                    
                    $(this).val($(elem).attr("data-testua"));
                    $(this).addClass("okerra");
                    
                    okerrak.push(id_hutsunea);
                }
                
            });
            
            //alert("Emaitza: " + zuzenak.length + "/" + (zuzenak.length + okerrak.length));
            
            $.post("<?php echo URL_BASE; ?>API/v1/hutsuneak-bete", {
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
        
        $("#emaitzak-modala-ados").click(function() {
            
            $("#emaitzak-modala").modal("hide");
            
            $("#zuzendu-botoia").prop('disabled', true);
            
        });
	});
</script>