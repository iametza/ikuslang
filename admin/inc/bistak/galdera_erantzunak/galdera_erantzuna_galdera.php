<link type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/ikus_entzunezkoak.css" rel="stylesheet" />

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>galdera-erantzunak">Galdera-erantzunak</a> > <?php echo elementuaren_testua("ariketak", "izena", $id_ariketa, $hizkuntza["id"]); ?> > <?php if($edit_id) { echo "Editatu galdera"; } else { echo "Gehitu galdera"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . "?id_ariketa=" . $id_ariketa; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
    
    <fieldset>
        <legend><strong>Ikus-entzunezkoa</strong></legend>
        
        <div>OHARRA: Ikus-entzunezko bat gehitu edo aldatzeko erabili ariketa honen formularioa.</div>
        
        <?php if ($galdera->ikus_entzunezkoa->mota == "bideoa") { ?>
        <div>
            <video id="bideoa-aurrebista-erreproduktorea" controls>
                <source id="bideoa-aurrebista-erreproduktorea-mp4" src="<?php echo URL_BASE . $galdera->ikus_entzunezkoa->bideo_path . $galdera->ikus_entzunezkoa->bideo_mp4; ?>" type="video/mp4"></source>
                <source id="bideoa-aurrebista-erreproduktorea-webm"  src="<?php echo URL_BASE . $galdera->ikus_entzunezkoa->bideo_path . $galdera->ikus_entzunezkoa->bideo_webm; ?>" type="video/webm"></source>
            </video>
        </div>
        <?php } else if ($galdera->ikus_entzunezkoa->mota == "audioa") { ?>
        <div>
            <audio id="audioa-aurrebista-erreproduktorea" controls>
                <source id="audioa-aurrebista-erreproduktorea-mp3" src="<?php echo URL_BASE . $galdera->ikus_entzunezkoa->audio_path . $galdera->ikus_entzunezkoa->audio_mp3; ?>" type="audio/mpeg"></source>
                <source id="audioa-aurrebista-erreproduktorea-ogg"  src="<?php echo URL_BASE . $galdera->ikus_entzunezkoa->audio_path . $galdera->ikus_entzunezkoa->audio_ogg; ?>" type="audio/ogg"></source>
            </audio>
        </div>
        <? } else { ?>
        <div>
            <div>Galdera-erantzun ariketa honek ez dauka ikus-entzunezkorik.</div>
        </div>
        <?php } ?>
    </fieldset>
    
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="id_ariketa" value="<?php echo $id_ariketa; ?>" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		
		<?php
			foreach (hizkuntza_idak() as $h_id){
		?>
		<fieldset>
			<legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
			
			<div class="control-group">
				<label for="galdera_<?php echo $h_id; ?>">Galdera:</label>
				<input class="input-xxlarge" type="text" id="galdera_<?php echo $h_id; ?>" name="galdera_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input($galdera->hizkuntzak[$h_id]->galdera); ?>" />
			</div>
            
            <fieldset>
                <legend><strong>Noiz bistaratu behar da?</strong></legend>
                
                <div class="control-group">
                    <label>
                        <input type="radio" name="galdera_noiz_<?php echo $h_id; ?>" id="bideoan_zehar_<?php echo $h_id; ?>" value="bideoan_zehar" <?php if ($galdera->hizkuntzak[$h_id]->denbora != -1) { echo checked; } ?>>
                        Bideoan zehar (hh:mm:ss)
                    </label>
                    <input class="input-xxlarge denbora" type="text" id="denbora_<?php echo $h_id; ?>" name="denbora_<?php echo $h_id; ?>" value="<?php if ($galdera->hizkuntzak[$h_id]->denbora && $galdera->hizkuntzak[$h_id]->denbora != -1) { echo segundoetatikHHMMSSra($galdera->hizkuntzak[$h_id]->denbora); } ?>" />
                    
                    <label>
                        <input type="radio" name="galdera_noiz_<?php echo $h_id; ?>" id="bideoa_amaitzean_<?php echo $h_id; ?>" value="bideoa_amaitzean" <?php if ($galdera->hizkuntzak[$h_id]->denbora && $galdera->hizkuntzak[$h_id]->denbora == -1) { echo checked; } ?>>
                        Bideoa amaitzean
                    </label>
                </div>
            </fieldset>
            
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

<script type="text/javascript">
    
    <?php
        
        function segundoetatikHHMMSSra($segundoak) {
          $t = round($segundoak);
          return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
        }
        
    ?>
    
    function egiaztatuHHMMSS($denbora) {
        
        // Denbora hh:mm:ss formatuan dagoela egiaztatuko dugu.
        if(/(?:[0-1]?[0-9]|[2][1-4]):[0-5]?[0-9]:[0-5]?[0-9]\s?/.test($denbora.val()) === false) {
            
            // Erabiltzaileari denboraren formatua ez dela egokia jakinarazi.
            alert("Denborak hh:mm:ss formatuan egon behar du.");
            
            // Fokua denboraren testu-koadroan jarri.
            $denbora.focus();
            
            return false;
		}
        
        return true;
    }
    
	function verif() {
        
		var patroi_hutsik = /^\s*$/;
        
        // Denborak formatu egokian dauden ala ez adierazten du.
        var ondo = true;
        
        // Hizkuntza desberdinetako denborak formatu egokian daudela egiaztatuko ditugu.
        $(".denbora").each(function() {
            
            // Radio botoiaren hautatzaile hau #ezdatsegit baina beno.
            if ($(this).prev().children().is(':checked')) {
                
                ondo = egiaztatuHHMMSS($(this));
                
                // Formatua ez badago ondo each-etik irtengo gara.
                if (!ondo) {
                    
                    return false;
                    
                }
                
            }
            
        });
        
        // Hizkuntza batetako denboraren formatua ez badago ondo ez dugu formularioa bidaliko.
        if (!ondo) {
            
            return false;
            
        }
        
		return (confirm("Ziur zaude galdera gorde nahi duzula?"));
	}
</script>