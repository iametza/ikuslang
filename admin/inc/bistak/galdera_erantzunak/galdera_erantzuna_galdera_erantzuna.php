<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;

		return (confirm ("Erantzuna gorde?"));
	}
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>galdera-erantzunak">Galdera-erantzunak</a> > <?php echo elementuaren_testua("ariketak", "izena", $id_ariketa, $hizkuntza["id"]); ?> > <?php if($edit_id) { echo "Editatu erantzuna"; } else { echo "Gehitu erantzuna"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . "?id_ariketa=" . $id_ariketa . "&id_galdera=" . $id_galdera; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="id_ariketa" value="<?php echo $id_ariketa; ?>" />
        <input type="hidden" name="id_galdera" value="<?php echo $id_galdera; ?>" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		
		<?php
			foreach (hizkuntza_idak() as $h_id){
		?>
		<fieldset>
			<legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
			
			<div class="control-group">
				<label for="erantzuna_<?php echo $h_id; ?>">Erantzuna:</label>
				<input class="input-xxlarge" type="text" id="erantzuna_<?php echo $h_id; ?>" name="erantzuna_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input($erantzuna->hizkuntzak[$h_id]->erantzuna); ?>" />
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