<?php
// Protegemos el archivo del "acceso directo"
if (!isset ($url)) header ("Location: /");
?>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.css">

<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#nire_ariketak').dataTable({"dom": 'flti<"pagination"p>',
                                        "pageLength": 10,
                                        language: {url: '<?php echo URL_BASE_ADMIN?>js/datatables.euskara.json'}
                                        });
        $('#nire_ikasgelak').dataTable({"dom": 'flti<"pagination"p>',
                                        "pageLength": 10,
                                        language: {url: '<?php echo URL_BASE_ADMIN?>js/datatables.euskara.json'}
                                        });
    } );
</script>

<h3>Nire ikasgelak</h3>
<table id="nire_ikasgelak" class="table">
    <thead>
        <tr>
            <th>Izena</th>
            <th>Maila</th>
            <th>Ikasleak</th>
            <th>Ikasgaiak (irekiak/guztira)</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($ikasgelak as $ikasgela){
            
            $ikasgela_datuak = get_ikasgela_datuak($ikasgela['id']);
            ?>
        <tr>
            <td class="td_klik"><?php echo $ikasgela['izena']?></td>
            <td class="td_klik"><?php echo get_dbtable_field_by_id('mailak', 'izena', $ikasgela['fk_maila'])?></td>
            <td class="td_klik"><?php echo $ikasgela_datuak['ikasle_kop']?></td>
            <td class="td_klik"><?php echo $ikasgela_datuak['ikasgai_irekiak'] . " / " . $ikasgela_datuak['ikasgaiak_guztira']?></td>
            <td class="td_aukerak">
                <a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo URL_BASE_ADMIN . "ikasgelak/form" . $url_param; ?>&edit_id=<?php echo $ikasgela["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo URL_BASE_ADMIN . "ikasgelak/form" .  $url_param; ?>&ezab_id=<?php echo $ikasgela["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>


<div style="clear:both"></div>
<hr>

<h3>Nire ariketak</h3>

<table id="nire_ariketak" class="table">
    <thead>
        <tr>
            <th>Izena</th>
            <th>Egoera</th>
            <th>Mota</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($ariketak as $ariketa){?>
        <tr>
            <td class="td_klik"><?php echo $ariketa['izena']?></td>
            <td class="td_klik"><?php echo $ariketa['egoera'] == 0 ? 'Zirriborroa':'Publikoa' ?></td>
            <td class="td_klik"><?php echo get_dbtable_field_by_id_hizkuntza('ariketa_motak', 'izena', $ariketa['fk_ariketa_mota'], $hizkuntza['id'])?></td>
            <td class="td_aukerak">
                <a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo URL_BASE_ADMIN . get_dbtable_field_by_id_hizkuntza('ariketa_motak', 'nice_name', $ariketa['fk_ariketa_mota'], $hizkuntza['id']). "/form" . $url_param; ?>&edit_id=<?php echo $ariketa["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo URL_BASE_ADMIN . get_dbtable_field_by_id_hizkuntza('ariketa_motak', 'nice_name', $ariketa['fk_ariketa_mota'], $hizkuntza['id']). "/form" . $url_param; ?>&ezab_id=<?php echo $ariketa["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

    

