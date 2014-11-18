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
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>multzokatu">Multzokatu</a> > <?php if ($edit_id) { echo $multzokatu->hizkuntzak[$h_id]->izena; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		
		<?php
			foreach (hizkuntza_idak() as $h_id){
		?>
		<fieldset>
			<legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
			
			<div class="control-group">
				<label for="izena_<?php echo $h_id; ?>">Izena:</label>
				<input class="input-xxlarge" type="text" id="izena_<?php echo $h_id; ?>" name="izena_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($multzokatu->hizkuntzak[$h_id]->izena); ?>" />
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