<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Erabiltzaileak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base; ?>form">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<?php $klassak = array ('', 'class="info"'); ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Erabiltzailea</th>
			<th width="85">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($elementuak as $elem){ ?>
		<tr <?php echo current ($klassak); ?>>
			<td class="td_klik"><?php echo $elem["erabiltzailea"]; ?></td>
			<td class="td_aukerak">
				<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=<?php echo $elem["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=<?php echo $elem["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
			</td>
		</tr>
		<?php if (!next ($klassak)) reset ($klassak); } ?>
	</tbody>
</table>

<?php
	// Ponemos los indices de la paginacion en caso de que haya mas de una pagina
	if ($orrikapena["numPags"] > 1)
		orrikapen_indizeak ($orrikapena, $url_base);
?>
