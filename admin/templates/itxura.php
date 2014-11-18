<!DOCTYPE html>
<html lang="eu" class="no-js">
<head>
	
<?php require ("templates/head.inc.php"); ?>

</head>

<body>

<?php require ("templates/burua.inc.php"); ?>

<div class="container-fluid">

	<div class="row-fluid">
		<div class="span12">
			<div class="user-zone">
				<span class="user-zone-left"></span>
				
				<span class="user-zone-right">
					<?php echo burua_fetxa (); ?>&nbsp;|&nbsp;
					Logged in: <i class="icon-user"></i> <a href="<?php echo URL_BASE_ADMIN; ?>administrazioa/form?edit_id=<?php echo $erabiltzailea->get_id (); ?>"> <?php echo $erabiltzailea->get_erabiltzailea (); ?></a>
				</span>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<?php require ("templates/menua.inc.php"); ?>
		
		<?php if (isset ($content) && is_file ($content)){ ?>
		
			<div class="span10">
				<?php require_once $content; ?>
			</div>
			
		<?php } else{ ?>
		
			<div class="span10">
				<h1>Errorea: <small>Fitxategia ez da aurkitu.</small></h1>
			</div>
			
		<?php } ?>
	</div>
	
	<?php require ("templates/oina.inc.php"); ?>
</div>

</body>
</html>
