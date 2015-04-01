<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>multzokatu">Multzokatu</a> > <?php echo elementuaren_testua("ariketak", "izena", $id_ariketa, $hizkuntza["id"]); ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo URL_BASE_ADMIN ?>multzokatu"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
			<a class="btn" href="<?php echo $url_base . "form" . $url_param . "&id_ariketa=" . $id_ariketa; ?>">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<?php $klassak = array ('', 'class="info"'); ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Taldea</th>
			<th width="130">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($elementuak as $elem) {
		?>
		<tr <?php echo current ($klassak); ?>>
			<td class="td_klik"><?php echo elementuaren_testua("multzokatu_taldeak", "izena", $elem["id"], $hizkuntza["id"]); ?></td>
			<td class="td_aukerak">
                <a class="btn" data-toggle="tooltip" title="elementuak" href="<?php echo $url_base; ?>elementuak?id_ariketa=<?php echo $id_ariketa; ?>&id_taldea=<?php echo $elem['id']; ?>"><i class="icon-list"></i></a>
				<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param . ""; ?>&id_ariketa=<?php echo $id_ariketa; ?>&edit_id=<?php echo $elem["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&id_ariketa=<?php echo $id_ariketa; ?>&ezab_id=<?php echo $elem["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
			</td>
		</tr>
		<?php if (!next ($klassak)) reset ($klassak); } ?>
	</tbody>
</table>

<?php
	// Ponemos los indices de la paginacion en caso de que haya mas de una pagina
	if ($orrikapena["numPags"] > 1) {
		orrikapen_indizeak ($orrikapena, $url_base);
	}
?>