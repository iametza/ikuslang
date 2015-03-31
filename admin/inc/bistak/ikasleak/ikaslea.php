<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;
        
        var pasahitza = $("#pasahitza").val();
        var pasahitza2 = $("#pasahitza2").val();
        
        <?php if ($edit_id) { // Ikaslea editatzen ari bagara pasahitzak hautazkoak dira... ?>
        // Erabiltzaileak pasahitza aldatu nahi badu pasahitzak bat datozela egiaztatu.
        if ((pasahitza && pasahitza2)  && pasahitza !== pasahitza2) {
            
            alert("Pasahitzak ez datoz bat.");
            
            return false;
        }
        <?php } else { // ikasle berria bada pasahitzak derrigorrezkoak dira... ?>
        // Pasahitzen eremuak ez daudela hutsik egiaztatu.
        if (!pasahitza && !pasahitza2) {
            
            alert("Pasahitzak derrigorrezkoak dira.");
            
            return false;
        }
        
        // Pasahitzak bat datozela egiaztatu.
        if (pasahitza !== pasahitza2) {
            
            alert("Pasahitzak ez datoz bat.");
            
            return false;
            
        }
        
        <?php } ?>
        
		return (confirm ("Ikaslea gorde?"));
	}
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>ikasleak">Ikasleak</a> > <?php if ($edit_id) { echo $ikaslea->izena . " " . $ikaslea->abizenak; } else { echo "Gehitu berria"; } ?></div>
		
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
			<div class="control-group">
				<label for="izena">Izena:</label>
				<input class="input-xxlarge" type="text" id="izena" name="izena" value="<?php echo testu_formatua_input ($ikaslea->izena); ?>" />
			</div>
            <div class="control-group">
				<label for="abizenak">Abizenak:</label>
				<input class="input-xxlarge" type="text" id="abizenak" name="abizenak" value="<?php echo testu_formatua_input ($ikaslea->abizenak); ?>" />
			</div>
            <div class="control-group">
				<label for="e_posta">e-posta:</label>
				<input class="input-xxlarge" type="text" id="e_posta" name="e_posta" value="<?php echo testu_formatua_input ($ikaslea->e_posta); ?>" />
			</div>
            <div class="control-group">
				<label for="pasahitza"><?php if ($edit_id) { echo "Aldatu pasahitza:"; } else { echo "Pasahitza: "; } ?></label>
				<input class="input-xxlarge" type="password" id="pasahitza" name="pasahitza" value="" />
			</div>
            <div class="control-group">
				<label for="pasahitza2">Berretsi pasahitza:</label>
				<input class="input-xxlarge" type="password" id="pasahitza2" name="pasahitza2" value="" />
			</div>
		</fieldset>
		
		<div class="control-group text-center">
			<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
			<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
		</div>
	</form>
</div>