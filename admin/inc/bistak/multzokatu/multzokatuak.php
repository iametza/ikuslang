<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Multzokatu</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base; ?>form">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<?php $klassak = array ('', 'class="info"'); ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
            <th width="100">Egoera</th>
			<th>Izena</th>
			<th width="130">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($elementuak as $elem){
		?>
		<tr <?php echo current ($klassak); ?>>
            <td>
                <select class="input" name="egoera_<?php echo $elem["id"]; ?>" onchange="javascript:document.location='<?php echo $url_base . $url_param; ?>&aldatu_egoera_id=<?php echo $elem["id"]; ?>&bal=' + this.options[this.selectedIndex].value;">
                    <option value="0"<?php echo $elem["egoera"] == 0 ? " selected" : ""; ?>>Zirriborroa</option>
                    <option value="1"<?php echo $elem["egoera"] == 1 ? " selected" : ""; ?>>Publiko</option>
                </select>
            </td>
			<td class="td_klik"><?php echo elementuaren_testua("ariketak", "izena", $elem["id"], $hizkuntza["id"]); ?></td>
			<td class="td_aukerak">
                <a class="btn" data-toggle="tooltip" title="taldeak" href="<?php echo $url_base; ?>taldeak?id_ariketa=<?php echo $elem['id']; ?>"><i class="icon-list"></i></a>
				<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=<?php echo $elem["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=<?php echo $elem["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
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