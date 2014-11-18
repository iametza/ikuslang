<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo $url_base; ?>">Dokumentuak</a> > <?php if ($edit_id) { echo $dokumentua->hizkuntzak[$hizkuntza["id"]]->izenburua; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
    <form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
        <input type="hidden" name="gorde" value="BAI" />
        <input id="hidden_edit_id" type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
        
        <fieldset>
            
            <legend><strong>Dokumentua</strong></legend>
            
            <div class="control-group">
                <label for="dokumentua">Dokumentua:</label>
                <input class="input-xxlarge" name="dokumentua" type="file" id="dokumentua" />
                <?php
                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $dokumentua->path_dokumentua . $dokumentua->dokumentua)) {
                        echo "<a href='" . URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua . "' target='_blank'>Ikusi</a>";
                        echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&h_id=" . $h_id . "&ezabatu=DOKUMENTUA\" onClick=\"javascript: return (confirm ('Dokumentua ezabatzea aukeratu duzu. Ziur al zaude?'));\">Ezabatu</a>";
                    }
                ?>
            </div>
            
        </fieldset>
        
        <?php
            foreach (hizkuntza_idak() as $h_id) {
        ?>
        <fieldset>
            
            <legend><strong><?php echo get_dbtable_field_by_id("hizkuntzak", "izena", $h_id); ?></strong></legend>
            
            <div class="control-group">
                <label for="izenburua_<?php echo $h_id; ?>">Izenburua:</label>
                <input class="input-xxlarge" type="text" id="izenburua_<?php echo $h_id; ?>" name="izenburua_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input($dokumentua->hizkuntzak[$h_id]->izenburua); ?>" />
            </div>
            
            <div class="control-group">
                <label for="azalpena_<?php echo $h_id; ?>">Azalpena:</label>
                <textarea class="input-xxlarge" type="text" id="azalpena_<?php echo $h_id; ?>" name="azalpena_<?php echo $h_id; ?>"><?php echo testu_formatua_input($dokumentua->hizkuntzak[$h_id]->azalpena); ?></textarea>
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

<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;
		
		return (confirm ("Gorde elementua?"));
	}
	
    $(document).ready(function() {
		
        // Ikus-entzunezko honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            id: '<?php echo $edit_id; ?>',
            mota: 'dokumentua'
        });
        
    });
</script>