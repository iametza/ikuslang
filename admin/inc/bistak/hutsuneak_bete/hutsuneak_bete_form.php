<link type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/ikus_entzunezkoak.css" rel="stylesheet" />

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo $url_base; ?>">Hutsuneak bete</a> > <?php if ($edit_id) { echo $hutsuneak_bete->hizkuntzak[$h_id]->izena; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
    <form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
        <input type="hidden" name="gorde" value="BAI" />
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
        
        <fieldset>
            <legend><strong>Ikus-entzunezkoa</strong></legend>
            
            <div class="control-group">
			    <label for="konbo-ikus-entzunezkoak">Aukeratu:</label>
			    <select id="konbo-ikus-entzunezkoak" class="input-xxlarge" name="ikus-entzunezkoa">
                    <?php if (!$hutsuneak_bete->ikus_entzunezkoa->id) { ?>
                    <option disabled selected value="">HAUTATU IKUS-ENTZUNEZKOA</option>
                    <?php } ?>
					<?php foreach($ikus_entzunezkoak as $ikus_entzunezkoa){?>
					<option <?php if($ikus_entzunezkoa['id'] == $hutsuneak_bete->ikus_entzunezkoa->id){?>selected="selected"<?php }?> value="<?=$ikus_entzunezkoa['id']?>" ><?php echo $ikus_entzunezkoa['izenburua'] . " [" . $ikus_entzunezkoa["mota"] . "]"; ?></option>
					<?php }?>
				</select>
			   
			</div>
            
            <div>
                
                <div>
                    <video id="bideoa-aurrebista-erreproduktorea" controls<?php if ($hutsuneak_bete->ikus_entzunezkoa->mota != "bideoa") { echo " style='display:none'";} ?>>
                        <source id="bideoa-aurrebista-erreproduktorea-mp4" src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->bideo_path . $hutsuneak_bete->ikus_entzunezkoa->bideo_mp4; ?>" type="video/mp4"></source>
                        <source id="bideoa-aurrebista-erreproduktorea-webm"  src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->bideo_path . $hutsuneak_bete->ikus_entzunezkoa->bideo_webm; ?>" type="video/webm"></source>
                    </video>
                </div>
                
                <div>
                    <audio id="audioa-aurrebista-erreproduktorea" controls<?php if ($hutsuneak_bete->ikus_entzunezkoa->mota != "audioa") { echo " style='display:none'";} ?>>
                        <source id="audioa-aurrebista-erreproduktorea-mp3" src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->audio_path . $hutsuneak_bete->ikus_entzunezkoa->audio_mp3; ?>" type="audio/mpeg"></source>
                        <source id="audioa-aurrebista-erreproduktorea-ogg"  src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->audio_path . $hutsuneak_bete->ikus_entzunezkoa->audio_ogg; ?>" type="audio/ogg"></source>
                    </audio>
                </div>
                
                <div id="ikus-entzunezkorik-ez"<?php if ($hutsuneak_bete->ikus_entzunezkoa) { echo " style='display:none;'"; } ?>>Galdera-erantzun ariketa honek ez dauka ikus-entzunezkorik oraindik.</div>
                
            </div>
            
        </fieldset>
        
        <fieldset>
            <legend><strong>Dokumentuak</strong></legend>
            
            <div class="control-group">
			    <select id="konbo-dokumentuak" class="input-xxlarge" name="dokumentuak[]" multiple="multiple">
					<?php foreach($dokumentuak as $dokumentua){?>
                        <option 
                        <?php if ($hutsuneak_bete->dokumentuak) {
                            foreach($hutsuneak_bete->dokumentuak as $ariketaren_dokumentua) { ?>
                            <?php if($ariketaren_dokumentua->id == $dokumentua["id"]){?>selected="selected"<?php }?>
                        <?php
                            }
                        }
                        ?>
                        value="<?=$dokumentua['id']?>" ><?php echo $dokumentua['izenburua']; ?></option>
					<?php }?>
				</select>
			   
			</div>
            
            <div id="dokumentuak-zerrenda">
                <?php if (count($hutsuneak_bete->dokumentuak) > 0) { ?>
                    <?php foreach ($hutsuneak_bete->dokumentuak as $dokumentua) { ?>
                    <div><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></div>
                    <?php } ?>
                    
                <? } else { ?>
                    <div>Esaldiak zuzendu ariketa honek ez dauka dokumenturik oraindik.</div>
                <?php } ?>
            </div>
        </fieldset>
        
        <?php
            foreach (hizkuntza_idak() as $h_id){
        ?>
        <fieldset>
            
            <legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
            
            <div class="control-group">
                <label for="izena_<?php echo $h_id; ?>">Izena:</label>
                <input class="input-xxlarge" type="text" id="izena_<?php echo $h_id; ?>" name="izena_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($hutsuneak_bete->hizkuntzak[$h_id]->izena); ?>" />
            </div>
            
            <div class="control-group">
				<label for="azalpena_<?php echo $h_id; ?>">Azalpena:</label>
				<textarea class="input-xxlarge" id="azalpena_<?php echo $h_id; ?>" name="azalpena_<?php echo $h_id; ?>"><?php echo testu_formatua_input($hutsuneak_bete->hizkuntzak[$h_id]->azalpena); ?></textarea>
			</div>
            
            <div class="control-group">
                <label for="etiketak_<?php echo $h_id; ?>">Etiketak:</label>
                <input id="etiketak_<?php echo $h_id; ?>" name="etiketak_<?php echo $h_id; ?>" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
            </div>
            
        </fieldset>
        <?php
            }
        ?>
        
        <div class="control-group text-center">
            <button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
            <button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
        </div>
    </form>
</div>

<script src="<?php echo URL_BASE; ?>js/chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    
	function verif(){
		var patroi_hutsik = /^\s*$/;
		
		return (confirm ("Ariketa gorde nahi duzu?"));
	}
    
	$(document).ready(function() {
		
        $("#konbo-ikus-entzunezkoak").chosen().change(function() {
            
            $.ajax({
                
                type: "GET",
                dataType: "json",
                url: "<?php echo URL_BASE; ?>API/v1/ikus-entzunezkoak/" + $(this).val()
                
            }).done(function(data, textStatus, jqXHR) {
                
                if (data.mota === "audioa") {
                    
                    // Audioaren src-ak eguneratu.
                    $("#audioa-aurrebista-erreproduktorea-mp3").attr('src', '<?php echo URL_BASE; ?>' + data.audio_path + data.audio_mp3);
                    $("#audioa-aurrebista-erreproduktorea-ogg").attr('src', '<?php echo URL_BASE; ?>' + data.audio_path + data.audio_ogg);
                    
                    // Hau gabe src-ak aldatzen dira baina aurreko bideoa ikusten da.
                    $("#audioa-aurrebista-erreproduktorea")[0].load();
                    
                    $("#audioa-aurrebista-erreproduktorea").show();
                    $("#bideoa-aurrebista-erreproduktorea").hide();
                    $("#ikus-entzunezkorik-ez").hide();
                    
                } else if (data.mota === "bideoa") {
                    
                    // Bideoaren src-ak eguneratu.
                    $("#bideoa-aurrebista-erreproduktorea-mp4").attr('src', '<?php echo URL_BASE; ?>' + data.bideo_path + data.bideo_mp4);
                    $("#bideoa-aurrebista-erreproduktorea-webm").attr('src', '<?php echo URL_BASE; ?>' + data.bideo_path + data.bideo_webm);
                    
                    // Hau gabe src-ak aldatzen dira baina aurreko bideoa ikusten da.
                    $("#bideoa-aurrebista-erreproduktorea")[0].load();
                    
                    $("#audioa-aurrebista-erreproduktorea").hide();
                    $("#bideoa-aurrebista-erreproduktorea").show();
                    $("#ikus-entzunezkorik-ez").hide();
                    
                } else {
                    
                    console.log("Ikus-entzunezko mota ezezaguna!");
                    
                }
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                
                console.log("Errore bat gertatu da zerbitzaritik bideoaren datuak eskuratzean.");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                
            });
            
        });
        
        $("#konbo-dokumentuak").chosen({
            placeholder_text_multiple : "Aukeratu dokumentua(k)...",
            no_results_text : "Emaitzarik ez"
        }).change(function() {
            
            // Dokumentuen zerrenda garbitu
            $("#dokumentuak-zerrenda").empty();
            
            // Zerrendako elementu guztiak kargatu.
            $("#konbo-dokumentuak option:selected").each(function() {
                
                $.ajax({
                    
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo URL_BASE; ?>API/v1/dokumentuak/" + $(this).val()
                    
                }).done(function(data, textStatus, jqXHR) {
                    
                    $("#dokumentuak-zerrenda").append("<div><a href='<?php echo URL_BASE; ?>" + data.path_dokumentua + data.dokumentua + "'>" + data.izenburua + "</a></div>");
                    
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    
                    console.log("Errore bat gertatu da zerbitzaritik dokumentuen datuak eskuratzean.");
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    
                });
            });
        });
        
        // Ariketa honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            id: '<?php echo $edit_id; ?>',
            mota: 'ariketa'
        });
        
	});
	
</script>