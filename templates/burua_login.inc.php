<h1 style="display:none;"><?php echo $hto->motz ("meta_title"); ?></h1>

<div class="row clearfix">
	<div class="col-md-12 column">
		<div class="row clearfix">
			<div class="col-md-6 column"><img alt="IKA" src="<?php echo URL_BASE; ?>img/logoa.png" class="zabalera_osoa_img"></div>
		</div>
		
		<?/*<div class="row clearfix">
			<div class="col-md-12 column">
				<nav class="navbar navbar-default" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Menua</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
						<?php/* <a class="navbar-brand" href="<?php echo URL_BASE; ?>"><?php echo $hto->motz ("menu_hasiera"); ?></a>/ ?>
					</div>
					
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
						</ul>
					</div>
				</nav>
			</div>
		</div> */ ?>
	</div>
</div>
<?php if($mezua!=""){ ?>
<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="alert alert-danger"><?=$mezua?></div>
		</div>
</div>
<?php } ?>