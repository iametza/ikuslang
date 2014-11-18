<!DOCTYPE html>
<html lang="eu" class="no-js">
<head>
	
<?php require ("templates/head.inc.php"); ?>

</head>

<body onload="javascript:document.getElementById('erabiltzailea').focus();">
	
<header id="overview" class="jumbotron subhead">
	
	<div class="container-fluid">
		<div class="row-fluid">
			<h1>Administrazio gunea</h1>
			
			<p class="lead">Ikuslang</p>
		</div>
	</div>
	
</header>

<div class="container-fluid">
	
	<div class="row-fluid">
		
		<div class="span4">&nbsp;</div>
		
		<div class="span4">
			<form id="f1" name="f1" method="post" action="<?php echo URL_BASE_ADMIN; ?>" class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="erabiltzailea">Izena</label>
					<div class="controls">
						<input type="text" id="erabiltzailea" placeholder="Izena" class="required" name="izena" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="pasahitza">Gakoa</label>
					<div class="controls">
						<input type="password" id="pasahitza" placeholder="Gakoa" class="required" name="gakoa" />
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn">Sartu</button>
					</div>
				</div>
			</form>
		</div>
		
		<div class="span4">&nbsp;</div>
		
	</div>
	
	<?php require ("templates/oina.inc.php"); ?>
	
</div>

</body>

</html>
