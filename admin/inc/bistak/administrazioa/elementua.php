<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;
		if (document.f1.erabiltzailea.value.match (patroi_hutsik) != null){
			alert ("Erabiltzaile eremua betetzea derrigorrezkoa da.");
			document.f1.erabiltzailea.focus ();
			return (false);
		}

		if (document.f1.p1.value.match (patroi_hutsik) != null){
			alert ("Pasahitza eremua betetzea derrigorrezkoa da");
			document.f1.p1.focus ();
			return (false);
		}

		if (document.f1.p2.value.match (patroi_hutsik) != null){
			alert ("Konfirmazio eremua betetzea derrigorrezkoa da");
			document.f1.p2.focus ();
			return (false);
		}

		if (document.f1.p1.value != document.f1.p2.value){
			alert ("Pasahitza eta konfirmazioa ez dira berdinak");
			document.f1.p1.value = "";
			document.f1.p2.value = "";
			document.f1.p1.focus ();
			return (false);
		}

		return (confirm ("Gorde erabiltzailea?"));
	}
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Erabiltzaileak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<?php if ($erab_existitzen){ ?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Erabiltzailea existitzen da, ezin da errepikatu.
	</div>
<?php } ?>

<div class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="erabiltzailea">Erabiltzailea:</label>
				<div class="controls">
					<input class="input-xxlarge" type="text" id="erabiltzailea" name="erabiltzailea" value="<?php echo testu_formatua_input ($erab); ?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="p1">Pasahitza:</label>
				<div class="controls">
					<input class="input-xxlarge" type="password" id="p1" name="p1" value="<?php echo $pasahitza; ?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="p2">Pasahitza konfirmatu:</label>
				<div class="controls">
					<input class="input-xxlarge" type="password" id="p2" name="p2" value="<?php echo $pasahitza; ?>" />
				</div>
			</div>
		</fieldset>
		
		<div class="control-group text-center">
			<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
			<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
		</div>
	</form>
</div>
