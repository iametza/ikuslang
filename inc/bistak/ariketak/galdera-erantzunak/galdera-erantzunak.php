<link type="text/css" href="<?php echo URL_BASE; ?>css/ariketak.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/jplayer-skin/iametza.minimalista/jplayer.iametza.minimalista.css" rel="stylesheet" />
<link type="text/css" href="<?php echo URL_BASE; ?>css/bideotranskribapena.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo URL_BASE; ?>css/galdera_erantzunak.css">
<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css' />

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

<script src="<?php echo URL_BASE; ?>js/soundmanager2-nodebug-jsmin.js"></script>
<script src="<?php echo URL_BASE; ?>js/galdera_erantzunak/erantzuna.js"></script>
<script src="<?php echo URL_BASE; ?>js/galdera_erantzunak/galdera.js"></script>
<script src="<?php echo URL_BASE; ?>js/galdera_erantzunak/galderak.js"></script>

<script>
	$(document).ready(function() {
        
        // Erabiltzaileari aurrerapen-barra erabiliz denboran aurrera eta atzera ibiltzea galaraziko diogu.
        $.jPlayer.prototype.seekBar = function() {};
        
        var pop = Popcorn.jplayer("#jquery_jplayer_1", {
			media: {
            <?php if ($galdera_erantzuna->ikus_entzunezkoa->mota == "bideoa") { ?>
				m4v: "<?php echo URL_BASE . $galdera_erantzuna->ikus_entzunezkoa->bideo_path . $galdera_erantzuna->ikus_entzunezkoa->bideo_mp4; ?>",
				webmv: "<?php echo URL_BASE . $galdera_erantzuna->ikus_entzunezkoa->bideo_path . $galdera_erantzuna->ikus_entzunezkoa->bideo_webm; ?>"
            <?php } else if ($galdera_erantzuna->ikus_entzunezkoa->mota == "audioa") { ?>
                mp3: "<?php echo URL_BASE . $galdera_erantzuna->ikus_entzunezkoa->audio_path . $galdera_erantzuna->ikus_entzunezkoa->audio_mp3; ?>",
                oga: "<?php echo URL_BASE . $galdera_erantzuna->ikus_entzunezkoa->audio_path . $galdera_erantzuna->ikus_entzunezkoa->audio_ogg; ?>"
            <?php } ?>
			},
			options: {
                //swfPath: "<?php echo URL_BASE; ?>swf/",
                //solution: "flash,html", // To prioritize Flash solution.
                solution: "html",
            <?php if ($galdera_erantzuna->ikus_entzunezkoa->mota == "bideoa") { ?>
				supplied: "m4v, webmv",
            <?php } else if ($galdera_erantzuna->ikus_entzunezkoa->mota == "audioa") { ?>
                supplied: "mp3, oga",
            <?php } ?>
                size: {
                    width: "300px",
                    height: "225px"
                }
			}
		});
        
        var dataMs = "data-ms";
        
        <?php
            
            for ($i = 0; $i < count($galdera_erantzuna->galderak); ++$i) {
                
        ?>
        
        pop.code({
            
            start: <?php echo $galdera_erantzuna->galderak[$i]->denbora; ?>,
            end: <?php echo $galdera_erantzuna->galderak[$i]->denbora + 1; ?>,
            onStart: function() {
                
                pop.pause();
                
                // Dagokion galdera prestatu.
                bistaratu_galdera();
                
                $("#galderak-modala").modal("show", {
                    backdrop: "static"
                });
                
            }
            
        });
            
        <?php
                
            }
           
        ?>
        
        pop.on("ended", function() {
            
            if (amaierako_galderak) {
                
                // Dagokion galdera prestatu.
                bistaratu_galdera();
                
                $("#galderak-modala").modal("show", {
                    backdrop: "static"
                });
                
            } else {
                
                $.post("<?php echo URL_BASE; ?>API/v1/galdera-erantzunak",
                    {
                        "id_ariketa": <?php echo $id_ariketa; ?>,
                        "id_ikasgaia": <?php echo $id_ikasgaia; ?>,
                        "id_ikaslea": <?php echo $erabiltzailea->get_id(); ?>,
                        "zuzenak": emaitzak.zuzenak,
                        "okerrak": emaitzak.okerrak
                    }
                )
                .done(function(data) {
                    
                    $("#emaitzak-modala-zuzenak").text(emaitzak.zuzenak.length);
                    $("#emaitzak-modala-okerrak").text(emaitzak.okerrak.length);
                    
                    $("#emaitzak-modala").modal("show", {
                        backdrop: "static"
                    });
                    
                })
                .fail(function() {
                });
                
                console.log(emaitzak.zuzenak);
                console.log(emaitzak.okerrak);
                
            }
        });
        
        function initTranscript(p) {
            
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
            
        }
        
		function soundManager_konfiguratu(){
			soundManager.setup({
				url: '<?php echo URL_BASE; ?>swf/',
				flashVersion: 9, // optional: shiny features (default = 8)
				useFlashBlock: false, // optionally, enable when you're ready to dive in
				/**
				  * read up on HTML5 audio support, if you're feeling adventurous.
				  * iPad/iPhone and devices without flash installed will always attempt to use it.
				  */
				onready: function() {
					// Ready to use; soundManager.createSound() etc. can now be called.
					var soinua = soundManager.createSound({
						id: 'miau',
						url: '<?php echo URL_BASE; ?>soinuak/miau.mp3'
					});
					
					var zuzena = soundManager.createSound({
						id: 'erantzun_zuzena',
						url: '<?php echo URL_BASE; ?>soinuak/41345__ivanbailey__2.mp3'
					});
					
					var okerra = soundManager.createSound({
						id: 'erantzun_okerra',
						url: '<?php echo URL_BASE; ?>soinuak/151309__tcpp__beep1-resonant-error-beep.mp3'
						//url: '<?php echo URL_BASE; ?>soinuak/161694__ziembee__error.mp3'
					});
				}
			});
		};
		
        var galderak = false;
        var amaierako_galderak = false;
        
        // Erabiltzaileak emandako erantzunak (erantzunaren id-a gordetzen dugu).
        var emaitzak = {
            
            zuzenak: [],
            okerrak: []
        };
        
        <?php if (count($galdera_erantzuna->galderak) > 0) { ?>
        
		// Galderak objektu berri bat sortu
		galderak = new Galderak({
            
            galderak_desordenatu: false
            
        });
		
        <?php } ?>
        
        <?php if (count($galdera_erantzuna->amaierako_galderak) > 0) { ?>
        
        // Amaierako galderentzat objektu berri bat sortu.
        amaierako_galderak = new Galderak({
            
            galderak_desordenatu: true
            
        });
        
        <?php } ?>
        
        <?php
            
			for ($i = 0; $i < count($galdera_erantzuna->galderak); ++$i) {
				echo "galderak.gehitu_galdera({id_galdera: '$i', testua: '" . $galdera_erantzuna->galderak[$i]->galdera . "', noiz: " . $galdera_erantzuna->galderak[$i]->denbora . "});";
				
				for ($j = 0; $j < count($galdera_erantzuna->galderak[$i]->erantzunak); ++$j) {
					echo "galderak.gehitu_erantzuna('$i', '$j', '" . $galdera_erantzuna->galderak[$i]->erantzunak[$j]->erantzuna . "', " . $galdera_erantzuna->galderak[$i]->erantzunak[$j]->zuzena . ", " . $galdera_erantzuna->galderak[$i]->erantzunak[$j]->id . ");";
				}
			}
            
            for ($i = 0; $i < count($galdera_erantzuna->amaierako_galderak); ++$i) {
				echo "amaierako_galderak.gehitu_galdera({id_galdera: '$i', testua: '" . $galdera_erantzuna->amaierako_galderak[$i]->galdera . "', noiz: " . $galdera_erantzuna->amaierako_galderak[$i]->denbora . "});";
				
				for ($j = 0; $j < count($galdera_erantzuna->amaierako_galderak[$i]->erantzunak); ++$j) {
					echo "amaierako_galderak.gehitu_erantzuna('$i', '$j', '" . $galdera_erantzuna->amaierako_galderak[$i]->erantzunak[$j]->erantzuna . "', " . $galdera_erantzuna->amaierako_galderak[$i]->erantzunak[$j]->zuzena . ", " . $galdera_erantzuna->amaierako_galderak[$i]->erantzunak[$j]->id . ");";
				}
			}
		?>
        
        // Galdera arruntak bukatu diren ala ez. Amaierako galderak ez ditu kontutan hartzen.
        var galderak_bukatu_dira = false;
        
		// Emandako id-a duen botoia desgaitu
		function desgaitu_botoia(id) {
			//$(id).attr("disabled", true);
			$(id).css("visibility", "hidden");
		}

		// Emandako id-a duen botoia gaitu
		function gaitu_botoia(id) {
			//$(id).attr("disabled", false);
			$(id).css("visibility", "visible");
		}
		
		// Dagokion galdera bistaratzen du
		function bistaratu_galdera() {
			
			bistaratu_zuzen_kopurua();
			
			bistaratu_oker_kopurua();
			
			bistaratu_zenbagarrena();
			
			bistaratu_galdera_kopurua();
			
			// Hasieran aurrera joateko botoiak desgaituta egon behar du
			desgaitu_botoia("#aurrera");
			
			// Erabiltzaileari erantzuteko aukera eman
			if (!galderak_bukatu_dira && galderak && galderak.itzuli_zenbagarren_galdera() <= galderak.itzuli_galdera_kopurua()) {
                
                galderak.gaitu_erantzunak();
                
            } else if (amaierako_galderak && amaierako_galderak.itzuli_zenbagarren_galdera() <= amaierako_galderak.itzuli_galdera_kopurua()) {
                
                amaierako_galderak.gaitu_erantzunak();
                
            }
            
            if (!galderak_bukatu_dira && galderak && galderak.itzuli_zenbagarren_galdera() <= galderak.itzuli_galdera_kopurua()) {
                
                // Galderaren id-a gorde atributu pertsonalizatu batean
                $("#galdera").attr("data-id", galderak.itzuli_id_galdera());
                
                // Galderaren testua bistaratu
                $("#galdera").text(galderak.itzuli_galderaren_testua());
                
                // Galderari dagozkion erantzunen id-en arraya eskuratu (desordenatuta)
                var id_erantzunak = galderak.itzuli_id_erantzunak();
                
                // Galdera motaren arabera beharrezko fitxategia kargatu
                if (galderak.itzuli_mota() == 'irudia') {
                    // Dagokion irudia bistaratzeko img bat sortu dagokion lekuan
                    $("#galdera_kontainer").prepend("<img id='irudia' src='" + galderak.itzuli_fitxategia() + "'>");
                } else if (galderak.itzuli_mota() == 'soinua') {
                    // Play botoia bistaratu
                    //$("#play").css("visibility", "visible");
                    $("#galdera_kontainer").prepend("<img id='play' src='<?php echo URL_BASE; ?>img/galdera_erantzunak/play.png'>");
                    $("#play").click(function() {
                        erreproduzitu_soinua();
                    });
                    soundManager.play(galderak.itzuli_fitxategia());
                }
                
            } else if (amaierako_galderak && amaierako_galderak.itzuli_zenbagarren_galdera() <= amaierako_galderak.itzuli_galdera_kopurua()) {
                
                // Galderaren id-a gorde atributu pertsonalizatu batean
                $("#galdera").attr("data-id", amaierako_galderak.itzuli_id_galdera());
                
                // Galderaren testua bistaratu
                $("#galdera").text(amaierako_galderak.itzuli_galderaren_testua());
                
                // Galderari dagozkion erantzunen id-en arraya eskuratu (desordenatuta)
                var id_erantzunak = amaierako_galderak.itzuli_id_erantzunak();
                
                // Galdera motaren arabera beharrezko fitxategia kargatu
                if (amaierako_galderak.itzuli_mota() == 'irudia') {
                    // Dagokion irudia bistaratzeko img bat sortu dagokion lekuan
                    $("#galdera_kontainer").prepend("<img id='irudia' src='" + galderak.itzuli_fitxategia() + "'>");
                } else if (amaierako_galderak.itzuli_mota() == 'soinua') {
                    // Play botoia bistaratu
                    //$("#play").css("visibility", "visible");
                    $("#galdera_kontainer").prepend("<img id='play' src='<?php echo URL_BASE; ?>img/galdera_erantzunak/play.png'>");
                    $("#play").click(function() {
                        erreproduzitu_soinua();
                    });
                    soundManager.play(galderak.itzuli_fitxategia());
                }
                
            }
			
			// Galdera erantzunanitzaren div-ak ezabatu
			$(".erantzunanitza_div").remove();
			
            if (!galderak_bukatu_dira && galderak && galderak.itzuli_zenbagarren_galdera() <= galderak.itzuli_galdera_kopurua()) {
                
                /*
                 * Galdera honen eta aurrekoaren erantzun kopurua ez bada berdina,
                 * aurreko div-ak ezabatu eta behar adina div sortu 
                 */
                if (galderak.itzuli_erantzun_mota() != "mapa") {
                    // Aurreko galderako mapa ezabatu
                    $("#chaptersMap").empty();
                    
                    // Galdera erantzunanitzetan aurreko erantzunen div-ak ezabatu behar dira,
                    // bestela aurreko galderaren erantzun kopurua berdina denean ez da agertzen checkbox-ik
                    if (id_erantzunak.length != $("#erantzunak div").size()
                        || galderak.erantzunanitza_da()) {
                        ezabatu_aurreko_erantzunen_divak();
                        sortu_erantzunen_divak();
                    }
                } else {
                    // Aurreko galderaren divak ezabatu
                    // (behar bada ezkutatzearekin nahikoa litzateke)
                    ezabatu_aurreko_erantzunen_divak();
                    
                    /*
                     * EGITEKO: Aurreko galderaren mapa berdina bada ez dago kargatu beharrik.
                     * Orain mapa berdina izanda ere berriz kargatzen du.
                     */
                    
                    // Aurreko galderako mapa ezabatu
                    $("#chaptersMap").empty();
                    
                    // Mapa berria kargatu
                    prestatu_mapa(galderak.itzuli_fitxategia());
                }
                
                // Zuzendu botoia galdera erantzunanitzetan bakarrik bistaratu
                if (galderak.erantzunanitza_da()) {
                    //$("#zuzendu").css("visibility", "visible");
                    $("#zuzendu").show();
                    gaitu_botoia($("#zuzendu"));
                } else {
                    //$("#zuzendu").css("visibility", "hidden");
                    $("#zuzendu").hide();
                }
                
                // Erantzunak bistaratu
                if (galderak.itzuli_erantzun_mota() != "mapa") {
                    for (var i = 0; i < id_erantzunak.length; i++){
                        // Erantzunaren div-aren atzeko planoa kolore lehenetsira berrezarri
                        $("#erantzuna" + i).removeClass("erantzun_zuzena");
                        $("#erantzuna" + i).removeClass("erantzun_okerra");
                        //$("#erantzuna" + i).css("background", "#fff");
                        
                        // Erantzunaren testua bistaratu
                        $("#erantzuna" + i).text(galderak.itzuli_erantzunaren_testua(id_erantzunak[i]));
                        
                        // Erantzunaren id_erantzuna gorde atributu pertsonalizatu batean
                        $("#erantzuna" + i).attr("data-id", id_erantzunak[i]);
                    }
                } else {
                    for (var i = 0; i < id_erantzunak.length; i++) {
                        // Erantzunaren bidearen atzeko planoa kolore lehenetsira berrezarri
                        //$("#erantzuna" + i).attr("fill", "#898989");
                    }
                }
                
            } else if (amaierako_galderak && amaierako_galderak.itzuli_zenbagarren_galdera() <= amaierako_galderak.itzuli_galdera_kopurua()) {
                
                /*
                 * Galdera honen eta aurrekoaren erantzun kopurua ez bada berdina,
                 * aurreko div-ak ezabatu eta behar adina div sortu 
                 */
                if (amaierako_galderak.itzuli_erantzun_mota() != "mapa") {
                    // Aurreko galderako mapa ezabatu
                    $("#chaptersMap").empty();
                    
                    // Galdera erantzunanitzetan aurreko erantzunen div-ak ezabatu behar dira,
                    // bestela aurreko galderaren erantzun kopurua berdina denean ez da agertzen checkbox-ik
                    if (id_erantzunak.length != $("#erantzunak div").size()
                        || amaierako_galderak.erantzunanitza_da()) {
                        ezabatu_aurreko_erantzunen_divak();
                        sortu_erantzunen_divak();
                    }
                } else {
                    // Aurreko galderaren divak ezabatu
                    // (behar bada ezkutatzearekin nahikoa litzateke)
                    ezabatu_aurreko_erantzunen_divak();
                    
                    /*
                     * EGITEKO: Aurreko galderaren mapa berdina bada ez dago kargatu beharrik.
                     * Orain mapa berdina izanda ere berriz kargatzen du.
                     */
                    
                    // Aurreko galderako mapa ezabatu
                    $("#chaptersMap").empty();
                    
                    // Mapa berria kargatu
                    prestatu_mapa(amaierako_galderak.itzuli_fitxategia());
                }
                
                // Zuzendu botoia galdera erantzunanitzetan bakarrik bistaratu
                if (amaierako_galderak.erantzunanitza_da()) {
                    //$("#zuzendu").css("visibility", "visible");
                    $("#zuzendu").show();
                    gaitu_botoia($("#zuzendu"));
                } else {
                    //$("#zuzendu").css("visibility", "hidden");
                    $("#zuzendu").hide();
                }
                
                // Erantzunak bistaratu
                if (amaierako_galderak.itzuli_erantzun_mota() != "mapa") {
                    for (var i = 0; i < id_erantzunak.length; i++){
                        // Erantzunaren div-aren atzeko planoa kolore lehenetsira berrezarri
                        $("#erantzuna" + i).removeClass("erantzun_zuzena");
                        $("#erantzuna" + i).removeClass("erantzun_okerra");
                        //$("#erantzuna" + i).css("background", "#fff");
                        
                        // Erantzunaren testua bistaratu
                        $("#erantzuna" + i).text(amaierako_galderak.itzuli_erantzunaren_testua(id_erantzunak[i]));
                        
                        // Erantzunaren id_erantzuna gorde atributu pertsonalizatu batean
                        $("#erantzuna" + i).attr("data-id", id_erantzunak[i]);
                    }
                } else {
                    for (var i = 0; i < id_erantzunak.length; i++) {
                        // Erantzunaren bidearen atzeko planoa kolore lehenetsira berrezarri
                        //$("#erantzuna" + i).attr("fill", "#898989");
                    }
                }
            }
		}
		
		function bistaratu_zenbagarrena() {
            
            var zenbagarrena = 0;
            
            if (galderak && amaierako_galderak) {
                
                zenbagarrena = galderak.itzuli_zenbagarren_galdera() + amaierako_galderak.itzuli_zenbagarren_galdera() - 1;
                
            } else if (galderak) {
                
                zenbagarrena = galderak.itzuli_zenbagarren_galdera();
                
            } else if (amaierako_galderak) {
                
                zenbagarrena = amaierako_galderak.itzuli_zenbagarren_galdera();
                
            }
            
			$("#unekoa").text(zenbagarrena);
		}
		
		function bistaratu_zuzen_kopurua() {
            
            var zuzen_kopurua = 0;
            
            if (galderak) {
                
                zuzen_kopurua = zuzen_kopurua + galderak.itzuli_erantzun_zuzen_kopurua();
                
            }
            
            if (amaierako_galderak) {
                
                zuzen_kopurua = zuzen_kopurua + amaierako_galderak.itzuli_erantzun_zuzen_kopurua();
                
            }
            
			$("#zuzenak").text(zuzen_kopurua);
		}
		function bistaratu_oker_kopurua() {
			
            var oker_kopurua = 0;
            
            if (galderak) {
                
                oker_kopurua = oker_kopurua + galderak.itzuli_erantzun_oker_kopurua();
                
            }
            
            if (amaierako_galderak) {
                
                oker_kopurua = oker_kopurua + amaierako_galderak.itzuli_erantzun_oker_kopurua();
                
            }
            
			$("#okerrak").text(oker_kopurua);
            
		}
		
		function bistaratu_galdera_kopurua() {
            
            var galdera_kopurua = 0;
            
            if (galderak) {
                
                galdera_kopurua = galdera_kopurua + galderak.itzuli_galdera_kopurua();
                
            }
            
            if (amaierako_galderak) {
                
                galdera_kopurua = galdera_kopurua + amaierako_galderak.itzuli_galdera_kopurua();
                
            }
            
			$("#guztira").text(galdera_kopurua);
		}
		
		function aurrera_klik() {
            
			// Irudiak ezkutuan daudela ziurtatu
			//$("#irudia").attr("src", "");
			$("#irudia").remove();
			//$("#play").css("visibility", "hidden");
			$("#play").remove();
			
            if (!galderak_bukatu_dira && galderak && galderak.itzuli_zenbagarren_galdera() <= galderak.itzuli_galdera_kopurua()) {
                
                // Sortako azken galderan bagaude edo hurrengo galdera ez bada unekoaren denbora berean.
                if ((galderak.itzuli_zenbagarren_galdera() === galderak.itzuli_galdera_kopurua())
                    || (galderak.itzuliGalderaNoiz(galderak.itzuli_zenbagarren_galdera()) !== galderak.itzuliGalderaNoiz(galderak.itzuli_zenbagarren_galdera() + 1))) {
                    
                    // Modala ezkutatu.
                    $("#galderak-modala").modal("hide");
                    
                    // Hurrengo galdera kargatu, gero bistaratzeko.
                    // Hurrengo galderarik ez badago false itzultzen du.
                    if (!galderak.hurrengo_galdera()) {
                        
                        galderak_bukatu_dira = true;
                        
                    }
                    
                    // Multimedia erreproduzitzen hasi berriz ere.
                    pop.play();
                    
                } else {
                    
                    galderak.hurrengo_galdera();
                    
                    // Hurrengo galdera bistaratu
                    bistaratu_galdera();
                    
                    // Zenbagarren galdera den bistaratu
                    bistaratu_zenbagarrena();
                    
                    // Botoia desgaitu erabiltzaileari erantzun bat hautatzera behartzeko
                    desgaitu_botoia("#aurrera");
                    
                    // Erabiltzaileari erantzuteko aukera eman
                    galderak.gaitu_erantzunak();
                    
                }
                
            } else if (amaierako_galderak && amaierako_galderak.itzuli_zenbagarren_galdera() <= amaierako_galderak.itzuli_galdera_kopurua()) {
                
                // Sortako azken galderan bagaude
                if (amaierako_galderak.itzuli_zenbagarren_galdera() === amaierako_galderak.itzuli_galdera_kopurua()) {
                    
                    // Modala ezkutatu.
                    $("#galderak-modala").modal("hide");
                    
                    console.log(emaitzak);
                    
                    // Emaitzak zerbitzarira bidali.
                    $.post("<?php echo URL_BASE; ?>API/v1/galdera-erantzunak", {
                            "id_ariketa": <?php echo $id_ariketa; ?>,
                            "id_ikasgaia": <?php echo $id_ikasgaia; ?>,
                            "id_ikaslea": <?php echo $erabiltzailea->get_id(); ?>,
                            "zuzenak": emaitzak.zuzenak,
                            "okerrak": emaitzak.okerrak
                        }
                    )
                    .done(function(data) {
                        
                        $("#emaitzak-modala-zuzenak").text(emaitzak.zuzenak.length);
                        $("#emaitzak-modala-okerrak").text(emaitzak.okerrak.length);
                        
                        $("#emaitzak-modala").modal("show", {
                            backdrop: "static"
                        });
                        
                    })
                    .fail(function() {
                    });
                    
                } else {
                    
                    amaierako_galderak.hurrengo_galdera();
                    
                    // Hurrengo galdera bistaratu
                    bistaratu_galdera();
                    
                    // Zenbagarren galdera den bistaratu
                    bistaratu_zenbagarrena();
                    
                    // Botoia desgaitu erabiltzaileari erantzun bat hautatzera behartzeko
                    desgaitu_botoia("#aurrera");
                    
                    // Erabiltzaileari erantzuteko aukera eman
                    amaierako_galderak.gaitu_erantzunak();
                    
                }
                
            }
            
		}
		
		function zuzendu_klik() {
			var erantzun_zuzenak = galderak.itzuli_erantzun_zuzenak();
			var erantzun_kopurua = $(".erantzunanitza_div").length;
			var okerrak = 0;
			for (var i = 0; i < erantzun_kopurua; i++) {
				// Erantzun zuzena bada
				if (galderak.erantzun_zuzena_da($("#erantzuna" + i).attr("data-id")) == true) {
					// Erabiltzaileak erantzun zuzen bezala markatu badu
					if ($("#check" + i).is(':checked')) {
						// Erantzun zuzenari dagokion estilo eman kontrol-laukiari eta bere div-ari
						$("#erantzuna" + i).addClass("erantzun_zuzena");
						$("#erantzunanitza" + i).addClass("erantzun_zuzena");
					} else {
						// Erantzun okerrari dagokion estilo eman kontrol-laukiari eta bere div-ari
						$("#erantzuna" + i).addClass("erantzun_okerra");
						$("#erantzunanitza" + i).addClass("erantzun_okerra");
						okerrak++;
					}
				} else {
					if ($("#check" + i).is(':checked')) {
						// Erantzun okerrari dagokion estilo eman kontrol-laukiari eta bere div-ari
						$("#erantzuna" + i).addClass("erantzun_okerra");
						$("#erantzunanitza" + i).addClass("erantzun_okerra");
						okerrak++;
					}
				}
			}
			
			if (okerrak > 0) {
				galderak.erantzun_okerrak_gehi_bat();
				bistaratu_oker_kopurua();
			} else {
				galderak.erantzun_zuzenak_gehi_bat();
				bistaratu_zuzen_kopurua();
			}
			
			// Zuzendu botoia desgaitu dagoeneko ez baitugu behar
			desgaitu_botoia("#zuzendu");
            
		}
		
		function erantzun_klik_maneiatzailea() {
            
            if (galderak && galderak.itzuli_zenbagarren_galdera() <= galderak.itzuli_galdera_kopurua()) {
                
                // Erantzunak gaituta badaude
                if (galderak.itzuli_erantzunak_gaituta()) {
                    
                    // Erantzuna zuzena bada
                    if (galderak.erantzun_zuzena_da($(this).attr("data-id")) == true){
                        //alert("oso ondo");
                        
                        // Erantzun zuzenari dagokion estiloa aplikatu
                        $(this).addClass("erantzun_zuzena");
                        
                        // Erantzun zuzenari dagokion soinua erreproduzitu
                        soundManager.play('erantzun_zuzena');
                        
                        galderak.erantzun_zuzenak_gehi_bat();
                        
                        bistaratu_zuzen_kopurua();
                        
                        // Erantzunaren id-a zuzenen arrayan gorde.
                        emaitzak.zuzenak.push(galderak.itzuli_id_erantzuna_db(galderak.itzuli_id_galdera(), $(this).attr("data-id")));
                        
                    } else { // okerra bada berriz
                        
                        //alert("oker");
                        
                        // Erantzun okerrari dagokion estiloa aplikatu
                        $(this).addClass("erantzun_okerra");
                        
                        // Erantzun okerrari dagokion soinua erreproduzitu
                        soundManager.play('erantzun_okerra');
                            
                        // Erantzun zuzenari/ei dagokion/en estiloa aplikatu
                        var erantzun_zuzenak = galderak.itzuli_erantzun_zuzenak();
                        for (var i = 0; i < erantzun_zuzenak.length; i++) {
                            $("#erantzuna" + erantzun_zuzenak[i]).addClass("erantzun_zuzena");
                        }
                        
                        galderak.erantzun_okerrak_gehi_bat();
                        
                        bistaratu_oker_kopurua();
                        
                        // Erantzunaren id-a okerren arrayan gorde.
                        emaitzak.okerrak.push(galderak.itzuli_id_erantzuna_db(galderak.itzuli_id_galdera(), $(this).attr("data-id")));
                        
                    }
                    
                    // Erabiltzaileari ez utzi berriz erantzuten
                    galderak.desgaitu_erantzunak();
                    
                    // Aurrera joateko botoia gaitu
                    gaitu_botoia("#aurrera");
                    
                }
                
            } else if (amaierako_galderak && amaierako_galderak.itzuli_zenbagarren_galdera() <= amaierako_galderak.itzuli_galdera_kopurua()) {
                
                // Erantzunak gaituta badaude
                if (amaierako_galderak.itzuli_erantzunak_gaituta()) {
                    
                    // Erantzuna zuzena bada
                    if (amaierako_galderak.erantzun_zuzena_da($(this).attr("data-id")) == true){
                        
                        //alert("oso ondo");
                        
                        // Erantzun okerrari dagokion estiloa aplikatu
                        $(this).addClass("erantzun_zuzena");
                        
                        // Erantzun zuzenari dagokion soinua erreproduzitu
                        soundManager.play('erantzun_zuzena');
                        
                        amaierako_galderak.erantzun_zuzenak_gehi_bat();
                        
                        bistaratu_zuzen_kopurua();
                        
                        // Erantzunaren id-a zuzenen arrayan gorde.
                        emaitzak.zuzenak.push(amaierako_galderak.itzuli_id_erantzuna_db(amaierako_galderak.itzuli_id_galdera(), $(this).attr("data-id")));
                        
                    } else { // okerra bada berriz
                        
                        //alert("oker");
                        
                        // Erantzun okerrari dagokion estiloa aplikatu
                        $(this).addClass("erantzun_okerra");
                        
                        // Erantzun okerrari dagokion soinua erreproduzitu
                        soundManager.play('erantzun_okerra');
                        
                        // Erantzun zuzenari/ei dagokion/en estiloa aplikatu
                        var erantzun_zuzenak = amaierako_galderak.itzuli_erantzun_zuzenak();
                        
                        for (var i = 0; i < erantzun_zuzenak.length; i++) {
                            $("#erantzuna" + erantzun_zuzenak[i]).addClass("erantzun_zuzena");
                        }
                        
                        amaierako_galderak.erantzun_okerrak_gehi_bat();
                        
                        bistaratu_oker_kopurua();
                        
                        // Erantzunaren id-a okerren arrayan gorde.
                        emaitzak.okerrak.push(amaierako_galderak.itzuli_id_erantzuna_db(amaierako_galderak.itzuli_id_galdera(), $(this).attr("data-id")));
                        
                    }
                    
                    // Erabiltzaileari ez utzi berriz erantzuten
                    amaierako_galderak.desgaitu_erantzunak();
                    
                    // Aurrera joateko botoia gaitu
                    gaitu_botoia("#aurrera");
                    
                }
                
            }
            
		}
		
		function ezabatu_aurreko_erantzunen_divak() {
			$("#erantzunak div").remove();
		}
		
		function sortu_erantzunen_divak() {
            
			if (galderak && galderak.itzuli_zenbagarren_galdera() <= galderak.itzuli_galdera_kopurua()) {
                
                // Erantzun bakoitzaren div-a sortu eta klik maneiatzailea gehitu
                var erantzun_kop = galderak.itzuli_erantzun_kopurua();
                
                for (var i = 0; i < erantzun_kop; i++){
                    
                    // Erantzunentzat behar adina div sortu
                    if (galderak.erantzunanitza_da()) {
                        
                        $("#erantzunak").append("<div class='erantzunanitza_div' id='erantzunanitza" + i + "'><input type='checkbox' id='check" + i + "' ><span class='erantzuna_span' id='erantzuna" + i + "'></span></div>");
                        
                    } else {
                        
                        $("#erantzunak").append("<div class='erantzuna_div' id='erantzuna" + i + "'></div>");
                        $("#erantzuna" + i).click(erantzun_klik_maneiatzailea);
                        
                    }
                    
                }
                
            } else if (amaierako_galderak && amaierako_galderak.itzuli_zenbagarren_galdera() <= amaierako_galderak.itzuli_galdera_kopurua()) {
                
                // Erantzun bakoitzaren div-a sortu eta klik maneiatzailea gehitu
                var erantzun_kop = amaierako_galderak.itzuli_erantzun_kopurua();
                
                for (var i = 0; i < erantzun_kop; i++){
                    
                    // Erantzunentzat behar adina div sortu
                    if (amaierako_galderak.erantzunanitza_da()) {
                        
                        $("#erantzunak").append("<div class='erantzunanitza_div' id='erantzunanitza" + i + "'><input type='checkbox' id='check" + i + "' ><span class='erantzuna_span' id='erantzuna" + i + "'></span></div>");
                        
                    } else {
                        
                        $("#erantzunak").append("<div class='erantzuna_div' id='erantzuna" + i + "'></div>");
                        $("#erantzuna" + i).click(erantzun_klik_maneiatzailea);
                        
                    }
                    
                }
                
            }
            
		}
		
		function erreproduzitu_soinua() {
			if (galderak.itzuli_mota() == 'soinua') {
				soundManager.play(galderak.itzuli_fitxategia());
			}
		}
		
		function prestatu_mapa(paths_aldagaia) {
			var r = new ScaleRaphael('chaptersMap', 1000, 1000);
			
			if (paths_aldagaia == "paths_lurra") {
				r.scaleAll(0.75);
				$("svg").css("left", "-60px");
				$("svg").css("top", "-100px");
			} else if (paths_aldagaia == "paths_digestioa") {
				r.scaleAll(0.85);
				$("svg").css("left", "150px");
				$("svg").css("top", "-30px");
			} else if (paths_aldagaia == "paths_formak") {
				$("svg").css("left", "110px");
			}
			
			r.safari();
			attributes = {
					//fill: '#898989',
					stroke: '#FFFFFF',
					'stroke-width': 2,
					'stroke-linejoin': 'round'
			}
				
			arr = new Array();
			
			// Azkena hautaturiko herrialdea
			var azkena;
			
			var paths = window[paths_aldagaia];
			
			for (var country in paths) {
				var obj = r.path(paths[country].path);
				arr[obj.id] = country;
				
				if (paths[country].bete == "true"){
					/*
					 * Gipuzkoa eskalatu eta posizioz aldatu behar da,
					 * Wikipedian aurkitu dudan maparen ezaugarriak direla eta.
					 */
					if (paths[arr[obj.id]].name == "Gipuzkoa") {
						obj.transform("m0.63004424,0,0,0.63004424,-9.7501545,-10.786905");
					}
					
					obj.attr(attributes);
					obj.attr("fill", paths[country].kolorea);
					
					// Bide bakoitzari id atributua gehitu gero hautatu ahal izateko
					obj.node.id = "erantzuna" + paths[arr[obj.id]].id;
		
					obj
					.click(function(){
						// Erantzunak gaituta badaude
						if (galderak.itzuli_erantzunak_gaituta()) {
							// Erantzuna zuzena bada
							if (galderak.erantzun_zuzena_da(paths[arr[this.id]].id) == true){
								//alert("oso ondo");
								
								// Berdez pintatu
								this.animate({
									fill: '#0f0'
								});
								galderak.erantzun_zuzenak_gehi_bat();
								bistaratu_zuzen_kopurua();
							} else { // okerra bada berriz
								//alert("oker");
								
								// Gorriz pintatu
								this.animate({
									fill: '#f00'
								});
								
								// Erantzun zuzena(k) berdez pintatu
								var erantzun_zuzenak = galderak.itzuli_erantzun_zuzenak();
								for (var i = 0; i < erantzun_zuzenak.length; i++) {
									$("#erantzuna" + erantzun_zuzenak[i]).attr("fill", "#0f0");
								}
								
								galderak.erantzun_okerrak_gehi_bat();
								bistaratu_oker_kopurua();
							}
							
							// Galdera gehiago badaude bistaratu hurrengoa
							if (galderak.hurrengo_galdera()) {
								// Aurrera joateko botoia gaitu
								gaitu_botoia("#aurrera");
							} else { // bestela galdera sorta amaitu da
								//alert("Galderak bukatu dira!");
							}
							
							// Erabiltzaileari ez utzi berriz erantzuten
							galderak.desgaitu_erantzunak();
						}
					})
				}
			}
		}
		
		$('#ondo').hide();

		soundManager_konfiguratu();
		
		function hasieratu() {
			// Bukaerako mezua ezkutatu
			$("#ondo").hide();
			
			// Hasteko prestatu
			galderak && galderak.hasieratu();
            amaierako_galderak && amaierako_galderak.hasieratu();
            
		}
		
		hasieratu();
		
        // Hipertranskribapenaren testua bistaratu
		$('#transkribapena-edukia').html(<?php echo $galdera_erantzuna->ikus_entzunezkoa->hipertranskribapena; ?>);
        
        // Hipertranskribapenaren oinarrizko funtzionalitatea hasieratu
        initTranscript(pop);
        
		// Zuzendu botoian klik egitean zuzendu_klik funtzioa deitu
		$("#zuzendu").click(function() {
			zuzendu_klik();
		});
		
		// Aurrera botoian klik egitean aurrera_klik funtzioa deitu
		$("#aurrera").click(function() {
			aurrera_klik();
		});
        
        $("#emaitzak-modala-ados").click(function() {
            
            $("#emaitzak-modala").modal("hide");
            
        });
        
        // Erabiltzaileak transkribapeneko hitz bat klikatzen duenean.
        // Galdera-erantzunen ariketan ez dugu klik gertaera erabiliko,
        // ikasleari ikus-entzunezkoan aurrera eta atzera ibiltzea galarazteko.
		/*$('#transkribapena-edukia').delegate('span','click',function(e){ 
			playSource = true;
			tPause = 0;
			endTime = null;
			
			// Klikatutako hitza bideoko zein momenturi dagokion kalkulatu.
			var jumpTo = $(this).attr(dataMs) / 1000;
			
			// Dagokion momentuan hasi bideoa erreproduzitzen.
			pop.play(jumpTo);
			
			//_gaq.push(['_trackEvent', 'USElect', 'Word clicked', 'word '+$(this).text()]);
			
			return false;
		});*/
	});
</script>

<h2><?php echo $galdera_erantzuna->izena; ?></h2>

<div id="azalpena">
    <?php echo $galdera_erantzuna->azalpena; ?>
</div>

<?php if (count($galdera_erantzuna->dokumentuak) > 0) { ?>
    
    <div id="ariketa-dokumentuak">
        <div>Ariketa honen dokumentuak:</div>
        <ul>
        <?php foreach ($galdera_erantzuna->dokumentuak as $dokumentua) { ?>
            <li><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></li>
        <?php } ?>
        </ul>
    </div>
    
<?php } ?>

<div id="transkribapena-edukinontzia" class="col-md-12">
	
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
	
    <?php
        // Hipertranskribapena dagoenean bakarrik bistaratu #transkribapena-edukia.
        if ($galdera_erantzuna->ikus_entzunezkoa->hipertranskribapena != "\"\"") {
    ?>
	<div id="transkribapena-edukia"></div>
    <?php } ?>
    
</div>

<div id="galderak-modala" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                
                <div id="goikoa">
                    <span id="emaitzak">
                        <span id="zuzenak_span">
                            <img id="zuzenak_img" src="<?php echo URL_BASE; ?>img/galdera_erantzunak/zuzen.png">
                            <span id="zuzenak"></span>
                        </span>
                        
                        <span id="okerrak_span">
                            <img id="okerrak_img" src="<?php echo URL_BASE; ?>img/galdera_erantzunak/oker.png">
                            <span id="okerrak"></span>
                        </span>
                    </span>
                    <span id="zenbagarrena">
                        <span id="unekoa"></span>/<span id="guztira"></span>
                    </span>
                </div>
                
            </div>
            
            <div class="modal-body">    
                <div id="edukiak">
                    
                    <div id="galdera_kontainer">
                        <span id="galdera" data-id=""></span>
                    </div>
                    
                    <div id="erantzunak"></div>
                    
                    <div id="container">
                        <div id="chaptersMap"></div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button id="zuzendu" type="button" class="btn btn-default">Zuzendu</button>
                <button id="aurrera" type="button" class="btn btn-primary">Aurrera</button>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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