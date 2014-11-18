<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE_ADMIN; ?>css/ikus_entzunezkoak.css"></link>
	
<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Ikus-entzunezkoak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base; ?>form">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<div>
    <div class='input-append'>
        <label for="ikus-entzunezkoak-bilatu-testua">Iragazi izenburuaren arabera:</label>
        <input class='input-xxlarge' id='ikus-entzunezkoak-bilatu-testua' type='text' value='' />
        <button class='btn' id='ikus-entzunezkoak-bilatu-botoia' type='button'>
            <i class='icon-search'></i>
        </button>
    </div>
    
    <div class="control-group">
        <label for="etiketak">Iragazi etiketen arabera:</label>
        <input id="etiketak" name="etiketak" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
        <input type="hidden" value="" id="etiketak-hidden">
    </div>
</div>

<?php if ($mezua != "") { ?>
    
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        $mezua
    </div>
    
<?php } ?>

<?php $klassak = array ('', 'class="info"'); ?>
<table id="ikus-entzunezkoak-zerrenda" class="table table-bordered table-hover">
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
                <?php if ($elem["mota"] == "audioa") { ?>
                <a class="btn ikus-entzunezkoa-aurrebista-botoia" data-toggle="modal" title="aurrebista" href="#audioa-aurrebista" data-id-ikus-entzunezkoa="<?php echo $elem["id"]; ?>"><i class="icon-play"></i></a>
                <?php } else { ?>
				<a class="btn ikus-entzunezkoa-aurrebista-botoia" data-toggle="modal" title="aurrebista" href="#bideoa-aurrebista" data-id-ikus-entzunezkoa="<?php echo $elem["id"]; ?>"><i class="icon-play"></i></a>
                <?php } ?>
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

<div id="audioa-aurrebista" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="audioa-aurrebista-etiketa" aria-hidden="true">
    
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Audioaren aurrebista</h3>
    </div>
    <div class="modal-body">
        <audio id="audioa-aurrebista-erreproduktorea" controls>
            <source id="audioa-aurrebista-erreproduktorea-mp3" src="" type="audio/mpeg"></source>
            <source id="audioa-aurrebista-erreproduktorea-ogg"  src="" type="audio/ogg"></source>
        </audio>
    </div>
</div>

<div id="bideoa-aurrebista" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="bideoa-aurrebista-etiketa" aria-hidden="true">
    
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Bideoaren aurrebista</h3>
    </div>
    <div class="modal-body">
        <video id="bideoa-aurrebista-erreproduktorea" controls>
            <source id="bideoa-aurrebista-erreproduktorea-mp4" src="" type="video/mp4"></source>
            <source id="bideoa-aurrebista-erreproduktorea-webm"  src="" type="video/webm"></source>
        </video>
    </div>
</div>

<script>
    
    $(document).ready(function() {
        
        // Etiketen arabera iragazi ahal izateko tagsManager erabiliko dugu
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            hiddenTagListId: "etiketak-hidden"  // Hau gabe ezkutuko etiketen zerrendak ez dauka id-rik eta ezin nuen jakin noiz gehitzen ziren etiketa berriak.
        });
        
    });
    
    $("#ikus-entzunezkoak-zerrenda").on("click", ".ikus-entzunezkoa-aurrebista-botoia", function() {
        
        $.ajax({
            
            type: "GET",
            dataType: "json",
            url: "<?php echo URL_BASE; ?>API/v1/ikus-entzunezkoak/" + $(this).attr("data-id-ikus-entzunezkoa")
            
        }).done(function(data, textStatus, jqXHR) {
            
            if (data.mota === "audioa") {
                
                // Audioaren src-ak eguneratu.
                $("#audioa-aurrebista-erreproduktorea-mp3").attr('src', '<?php echo URL_BASE; ?>' + data.audio_path + data.audio_mp3);
                $("#audioa-aurrebista-erreproduktorea-ogg").attr('src', '<?php echo URL_BASE; ?>' + data.audio_path + data.audio_ogg);
                
                // Hau gabe src-ak aldatzen dira baina aurreko bideoa ikusten da.
                $("#audioa-aurrebista-erreproduktorea")[0].load();
                
            } else if (data.mota === "bideoa") {
                
                // Bideoaren src-ak eguneratu.
                $("#bideoa-aurrebista-erreproduktorea-mp4").attr('src', '<?php echo URL_BASE; ?>' + data.bideo_path + data.bideo_mp4);
                $("#bideoa-aurrebista-erreproduktorea-webm").attr('src', '<?php echo URL_BASE; ?>' + data.bideo_path + data.bideo_webm);
                
                // Hau gabe src-ak aldatzen dira baina aurreko bideoa ikusten da.
                $("#bideoa-aurrebista-erreproduktorea")[0].load();
                
            } else {
                
                console.log("Ikus-entzunezko mota ezezaguna!");
                
            }
            
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log("Errore bat gertatu da zerbitzaritik bideoaren datuak eskuratzean.");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            
        });
        
    });
    
    $("#ikus-entzunezkoak-bilatu-botoia").click(function() {
        
        $.ajax({
            
            type: "GET",
            dataType: "json",
            url: "<?php echo URL_BASE; ?>API/v1/ikus-entzunezkoak/",
            data: {
                testua: $("#ikus-entzunezkoak-bilatu-testua").val(),
                etiketak: $(this).val()
            }
            
        }).done(function(data, textStatus, jqXHR) {
            
            console.log(data);
            
            // Zerrenda garbitu.
            $("#ikus-entzunezkoak-zerrenda tbody").empty();
            
            // Zerbitzaritik kargatutako elementuak zerrendara gehitu.
            for (var i = 0; i < data.ikus_entzunezkoak.length; i++) {
                
                $("#ikus-entzunezkoak-zerrenda tbody").append(
                    '<tr <?php echo current($klassak); ?>>' +
                        '<td class="td_klik">' + data.ikus_entzunezkoak[i].izenburua + '</td>' +
                        '<td class="td_aukerak">' +
                            '<a class="btn ikus-entzunezkoa-aurrebista-botoia" data-toggle="modal" title="aurrebista" href="#bideoa-aurrebista" data-id-ikus-entzunezkoa="' + data.ikus_entzunezkoak[i].id + '"><i class="icon-play"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=' + data.ikus_entzunezkoak[i].id + '"><i class="icon-pencil"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=' + data.ikus_entzunezkoak[i].id + '" onclick="javascript: return (confirm (\'Seguru ezabatu nahi duzula?\'));"><i class="icon-trash"></i></a>' +
                        '</td>' +
                    '</tr>'
                );
                
            }
            
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log("Errore bat gertatu da zerbitzaritik bideoen datuak eskuratzean.");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            
        });
        
    });
    
    $("#etiketak-hidden").change(function() {
        
        $.ajax({
            
            type: "GET",
            dataType: "json",
            url: "<?php echo URL_BASE; ?>API/v1/ikus-entzunezkoak/",
            data: {
                testua: $("#ikus-entzunezkoak-bilatu-testua").val(),
                etiketak: $(this).val()
            }
            
        }).done(function(data, textStatus, jqXHR) {
            
            console.log(data);
            
            // Zerrenda garbitu.
            $("#ikus-entzunezkoak-zerrenda tbody").empty();
            
            // Zerbitzaritik kargatutako elementuak zerrendara gehitu.
            for (var i = 0; i < data.ikus_entzunezkoak.length; i++) {
                
                $("#ikus-entzunezkoak-zerrenda tbody").append(
                    '<tr <?php echo current($klassak); ?>>' +
                        '<td class="td_klik">' + data.ikus_entzunezkoak[i].izenburua + '</td>' +
                        '<td class="td_aukerak">' +
                            '<a class="btn ikus-entzunezkoa-aurrebista-botoia" data-toggle="modal" title="aurrebista" href="#bideoa-aurrebista" data-id-ikus-entzunezkoa="' + data.ikus_entzunezkoak[i].id + '"><i class="icon-play"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=' + data.ikus_entzunezkoak[i].id + '"><i class="icon-pencil"></i></a>' +
                            '<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=' + data.ikus_entzunezkoak[i].id + '" onclick="javascript: return (confirm (\'Seguru ezabatu nahi duzula?\'));"><i class="icon-trash"></i></a>' +
                        '</td>' +
                    '</tr>'
                );
                
            }
            
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log("Errore bat gertatu da zerbitzaritik bideoen datuak eskuratzean.");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            
        });
        
    });
    
    $("#bideoa-aurrebista").on("hidden", function() {
        
        // Bideoa geldituko dugu. Kontutan izan HTML5 bideoak ez daukala stop() metodorik.
        $("#bideoa-aurrebista-erreproduktorea")[0].pause();
        
    });
    
    $("#audioa-aurrebista").on("hidden", function() {
        
        // Audioa geldituko dugu. Kontutan izan HTML5 audioak ez daukala stop() metodorik.
        $("#audioa-aurrebista-erreproduktorea")[0].pause();
        
    });
    
</script>