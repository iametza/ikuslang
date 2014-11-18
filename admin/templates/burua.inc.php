<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			
			<div class="brand">Ikuslang :: Administrazioa</div>
			
			<div class="nav-collapse collapse">				
				<ul class="nav">
					<li class="active"><a href="<?php echo URL_BASE_ADMIN; ?>"><i class="icon-home"></i>&nbsp;Hasiera</a></li>
					<?php if($erabiltzailea->get_rola() == 'admin'){?>
					<li class="dropdown active">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i>&nbsp;Konfigurazioa&nbsp;<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo URL_BASE_ADMIN; ?>konfigurazioa">Bestelakoak</a></li>
							<li><a href="<?php echo URL_BASE_ADMIN; ?>administrazioa">Erabiltzaileak</a></li>
						</ul>
					</li>
					<?php }?>
					<?php /*<li class="active"><a href="#"><i class="icon-asterisk"></i>&nbsp;Laguntza</a></li>*/ ?>
                    <li class="active"><a href="<?php echo URL_BASE; ?>" target="_blank"><i class="icon-globe"></i>&nbsp;Ikusi webgunea</a></li>
					<li class="active"><a href="<?php echo URL_BASE_ADMIN; ?>logout"><i class="icon-off"></i>&nbsp;Irten</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
