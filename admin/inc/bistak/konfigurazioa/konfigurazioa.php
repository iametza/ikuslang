<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;

		return (confirm ("Gorde konfigurazioa?"));
	}
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Bestelakoak</div>
	</div>
</div>

<?php if (trim ($mezua) != ""){ ?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $mezua; ?>
	</div>
<?php } ?>

<div class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form"; ?>" class="form-horizontal" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		
		<fieldset>
			<legend><strong>Epostak</strong></legend>

			<div class="control-group">
				<label class="control-label" for="email_harremanetarako">Harremanetarako:</label>
				<div class="controls">
					<input class="input-xxlarge" type="text" id="email_harremanetarako" name="email_harremanetarako" value="<?php echo testu_formatua_input ($email_harremanetarako); ?>" />
				</div>
			</div>
			
			<?php /*<div class="control-group">
				<label class="control-label" for="email_abisuak">Abisuak:</label>
				<div class="controls">
					<input class="input-xxlarge" type="text" id="email_abisuak" name="email_abisuak" value="<?php echo testu_formatua_input ($email_abisuak); ?>" />
				</div>
			</div>*/ ?>
			<input type="hidden" id="email_abisuak" name="email_abisuak" value="<?php echo testu_formatua_input ($email_abisuak); ?>" />
		</fieldset>
		
		<div class="control-group text-center">
			<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
			<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
		</div>
	</form>
</div>
