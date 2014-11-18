<h1 style="display:none;"><?php echo $hto->motz ("meta_title"); ?></h1>

<div class="row clearfix">
	<div class="col-md-12 column">
		<div class="row clearfix burua">
			<div class="col-md-6 column"><a href="<?php echo URL_BASE . $hto->nice("menu_nire_txokoa"); ?>" style="border: none;"><img alt="IKA" src="<?php echo URL_BASE; ?>img/logoa.png" class="zabalera_osoa_img"></a></div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-12 column">
				<nav class="navbar navbar-default menua" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Menua</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><?php/* <a class="navbar-brand" href="<?php echo URL_BASE; ?>"><?php echo $hto->motz ("menu_hasiera"); ?></a>*/ ?>
					</div>
					
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li<?php echo $menu_aktibo == 'nire-txokoa' ? ' class="active"' : ''; ?>><a href="<?php echo URL_BASE . $hto->nice("menu_nire_txokoa"); ?>"><?php echo $hto->motz("menu_nire_txokoa"); ?></a></li>
                            <li<?php echo $menu_aktibo == 'ezarpenak' ? ' class="active"' : ''; ?>><a href="<?php echo URL_BASE . $hto->nice("menu_ezarpenak"); ?>"><?php echo $hto->motz("menu_ezarpenak"); ?></a></li>
                            <li<?php echo $menu_aktibo == 'amaitu-saioa' ? ' class="active"' : ''; ?>><a href="<?php echo URL_BASE . $hto->nice("menu_amaitu_saioa"); ?>"><?php echo $hto->motz("menu_amaitu_saioa"); ?></a></li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
	</div>
</div>
<?php if($mezua!=""){ ?>
<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="alert alert-danger"><?=$mezua?></div>
		</div>
</div>
<?php } ?>