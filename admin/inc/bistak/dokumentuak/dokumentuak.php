<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Dokumentuak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base; ?>form">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<td>
    <div class='input-append'>
        <label for="dokumentuak-bilatu-testua">Iragazi izenburuaren arabera:</label>
        <input class='input-xxlarge' id='dokumentuak-bilatu-testua' type='text' value='' />
        <button class='btn' id='dokumentuak-bilatu-botoia' type='button'>
            <i class='icon-search'></i>
        </button>
    </div>
    
    <div class="control-group">
        <label for="etiketak">Iragazi etiketen arabera:</label>
        <input id="etiketak" name="etiketak" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
        <input type="hidden" value="" id="etiketak-hidden">
    </div>
</td>

<?php $klassak = array ('', 'class="info"'); ?>
<table id="dokumentuak-zerrenda" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Izena</th>
			<th width="130">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($elementuak as $elem) {
		?>
		<tr <?php echo current ($klassak); ?>>
			<td class="td_klik"><?php echo $elem["izenburua"]; ?></td>
			<td class="td_aukerak">
                <a class="btn dokumentuak-deskargatu-botoia" data-toggle="modal" title="deskargatu" href="<?php echo URL_BASE . $elem["path_dokumentua"] . $elem["dokumentua"]; ?>"><i class="icon-download"></i></a>
				<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=<?php echo $elem["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=<?php echo $elem["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
			</td>
		</tr>
		<?php if (!next($klassak)) { reset($klassak); } } ?>
	</tbody>
</table>

<?php
	// Ponemos los indices de la paginacion en caso de que haya mas de una pagina
	if ($orrikapena["numPags"] > 1) {
		orrikapen_indizeak ($orrikapena, $url_base);
	}
?>

<script>
    
    $(document).ready(function() {
        
        // Etiketen arabera iragazi ahal izateko tagsManager erabiliko dugu
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            hiddenTagListId: "etiketak-hidden"  // Hau gabe ezkutuko etiketen zerrendak ez dauka id-rik eta ezin nuen jakin noiz gehitzen ziren etiketa berriak.
        });
        
    });
    
    $("#dokumentuak-bilatu-botoia").click(function() {
        
        $.ajax({
            
            type: "GET",
            dataType: "json",
            url: "<?php echo URL_BASE; ?>API/v1/dokumentuak/",
            data: {
                testua: $("#dokumentuak-bilatu-testua").val(),
                etiketak: $(this).val()
            }
            
        }).done(function(data, textStatus, jqXHR) {
            
            console.log(data);
            
            // Zerrenda garbitu.
            $("#dokumentuak-zerrenda tbody").empty();
            
            // Zerbitzaritik kargatutako elementuak zerrendara gehitu.
            for (var i = 0; i < data.dokumentuak.length; i++) {
                
                $("#dokumentuak-zerrenda tbody").append(
                    '<tr <?php echo current($klassak); ?>>' +
                        '<td class="td_klik">' + data.dokumentuak[i].izenburua + '</td>' +
                        '<td class="td_aukerak">' +
                            '<a class="btn dokumentuak-deskargatu-botoia" data-toggle="modal" title="deskargatu" href="<?php echo URL_BASE; ?>' + data.dokumentuak[i].path_dokumentua + data.dokumentuak[i].dokumentua + '" data-id-dokumentua="' + data.dokumentuak[i].id + '"><i class="icon-download"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=' + data.dokumentuak[i].id + '"><i class="icon-pencil"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=' + data.dokumentuak[i].id + '" onclick="javascript: return (confirm (\'Seguru ezabatu nahi duzula?\'));"><i class="icon-trash"></i></a>' +
                        '</td>' +
                    '</tr>'
                );
                
            }
            
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log("Errore bat gertatu da zerbitzaritik dokumentuen datuak eskuratzean.");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            
        });
        
    });
    
    $("#etiketak-hidden").change(function() {
        
        $.ajax({
            
            type: "GET",
            dataType: "json",
            url: "<?php echo URL_BASE; ?>API/v1/dokumentuak/",
            data: {
                testua: $("#dokumentuak-bilatu-testua").val(),
                etiketak: $(this).val()
            }
            
        }).done(function(data, textStatus, jqXHR) {
            
            console.log(data);
            
            // Zerrenda garbitu.
            $("#dokumentuak-zerrenda tbody").empty();
            
            // Zerbitzaritik kargatutako elementuak zerrendara gehitu.
            for (var i = 0; i < data.dokumentuak.length; i++) {
                
                $("#dokumentuak-zerrenda tbody").append(
                    '<tr <?php echo current($klassak); ?>>' +
                        '<td class="td_klik">' + data.dokumentuak[i].izenburua + '</td>' +
                        '<td class="td_aukerak">' +
                            '<a class="btn dokumentuak-deskargatu-botoia" data-toggle="modal" title="deskargatu" href="<?php echo URL_BASE; ?>' + data.dokumentuak[i].path_dokumentua + data.dokumentuak[i].dokumentua + '" data-id-dokumentua="' + data.dokumentuak[i].id + '"><i class="icon-download"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=' + data.dokumentuak[i].id + '"><i class="icon-pencil"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=' + data.dokumentuak[i].id + '" onclick="javascript: return (confirm (\'Seguru ezabatu nahi duzula?\'));"><i class="icon-trash"></i></a>' +
                        '</td>' +
                    '</tr>'
                );
                
            }
            
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log("Errore bat gertatu da zerbitzaritik dokumentuen datuak eskuratzean.");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            
        });
        
    });
</script>