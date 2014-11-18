<?php if($erabiltzailea->get_rola() == 'admin'){?>
<div class="span2">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Atal nagusiak</li>
            <li<?php echo $menu_aktibo == "mailak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>mailak">MAILAK</a></li>
            <li<?php echo $menu_aktibo == "ikasgelak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikasgelak">IKASGELAK</a></li>
            <li<?php echo $menu_aktibo == "irakasleak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>irakasleak">IRAKASLEAK</a></li>
            <li<?php echo $menu_aktibo == "ikasleak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikasleak">IKASLEAK</a></li>
            <?php /*<li<?php echo $menu_aktibo == "ikus-entzunezkoak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikus-entzunezkoak">IKUS-ENTZUNEZKOAK</a></li>*/?>
	     <li><a data-toggle="collapse" data-target="#collapse2" href="#">IKUS-ENTZUNEZKOAK<i class="icon-chevron-down"></i></a>
				<div id="collapse2" class="collapse menua">
					<ul class="nav nav-list">
                        <li<?php echo $menu_aktibo == "fitxategiak-igo" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>fitxategiak-igo">Fitxategiak igo</a></li>
                        <li<?php echo $menu_aktibo == "ikus-entzunezkoak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikus-entzunezkoak">Zerrenda</a></li>
                       
                    </ul>
                </div>
            </li>
            <li<?php echo $menu_aktibo == "dokumentuak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>dokumentuak">DOKUMENTUAK</a></li>
            <li><a data-toggle="collapse" data-target="#collapse1" href="#">ARIKETAK&nbsp;<i class="icon-chevron-down"></i></a>
				<div id="collapse1" class="collapse menua">
					<ul class="nav nav-list">
                        <li<?php echo $menu_aktibo == "esaldiak-zuzendu" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>esaldiak-zuzendu">Esaldiak zuzendu</a></li>
                        <li<?php echo $menu_aktibo == "galdera-erantzunak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>galdera-erantzunak">Galdera-erantzunak</a></li>
                        <li<?php echo $menu_aktibo == "hitzak-markatu" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>hitzak-markatu">Hitzak markatu</a></li>
                        <li<?php echo $menu_aktibo == "hutsuneak-bete" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>hutsuneak-bete">Hutsuneak bete</a></li>
                        <li<?php echo $menu_aktibo == "multzokatu" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>multzokatu">Multzokatu</a></li>
                    </ul>
                </div>
            </li>
		</ul>
	</div>
</div>
<?php } else{?>

<div class="span2">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Atal nagusiak</li>
                      <li<?php echo $menu_aktibo == "ikasgelak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikasgelak">NIRE IKASGELAK</a></li>
            
            <li<?php echo $menu_aktibo == "ikasleak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikasleak">IKASLEAK</a></li>
            <li<?php echo $menu_aktibo == "ikus-entzunezkoak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>ikus-entzunezkoak">IKUS-ENTZUNEZKOAK</a></li>
	   
            <li<?php echo $menu_aktibo == "dokumentuak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>dokumentuak">DOKUMENTUAK</a></li>
            <li><a data-toggle="collapse" data-target="#collapse1" href="#">ARIKETAK&nbsp;<i class="icon-chevron-down"></i></a>
				<div id="collapse1" class="collapse menua">
					<ul class="nav nav-list">
                        <li<?php echo $menu_aktibo == "esaldiak-zuzendu" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>esaldiak-zuzendu">Esaldiak zuzendu</a></li>
                        <li<?php echo $menu_aktibo == "galdera-erantzunak" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>galdera-erantzunak">Galdera-erantzunak</a></li>
                        <li<?php echo $menu_aktibo == "hitzak-markatu" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>hitzak-markatu">Hitzak markatu</a></li>
                        <li<?php echo $menu_aktibo == "hutsuneak-bete" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>hutsuneak-bete">Hutsuneak bete</a></li>
                        <li<?php echo $menu_aktibo == "multzokatu" ? ' class="active"' : ''?>><a href="<?php echo URL_BASE_ADMIN; ?>multzokatu">Multzokatu</a></li>
                    </ul>
                </div>
            </li>
		</ul>
	</div>
</div>
<?php }?>