<link type="text/css" href="<?php echo URL_BASE; ?>css/jplayer-skin/iametza.minimalista/jplayer.iametza.minimalista.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/bideotranskribapena.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/hitzak_markatu_akatsak.css" rel="stylesheet" />

<style type="text/css">
    .icon-floppy-disk {
        background-image: url("<?php echo URL_BASE; ?>/img/floppy.png");
        background-position: center center;
    }
</style>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo $url_base; ?>">Hitzak markatu</a> > <?php echo $hitzak_markatu->hizkuntzak[$hizkuntza["id"]]->izena; ?> > Akatsak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<?php foreach (hizkuntza_idak() as $h_id) { ?>
<fieldset class="fieldset">
    
    <legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>

    <div>
        
        <div id="transkribapena-edukinontzia-<?php echo $h_id; ?>" class="span6">
            
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
        
        <div id="zerrenda-edukinontzia-<?php echo $h_id; ?>" class="span6">
            
            <div id="hitz-ontzia"></div>
            
            <table id="akatsen-zerrenda-<?php echo $h_id; ?>" data-h-id="<?php echo $h_id; ?>" class="akatsen-zerrenda table table-striped">
                <thead>
                    <tr>
                        <th>Hasiera</th>
                        <th>Amaiera</th>
                        <th>Zuzena</th>
                        <th>Okerra</th>
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
            <?php if ($hitzak_markatu->mota == "bideoa") { ?>
				m4v: "<?php echo URL_BASE . $hitzak_markatu->bideo_path . $hitzak_markatu->bideo_mp4; ?>",
				webmv: "<?php echo URL_BASE . $hitzak_markatu->bideo_path . $hitzak_markatu->bideo_webm; ?>"
            <?php } else if ($hitzak_markatu->mota == "audioa") { ?>
                mp3: "<?php echo URL_BASE . $hitzak_markatu->audio_path . $hitzak_markatu->audio_mp3; ?>",
                oga: "<?php echo URL_BASE . $hitzak_markatu->audio_path . $hitzak_markatu->audio_ogg; ?>"
            <?php } ?>
			},
			options: {
				swfPath: "<?php echo URL_BASE; ?>swf/",
                solution: "flash,html", // To prioritize Flash solution.
			<?php if ($hitzak_markatu->mota == "bideoa") { ?>
				supplied: "m4v, webmv",
            <?php } else if ($hitzak_markatu->mota == "audioa") { ?>
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
            
            // Akatsak ze data-ms-tan jarri behar diren.
            // Akatsak hitz bat baino gehiagokoak izan daitezke.
            var akatsak = [];
            var akats_kopurua = [];
            
            <?php foreach (hizkuntza_idak() as $h_id) { ?>
            akatsak[<?php echo $h_id; ?>] = <?php echo json_encode($hitzak_markatu->hizkuntzak[$h_id]->akatsak); ?>;
            akats_kopurua[<?php echo $h_id; ?>] = akatsak[<?php echo $h_id; ?>].length;
            <?php } ?>
            
            var hitz_kopurua;
            var zuzenaren_testua = "";
            var okerraren_testua = "";
            var denborak;
            
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
            
            // Akatsak nabarmendu eta zerrendara gehituko ditugu.
            for (var i = 0; i < akats_kopurua[h_id]; i++) {
                
                hitz_kopurua = akatsak[h_id][i]["hitzak"].length;
                
                zuzenaren_testua = "";
                okerraren_testua = "";
                
                denborak = "";
                
                // Akatsaren hitz bakoitzeko.
                for (var j = 0; j < hitz_kopurua; j++) {
                    
                    // Zuzenaren testua osatzen joan.
                    zuzenaren_testua = zuzenaren_testua + akatsak[h_id][i]["hitzak"][j].zuzena + " ";
                    
                    // Okerraren testua osatzen joan.
                    okerraren_testua = okerraren_testua + akatsak[h_id][i]["hitzak"][j].okerra + " ";
                    
                    // Hitz(ar)en denbora(k) komaz banatutako kate batean lotu.
                    denborak = denborak + akatsak[h_id][i]["hitzak"][j].denbora + ",";
                    
                    // Span-ean hitz okerra jarri eta dagokion klasea gehitu nabarmentzeko.
                    $("#transkribapena-edukia-" + h_id + " span[data-ms='" + akatsak[h_id][i]["hitzak"][j].denbora + "']").text(akatsak[h_id][i]["hitzak"][j].okerra).addClass("hipertranskribapena-erantzun-okerra");
                    
                }
                
                // Amaierako koma eta zuriuneak kendu.
                denborak = denborak.slice(0, -1);
                zuzenaren_testua = $.trim(zuzenaren_testua);
                okerraren_testua = $.trim(okerraren_testua);
                
                $("#akatsen-zerrenda-" + h_id).append("<tr data-id-akatsa=" + akatsak[h_id][i]["id_akatsa"] + " data-denborak=" + denborak + ">" +
                                                    "<td>" + egokituDenboraHHMMSSra(akatsak[h_id][i]["hitzak"][0].denbora) + "</td>" +
                                                    "<td>" + egokituDenboraHHMMSSra(akatsak[h_id][i]["hitzak"][akatsak[h_id][i]["hitzak"].length - 1].denbora) + "</td>" +
                                                    "<td>" + zuzenaren_testua + "</td>" +
                                                    "<td>" +
                                                        "<div class='input-append'>" +
                                                            "<input class='akatsen-zerrenda-akatsa-testua' type='text' value='" + okerraren_testua + "' />" +
                                                            "<button class='btn akatsen-zerrenda-akatsa-gorde-botoia' type='button' disabled='disabled'>" +
                                                                "<i class='icon-floppy-disk'></i>" +
                                                            "</button>" +
                                                        "</div>" +
                                                    "</td>" +
                                                    "<td><button class='btn akatsen-zerrenda-kendu-botoia'>Kendu</button>" +
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
		pop[<?php echo $h_id; ?>].parseSRT("<?php echo URL_BASE . $hitzak_markatu->hizkuntzak[$h_id]->path_azpitituluak . $hitzak_markatu->hizkuntzak[$h_id]->azpitituluak; ?>", {target: "bideoa-azpitituluak-<?php echo $h_id; ?>"});
        
        // Hipertranskribapenaren testua bistaratu
		$('#transkribapena-edukia-<?php echo $h_id; ?>').html(<?php echo $hitzak_markatu->hizkuntzak[$h_id]->hipertranskribapena; ?>);
        
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
        
        $(".akatsen-zerrenda").on("click", ".akatsen-zerrenda-akatsa-gorde-botoia", function() {
            
            var zuzeneko_testuak = $(this).parent().parent().prev().text().trim().split(" ");
            var okerreko_testuak = $(this).prev().val().trim().split(" ");
            var denborak = $(this).parent().parent().parent().attr("data-denborak").split(",");
            var id_akatsa = $(this).parent().parent().parent().attr("data-id-akatsa");
            
            var h_id = $(this).parent().parent().parent().parent().parent().attr("data-h-id");
            
            var denbora_berriak = [];
            var denbora_berriak_katea = "";
            
            var akatsaren_ondorengo_denbora;
            var akatsaren_iraupena;
            var akatsaren_letra_kopurua = 0;
            
            var kate_berria = " ";
            
            var $hasierako_spana = $("#transkribapena-edukia-" + h_id + " span[data-ms='" + denborak[0] + "']");
            var $that = $(this);
            
            var tmp_denbora;
            
            // Aldatutako akatsaren datuak zerbitzarira bidali.
            $.ajax({
                type: "PUT",
                url: "<?php echo URL_BASE; ?>API/v1/hitzak-markatu/akatsa",
                data: {
                    id_ariketa: <?php echo $id_ariketa; ?>,
                    id_akatsa: id_akatsa,
                    id_hizkuntza: h_id,
                    zuzeneko_testuak: JSON.stringify(zuzeneko_testuak),
                    okerreko_testuak: JSON.stringify(okerreko_testuak),
                    denborak: JSON.stringify(denborak)
                }
            })
            .done(function(data, textStatus, jqXHR) {
                
                // Akatseko hitzek hipertranskribapenean izango dituzten denborak kalkulatu behar ditugu.
                // Hasierako hitzarena jatorrizko hasierako hitzarena izango da.
                denbora_berriak.push(denborak[0]);
                denbora_berriak_katea = denbora_berriak_katea + denborak[0] + ",";
                
                // Hasierako hitzaren testua aldatuko dugu.
                $hasierako_spana.text(okerreko_testuak[0]);
                
                // Gainerako hitz zahar guztiak ezabatuko ditugu.
                for (var i = 1; i < denborak.length; i++) {
                    
                    $("#transkribapena-edukia-" + h_id + " span[data-ms='" + denborak[i] + "']").remove();
                    
                }
                
                // Okerreko testu berrian hitz bat baino gehiago badaude.
                if (okerreko_testuak.length > 1) {
                    
                    // Akatsaren ondorengo hitzaren denbora eskuratuko dugu.
                    akatsaren_ondorengo_denbora = parseInt($hasierako_spana.next().attr("data-ms"), 10);
                    
                    // Akatsaren iraupena kalkulatuko dugu.
                    akatsaren_iraupena = akatsaren_ondorengo_denbora - parseInt(denborak[0], 10);
                    
                    // Akatsaren letra kopuru totala kalkulatuko dugu.
                    for (var i = 0; i < okerreko_testuak.length; i++) {
                        
                        akatsaren_letra_kopurua = akatsaren_letra_kopurua + okerreko_testuak[i].length;
                        
                    }
                    
                    for (var i = 1; i < okerreko_testuak.length; i++) {
                        
                        // Hitzaren hasierako denbora kalkulatu: aurreko hitzaren hasierako denbora + ((akatsaren iraupena * aurreko hitzaren letra kopurua) / akatsaren letra kopuru totala)
                        tmp_denbora = Math.round(parseInt(denbora_berriak[i - 1], 10) + ((akatsaren_iraupena * okerreko_testuak[i - 1].length) / akatsaren_letra_kopurua));
                        
                        // Hitza kate berrira gehitu.
                        kate_berria = kate_berria + "<span data-ms='" + tmp_denbora + "' class='hipertranskribapena-erantzun-okerra'>" + okerreko_testuak[i] + "</span> ";
                        
                        denbora_berriak.push(tmp_denbora);
                        
                        denbora_berriak_katea = denbora_berriak_katea + tmp_denbora + ",";
                    }
                    
                    // Hasierako hitzaren span-aren ondoren gainerako span-ak gehitu.
                    $hasierako_spana.after(kate_berria);
                    
                }
                
                // Denboren kateari bukaerako koma kendu.
                denbora_berriak_katea = denbora_berriak_katea.slice(0, -1);
                
                // tr-aren data-denborak eguneratu.
                $that.parent().parent().parent().attr("data-denborak", denbora_berriak_katea);
                
                // Akatsaren bukaerako denbora eguneratu.
                $that.parent().parent().prev().prev().text(egokituDenboraHHMMSSra(denbora_berriak[denbora_berriak.length - 1]));
                
                // Botoia desgaitu berriz ere.
                $that.prop('disabled', true);
                
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                
                console.log(textStatus);
                console.log(errorThrown);
                
                alert("Errore bat gertatu da akatsa datu-basean gordetzean. Mesedez, saiatu berriro.");
                
            });
        });
        
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
                
                var h_id = <?php echo (int) $hizkuntza["id"]; ?>;
                
                var hitzak = [];
                
                var ordezkatua = false;
                
                var zerrendan = false;
                
                var testua = $(ui.draggable).text();
                var denbora = $(ui.draggable).attr("data-ms");
                
                hitzak.push({"denbora": denbora,
                             "testua": testua
                });
                
                // Hautapena zerrendan ez dagoela egiaztatuko dugu.
                $("#akatsen-zerrenda-" + h_id + " tbody tr").each(function() {
                    
                    // Zerrendako elementuaren denborak bat badatoz hautapenarekin.
                    if ($(this).attr("data-denborak") == denbora) {
                        
                        zerrendan = true;
                        
                    }
                    
                });
                
                console.log(zerrendan);
                
                // Hautapena ez badago zerrendan.
                if (!zerrendan) {
                    
                    // Akats berria zerbitzarira bidali.
                    $.ajax({
                        type: "POST",
                        url: "<?php echo URL_BASE; ?>API/v1/hitzak-markatu/akatsa",
                        data: {
                            id_ariketa: <?php echo $id_ariketa; ?>,
                            id_hizkuntza: h_id,
                            hitzak: JSON.stringify(hitzak)
                        }
                    })
                    .done(function(data, textStatus, jqXHR) {
                        
                        data = JSON.parse(data);
                        
                        console.log(data);
                        console.log(textStatus);
                        
                        katea = "<tr data-denborak='" + denbora + "' data-id-akatsa='" + data["id_akatsa"] + "'>" +
                            "<td>" + egokituDenboraHHMMSSra(denbora) + "</td>" +
                            "<td>" + egokituDenboraHHMMSSra(denbora) + "</td>" +
                            "<td>" + testua + "</td>" +
                            "<td>" +
                                "<div class='input-append'>" +
                                    "<input class='akatsen-zerrenda-akatsa-testua' type='text' value='" + testua + "' />" +
                                    "<button class='btn akatsen-zerrenda-akatsa-gorde-botoia' type='button'>" +
                                        "<i class='icon-floppy-disk'></i>" +
                                    "</button>" +
                                "</div>" +
                            "</td>" +
                            "<td><button class='btn akatsen-zerrenda-kendu-botoia'>Kendu</button>" +
                        "</tr>";
                        
                        // Zerrendan dagoeneko elementuak badaude.
                        if ($("#akatsen-zerrenda-" + h_id + " tbody tr").length > 0) {
                            
                            // Elementua non txertatu jakin behar dugu. Horretarako zerrendako elementuak banan bana pasako ditugu.
                            $("#akatsen-zerrenda-" + h_id + " tbody tr").each(function(index, element) {
                                
                                // Elementu berriaren hasiera baino beranduago hasten bada, bere aurretik txertatuko dugu elementu berria.
                                if ($(this).children(":first").text() > egokituDenboraHHMMSSra(denbora)) {
                                    
                                    $(this).before(katea);
                                    
                                    // each-etik irtengo gara.
                                    return false;
                                }
                                
                                // Azkenengo elementua baino beranduago hasten bada azken elementuaren ondoren txertatu.
                                if (index == $("#akatsen-zerrenda-" + h_id + " tbody tr").length - 1) {
                                    
                                    $(this).after(katea);
                                    
                                }
                                
                            });
                            
                        } else {
                            
                            // Ez dago errenkadarik, append besterik gabe.
                            $("#akatsen-zerrenda-" + h_id).append(katea);
                            
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        
                        console.log(textStatus);
                        console.log(errorThrown);
                        
                        alert("Errore bat gertatu da akatsa datu-basean gordetzean. Mesedez, saiatu berriro.");
                    });
                    
                    // Akatseko hitza nabarmenduko dugu hipertranskribapenean.
                    $("#transkribapena-edukia-" + h_id + " span[data-ms='" + denbora + "']").addClass("hipertranskribapena-erantzun-okerra");
                    
                } else {
                    
                    alert("Ezin duzu hitz hori gehitu, dagoeneko zerrendan baitago.");
                    
                }
                
            }
        });
        
        $(".akatsen-zerrenda").on("input", ".akatsen-zerrenda-akatsa-testua", function() {
            
            // Akats honen gordetzeko botoia gaitu, erabiltzaileari gorde egin behar duela gogoratzeko.
            $(this).next().prop('disabled', false);
            
        });
        
        $(".akatsen-zerrenda").on("click", ".akatsen-zerrenda-kendu-botoia", function() {
            
            var id_akatsa = $(this).parent().parent().attr("data-id-akatsa");
            var denborak = $(this).parent().parent().attr("data-denborak").split(",");
            var hitz_zuzena = $(this).parent().prev().prev().text();
            var $that = $(this);
            
            var h_id = $(this).parent().parent().parent().parent().attr("data-h-id");

            if (confirm("Ziur zaude akatsa ezabatu nahi duzula?")) {
                
                // Akatsa ezabatu behar dela jakinarazi zerbitzariari.
                $.ajax({
                    type: "DELETE",
                    url: "<?php echo URL_BASE; ?>API/v1/hitzak-markatu/akatsa/" + id_akatsa
                })
                .done(function(data, textStatus, jqXHR) {
                    
                    // Hipertranskribapenean akatsaren hitzei kolore normala eman.
                    for (var i = 0; i < denborak.length; i++) {
                        
                        $("#transkribapena-edukia-" + h_id + " span[data-ms='" + denborak[i] + "']").removeClass("hipertranskribapena-erantzun-okerra");
                        
                        // Orain akatsak hitz bakarrekoak direnez lehen eskuratutako hitz zuzena sar dezakegu zuzenean.
                        $("#transkribapena-edukia-" + h_id + " span[data-ms='" + denborak[i] + "']").text(hitz_zuzena);
                    }
                    
                    $that.parent().parent().remove();
                    
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    
                    console.log(textStatus);
                    console.log(errorThrown);
                    
                    alert("Errore bat gertatu da akatsa datu-basean gordetzean. Mesedez, saiatu berriro.");
                    
                });
                
            }
        });
    })
</script>