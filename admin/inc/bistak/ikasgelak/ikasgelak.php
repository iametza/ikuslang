<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Ikasgelak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base; ?>form">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<?php $klassak = array ('', 'class="info"'); ?>
<table id="nire_ikasgelak" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Izena</th>
            <th>Maila</th>
			<th>Irakaslea</th>
            <th>Ikasleak</th>
            <th>Ikasgaiak (irekiak/guztira)</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($elementuak as $ikasgela){
            
            $ikasgela_datuak = get_ikasgela_datuak($ikasgela['id']);
            ?>
        <tr <?php echo current ($klassak); ?>>
            <td class="td_klik"><?php echo $ikasgela['izena']?></td>
            <td class="td_klik"><?php echo get_dbtable_field_by_id('mailak', 'izena', $ikasgela['fk_maila'])?></td>
            <td class="td_klik"><?php echo get_dbtable_field_by_id('irakasleak', 'abizenak', $ikasgela['fk_irakaslea']); ?>, <?php echo get_dbtable_field_by_id('irakasleak', 'izena', $ikasgela['fk_irakaslea']); ?></td>
			<td class="td_klik"><?php echo $ikasgela_datuak['ikasle_kop']?></td>
            <td class="td_klik"><?php echo $ikasgela_datuak['ikasgai_irekiak'] . " / " . $ikasgela_datuak['ikasgaiak_guztira']?></td>
            <td class="td_aukerak">
                <a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo URL_BASE_ADMIN . "ikasgelak/form" . $url_param; ?>&edit_id=<?php echo $ikasgela["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo URL_BASE_ADMIN . "ikasgelak/form" .  $url_param; ?>&ezab_id=<?php echo $ikasgela["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php if (!next ($klassak)) reset ($klassak); }?>
    </tbody>
</table>




<?php
	// Ponemos los indices de la paginacion en caso de que haya mas de una pagina
	if ($orrikapena["numPags"] > 1) {
		orrikapen_indizeak ($orrikapena, $url_base);
	}
?>