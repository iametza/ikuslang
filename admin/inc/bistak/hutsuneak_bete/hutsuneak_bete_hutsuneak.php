<link type="text/css" href="<?php echo URL_BASE; ?>css/jplayer-skin/iametza.minimalista/jplayer.iametza.minimalista.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/bideotranskribapena.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/hutsuneak_bete_hutsuneak.css" rel="stylesheet" />

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo $url_base; ?>">Hutsuneak bete</a> > <?php echo $hutsuneak_bete->hizkuntzak[$hizkuntza["id"]]->izena; ?> > Hutsuneak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<?php foreach (hizkuntza_idak() as $h_id) { ?>
<fieldset class="fieldset">
    
    <legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>

    <div>
        
        <div id="transkribapena-edukinontzia-<?php echo $h_id; ?>" class="span8">
            
            <div id="jp_container_<?php echo $h_id; ?>" class="jp-container jp-video jp-video-270p">
                
                <div class="jp-type-single">
                    
                    <div id="jquery_jplayer_<?php echo $h_id; ?>" class="jp-jplayer"></div>
                    
                    <div class="jp-gui">
                        
                        <div class="jp-video-play">
                            <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
                        </div>
                        
                        <div class="jp-interface">
                            
                            <div class="jp-controls-holder">
                                
                                <a href="javascript:;" class="jp-play btn" tabindex="1"><i class="icon-play"></i></a>
                                <a href="javascript:;" class="jp-pause btn" tabindex="1"><i class="icon-pause"></i></a>
                                
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
                    
                    <div id="bideoa-azpitituluak-<?php echo $h_id; ?>" class="bideoa-azpitituluak bideoa-azpitituluak-behean"></div>
                    
                    <div class="jp-no-solution">
                        <span>Beharrezkoa da eguneratzea</span>
                        Edukiak erreproduzitzeko nabigatzailea edo <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugina</a> eguneratu behar dituzu.
                    </div>
                    
                </div><!--end jp-type-single-->
                
            </div><!--end jp_container_1-->
            
            <div id="transkribapena-edukia-<?php echo $h_id; ?>" class="transkribapena-edukia"></div>
        </div>
        
        <div id="zerrenda-edukinontzia-<?php echo $h_id; ?>" class="span4">
            
            <div id="hitz-ontzia"></div>
            
            <table id="hutsuneen-zerrenda-<?php echo $h_id; ?>" data-h-id="<?php echo $h_id; ?>" class="hutsuneen-zerrenda table table-striped">
                <thead>
                    <tr>
                        <th>Denbora</th>
                        <th>Testua</th>
                        <th width="50"></th>
                    </tr>
                </thead>
            </table>
            
        </div>
        
    </div>

</fieldset>
<?php } ?>

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
        
		var pop = [];
        
        <?php foreach (hizkuntza_idak() as $h_id) { ?>
		pop[<?php echo $h_id; ?>] = Popcorn.jplayer("#jquery_jplayer_<?php echo $h_id; ?>", {
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
				swfPath: "<?php echo URL_BASE; ?>swf/",
                solution: "flash,html", // To prioritize Flash solution.
			<?php if ($hutsuneak_bete->mota == "bideoa") { ?>
				supplied: "m4v, webmv",
            <?php } else if ($hutsuneak_bete->mota == "audioa") { ?>
                supplied: "mp3, oga",
            <?php } ?>
                size: {width: "300px", height: "168.5px"}
			}
		});
		<?php } ?>
        
		var dataMs = "data-ms";
        
		// Zertarako ziren hauek?
		var playSource = true;
		var tPause = 0;
		var endTime = null;
        
        // Segundoak * 10 jaso eta hh:mm:ss formatura bihurtzen du.
        function egokituDenboraHHMMSSra(milisegundoak) {
            
            var katea = "";
            
            // Zenbat ordu daude milisegundo horietan?
            var h = Math.floor(milisegundoak / 3600000);
            
            // Itzuliko dugun kateari orduak gehitu
            if (h > 9) {
                katea = katea + h + ":";
            } else {
                katea = katea + '0' + h + ":";
            }
            
            // Ordu horiek kenduta geratzen diren milisegundoak
            milisegundoak = milisegundoak - h * 3600000;
            
            // Zenbat minutu daude geratzen diren milisegundoetan?
            var m = Math.floor(milisegundoak / 60000);
            
            // Itzuliko dugun kateari minutuak gehitu
            if (m > 9) {
                katea = katea + m + ':';
            } else {
                katea = katea + '0' + m + ':';
            }
            
            // Minutu horiek kenduta geratzen diren milisegundoak
            milisegundoak = milisegundoak - m * 60000;
            
            // Zenbat segundo daude geratzen diren segundoetan?
            var s = Math.floor(milisegundoak / 1000);
            
            // Itzuliko dugun kateari milisegundoak gehitu
            if (s > 9) {
                katea = katea + s + ':';
            } else {
                katea = katea + '0' + s + ':';
            }
            
            // Segundo horiek kenduta geratzen diren milisegundoak.
            var ms = Math.round(milisegundoak - s * 1000);
            
            // Itzuliko dugun kateari milisegundoak gehitu.
            if (ms < 10) {
                
                katea = katea + '00' + ms;
                
            } else if (ms < 100) {
                
                katea = katea + '0' + ms;
                
            } else {
                
                katea = katea + ms;
                
            }
            
            return katea;
        }
        
		function initTranscript(p, h_id) {
			
            // Hutsuneak ze data-ms-tan jarri behar diren.
            // Hutsuneak hitz bat baino gehiagokoak izan daitezke.
            var hutsuneak = [];
            var hutsune_kopurua = [];
            
            <?php foreach (hizkuntza_idak() as $h_id) { ?>
            hutsuneak[<?php echo $h_id; ?>] = <?php echo json_encode($hutsuneak_bete->hizkuntzak[$h_id]->hutsuneak); ?>;
            hutsune_kopurua[<?php echo $h_id; ?>] = hutsuneak[<?php echo $h_id; ?>].length;
            <?php } ?>
            
            var hitz_kopurua;
            var hutsunearen_testua = "";
            var $spana;
            var denborak;
            
            // Hutsunearen testuari kendutako !az karaktereak
            var kendutako_karaktereak = "";
            
			$("#transkribapena-edukia-" + h_id + " span").each(function(i) {  
				// doing p.transcript on every word is a bit inefficient - wondering if there is a better way
				p.transcript({
					time: $(this).attr(dataMs) / 1000, // seconds
					futureClass: "transcript-grey",
					target: this,
					onNewPara: function(parent) {
						$("#transkribapena-edukia-" + h_id).stop().scrollTo($(parent), 800, {axis:'y',margin:true,offset:{top:0}});
					}
				});  
			});
			
            // Hutsuneak gehitu dagokion lekuan.
            for (var i = 0; i < hutsune_kopurua[h_id]; i++) {
                
                hitz_kopurua = hutsuneak[h_id][i]["hitzak"].length;
                
                hutsunearen_testua = "";
                
                kendutako_karaktereak = "";
                
                denborak = "";
                
                // Hitz bat baino gehiagoko hutsuneen kasuan bakarrik sartzen da while begizta honetan.
                while (--hitz_kopurua) {
                    
                    // Hutsunearen testua osatzen joan.
                    hutsunearen_testua = hutsuneak[h_id][i]["hitzak"][hitz_kopurua].testua + " " + hutsunearen_testua;
                    
                    // Hitz(ar)en denbora(k) komaz banatutako kate batean lotu.
                    denborak = hutsuneak[h_id][i]["hitzak"][hitz_kopurua].denbora + "," + denborak;
                    
                    // Span-a ezabatu.
                    $("#transkribapena-edukia-" + h_id + " span[data-ms='" + hutsuneak[h_id][i]["hitzak"][hitz_kopurua].denbora + "']").remove();
                    
                }
                
                // Lehen hitza gehitu hutsunearen testuari. Hitz bakarreko hutsunea bada, hau izango da hitz bakarra.
                hutsunearen_testua = hutsuneak[h_id][i]["hitzak"][0].testua + " " + hutsunearen_testua;
                
                // Lehen hitzaren denbora gehitu.
                denborak = hutsuneak[h_id][i]["hitzak"][0].denbora + "," + denborak;
                
                // Amaierako koma eta zuriunea kendu.
                denborak = denborak.slice(0, -1);
                hutsunearen_testua = $.trim(hutsunearen_testua);
                
                while(/[^a-zA-Z0-9]/.test(hutsunearen_testua.charAt(hutsunearen_testua.length - 1))) {
                    
                    kendutako_karaktereak = hutsunearen_testua.charAt(hutsunearen_testua.length - 1) + kendutako_karaktereak;
                    
                    hutsunearen_testua = hutsunearen_testua.substring(0, hutsunearen_testua.length - 1);
                    
                }
                
                console.log(kendutako_karaktereak);
                console.log(hutsunearen_testua);
                
                // Lehen hitzaren span-a input text batekin ordezkatu.
                $("#transkribapena-edukia-" + h_id + " span[data-ms='" + hutsuneak[h_id][i]["hitzak"][0].denbora + "']").replaceWith("<input data-denborak='" + denborak + "' type='text' value='" + hutsunearen_testua + "' readonly='readonly' />");
                
                if (kendutako_karaktereak.length > 0) {
                    
                    $("input[data-denborak='" + denborak + "']").after("<span>" + kendutako_karaktereak + "</span>");
                    
                }
                
                $("#hutsuneen-zerrenda-" + h_id).append("<tr data-id-hutsunea='" + hutsuneak[h_id][i]["id_hutsunea"] + "' data-denborak='" + denborak + "' data-jatorrizkoa='" + hutsunearen_testua + kendutako_karaktereak + "'>" +
                                                    "<td>" + egokituDenboraHHMMSSra(hutsuneak[h_id][i]["hitzak"][0].denbora) + "</td>" +
                                                    //"<td>" + egokituDenboraHHMMSSra(hutsuneak[h_id][i]["hitzak"][hutsuneak[h_id][i]["hitzak"].length - 1].denbora) + "</td>" +
                                                    "<td>" + hutsunearen_testua + "</td>" +
                                                    "<td><button class='btn hutsuneen-zerrenda-kendu-botoia'>Kendu</button>" +
                                                "</tr>");
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
		
        <?php foreach (hizkuntza_idak() as $h_id) { ?>
        
		// Azpitituluen fitxategia parseatu bistaratzeko.
		//pop[<?php echo $h_id; ?>].parseSRT("<?php echo URL_BASE . $hutsuneak_bete->hizkuntzak[$h_id]->path_azpitituluak . $hutsuneak_bete->hizkuntzak[$h_id]->azpitituluak; ?>", {target: "bideoa-azpitituluak-<?php echo $h_id; ?>"});
		
		// Hipertranskribapenaren testua bistaratu
		$('#transkribapena-edukia-<?php echo $h_id; ?>').html(<?php echo $hutsuneak_bete->hizkuntzak[$h_id]->hipertranskribapena; ?>);
        
		// Hipertranskribapenaren oinarrizko funtzionalitatea hasieratu
        initTranscript(pop[<?php echo $h_id; ?>], <?php echo $h_id; ?>);
		
		// Erabiltzaileak transkribapeneko hitz bat klikatzen duenean.
		$('#transkribapena-edukia-<?php echo $h_id; ?>').delegate('span','click',function(e){ 
			playSource = true;
			tPause = 0;
			endTime = null;
			
			// Klikatutako hitza bideoko zein momenturi dagokion kalkulatu.
			var jumpTo = $(this).attr(dataMs) / 1000;
			
			// Dagokion momentuan hasi bideoa erreproduzitzen.
			pop[<?php echo $h_id; ?>].play(jumpTo);
			
			//_gaq.push(['_trackEvent', 'USElect', 'Word clicked', 'word '+$(this).text()]);
			
			return false;
		});
        
        <?php } ?>
        
        $("#transkribapena-edukia-1 span").draggable({
            
            revert: "invalid",
            helper: "clone",
            drag: function(event, ui) {
                ui.helper.removeClass("transcript-grey");
                ui.helper.addClass("hipertranskribapena-arrastatzeko-spana");
            }
            
        });
        
        $("#hitz-ontzia").droppable({
            
            accept: "#transkribapena-edukia-1 span",
            drop: function(event, ui) {
                
                // Euskaraz da.
                var h_id = "1";
                
                // Hautatutako hitzaren testua eta denbora.
                var testua = $(ui.draggable).text();
                var denbora = $(ui.draggable).attr("data-ms");
                
                var hutsunearen_testua = testua;
                
                // Hutsunearen testuari kendutako !az karaktereak
                var kendutako_karaktereak = "";
                
                console.log(testua);
                console.log(denbora);
                
                // Hutsune berria zerbitzarira bidali.
                $.ajax({
                    type: "POST",
                    url: "<?php echo URL_BASE; ?>API/v1/hutsuneak-bete/hutsunea",
                    data: {
                        id_ariketa: <?php echo $id_ariketa; ?>,
                        id_hizkuntza: h_id,
                        hitzak: "[{\"denbora\":\"" + denbora + "\",\"testua\":\"" + testua + "\"}]"
                    }
                })
                .done(function(data, textStatus, jqXHR) {
                    
                    data = JSON.parse(data);
                    
                    console.log(data);
                    console.log(textStatus);
                    
                    while(/[^a-zA-Z0-9]/.test(testua.charAt(hutsunearen_testua.length - 1))) {
                        
                        kendutako_karaktereak = hutsunearen_testua.charAt(hutsunearen_testua.length - 1) + kendutako_karaktereak;
                        
                        hutsunearen_testua = hutsunearen_testua.substring(0, hutsunearen_testua.length - 1);
                        
                    }
                    
                    katea = "<tr data-denborak='" + denbora + "' data-id-hutsunea='" + data["id_hutsunea"] + "' data-jatorrizkoa='" + testua + "'>" +
                        "<td>" + egokituDenboraHHMMSSra(denbora) + "</td>" +
                        "<td>" + hutsunearen_testua + "</td>" +
                        "<td>" +
                            "<button class='btn hutsuneen-zerrenda-kendu-botoia'>Kendu</button>" +
                        "</td>" +
                    "</tr>";
                    
                    // Zerrendan dagoeneko elementuak badaude.
                    if ($("#hutsuneen-zerrenda-" + h_id + " tbody tr").length > 0) {
                        
                        // Elementua non txertatu jakin behar dugu. Horretarako zerrendako elementuak banan bana pasako ditugu.
                        $("#hutsuneen-zerrenda-" + h_id + " tbody tr").each(function(index, element) {
                            
                            // Elementu berriaren hasiera baino beranduago hasten bada, bere aurretik txertatuko dugu elementu berria.
                            if ($(this).children(":first").text() > egokituDenboraHHMMSSra(denbora)) {
                                
                                $(this).before(katea);
                                
                                // each-etik irtengo gara.
                                return false;
                            }
                            
                            // Azkenengo elementua baino beranduago hasten bada azken elementuaren ondoren txertatu.
                            if (index == $("#hutsuneen-zerrenda-" + h_id + " tbody tr").length - 1) {
                                $(this).after(katea);
                            }
                            
                        });
                        
                    } else {
                        
                        // Ez dago errenkadarik, append besterik gabe.
                        $("#hutsuneen-zerrenda-" + h_id).append(katea);
                        
                    }
                    
                    // Lehen hitzaren span-a input text batekin ordezkatu.
                    $("#transkribapena-edukia-" + h_id + " span[data-ms='" + denbora + "']").replaceWith("<input data-denborak='" + denbora + "' type='text' value='" + hutsunearen_testua + "' readonly='readonly' />");
                    
                    if (kendutako_karaktereak.length > 0) {
                        
                        $("input[data-denborak='" + denbora + "']").after("<span>" + kendutako_karaktereak + "</span>");
                        
                    }
                    
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    
                    console.log(textStatus);
                    console.log(errorThrown);
                    
                    alert("Errore bat gertatu da hutsunea datu-basean gordetzean. Mesedez, saiatu berriro.");
                });
                
            }
        });
        
        $(".hutsuneen-zerrenda").on("click", ".hutsuneen-zerrenda-kendu-botoia", function() {
            
            // Denboren csv katea eta arraya.
            var str_denborak = $(this).parent().parent().attr("data-denborak");
            var denborak = str_denborak.split(",");
            
            // Hutsunearen testuen arraya.
            var testuak = $(this).parent().parent().attr("data-jatorrizkoa").split(" ");
            
            var hitz_kopurua = testuak.length;
            
            // Hutsune honi dagokion inputa.
            var $inputa = $("input[data-denborak='" + str_denborak + "']");
            
            var $errenkada = $(this).parent().parent();
            
            var id_hutsunea = $errenkada.attr("data-id-hutsunea");
            var h_id = $(this).parent().parent().parent().parent().attr("data-h-id");
            
            if (window.confirm("Ziur zaude hutsunea behin betiko ezabatu nahi duzula?")) {
                
                // Hutsunea zerbitzaritik ezabatu.
                $.ajax({
                    type: "DELETE",
                    url: "<?php echo URL_BASE; ?>API/v1/hutsuneak-bete/hutsunea/" + id_hutsunea,
                })
                .done(function(data, textStatus, jqXHR) {
                    
                    // Hutsunearen hitzen spanak txertatu.
                    for (var i = 0; i < hitz_kopurua; i++) {
                        
                        $inputa.before("<span class='transcript-grey' data-ms='" + denborak[i] + "'>" +  testuak[i] + "</span> ");
                        
                        // Jatorrizko hitzak puntuazio karaktereak badauzka...
                        if (/[^a-zA-Z0-9]/.test(testuak[i].charAt(testuak[i].length - 1))) {
                            
                            $inputa.next().remove();
                            
                        }
                    }
                    
                    // Hutsunearen inputa ezabatu.
                    $inputa.remove();
                    
                    // Taulako errenkada ezabatu.
                    $errenkada.remove();
                    
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    
                    alert("Errore bat gertatu da hutsunea datu-basetik ezabatzean. Mesedez, saiatu berriro.");
                    
                });
            }
        });
    });
</script>