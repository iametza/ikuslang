<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;
		
		return (confirm ("Ariketa gorde nahi duzu?"));
	}
	
	$(document).ready(function() {
		
        // Ariketa honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            id: '<?php echo $edit_id; ?>',
            mota: 'ariketa'
        });
        
	});
	
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo $url_base; ?>">Hutsuneak bete</a> > <?php echo $hutsuneak_bete->hizkuntzak[$h_id]->izena; ?></div>
		
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
            
            <!-- <div>IKUS-ENTZUNEZKOA HAUTATU ETA ALDATZEKO AUKERA EGON BEHAR LUKE HEMEN!!!!</div> -->
            
            <?php if ($hutsuneak_bete->ikus_entzunezkoa->mota == "bideoa") { ?>
            <div>
                <video id="bideoa-aurrebista-erreproduktorea" controls>
                    <source id="bideoa-aurrebista-erreproduktorea-mp4" src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->bideo_path . $hutsuneak_bete->ikus_entzunezkoa->bideo_mp4; ?>" type="video/mp4"></source>
                    <source id="bideoa-aurrebista-erreproduktorea-webm"  src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->bideo_path . $hutsuneak_bete->ikus_entzunezkoa->bideo_webm; ?>" type="video/webm"></source>
                </video>
            </div>
            <?php } else if ($hutsuneak_bete->ikus_entzunezkoa->mota == "audioa") { ?>
            <div>
                <audio id="audioa-aurrebista-erreproduktorea" controls>
                    <source id="audioa-aurrebista-erreproduktorea-mp3" src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->audio_path . $hutsuneak_bete->ikus_entzunezkoa->audio_mp3; ?>" type="audio/mpeg"></source>
                    <source id="audioa-aurrebista-erreproduktorea-ogg"  src="<?php echo URL_BASE . $hutsuneak_bete->ikus_entzunezkoa->audio_path . $hutsuneak_bete->ikus_entzunezkoa->audio_ogg; ?>" type="audio/ogg"></source>
                </audio>
            </div>
            <? } else { ?>
            <div>
                <div>Hutsuneak bete ariketa honek ez dauka ikus-entzunezkorik oraindik.</div>
            </div>
            <?php } ?>
        </fieldset>
        
        <fieldset>
            <legend><strong>Dokumentuak</strong></legend>
            
            <!-- <div>DOKUMENTUAK HAUTATU ETA ALDATZEKO AUKERA EGON BEHAR LUKE HEMEN!!!!</div> -->
            
            <?php if (count($hutsuneak_bete->dokumentuak) > 0) { ?>
                
                <?php foreach ($galdera_erantzuna->dokumentuak as $dokumentua) { ?>
                <a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a>
                <?php } ?>
                
            <? } else { ?>
            <div>
                <div>Hutsuneak bete ariketa honek ez dauka dokumenturik oraindik.</div>
            </div>
            <?php } ?>
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