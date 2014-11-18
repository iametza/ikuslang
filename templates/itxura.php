<!DOCTYPE html>
<html lang="<?php echo $hizkuntza["gako"]; ?>">
<head>
	
<?php require ("templates/head.inc.php"); ?>

</head>

<body>
	
<div class="container gorputza">
	<?php require ("templates/burua.inc.php"); ?>
	
	<div class="row clearfix">
		<div class="col-md-12 column">
			<?php if (isset ($content) && is_file ($content)){ ?>
		
				<?php require_once $content; ?>
				
			<?php } else{ ?>
			
				<h2><?php echo $hto->motz ("orok_notfound"); ?></h2>
				
				<?php echo $hto->luze ("orok_notfound"); ?>
				
			<?php } ?>
		</div>
	</div>
	
	<?php require ("templates/oina.inc.php"); ?>
	
</div>

</body>
</html>
