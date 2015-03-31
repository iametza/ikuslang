<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/css/jquery.fileupload.css">

<script type="text/javascript">
    
	/*function verif() {
		var patroi_hutsik = /^\s*$/;
		
        // Fitxa aldatzen ari bagara eta erabiltzaileak ez badu ikus-entzunezko fitxategirik gehitu eta dagoeneko ez badago fitxategirik ez utzi gordetzen.
        if ($("#hidden_edit_id").val() != "0" && !$("#ikus_entzunezkoa_jatorrizkoa").val() && !$("#ikus-entzunezkoa-jatorrizkoa-ikusi").length > 0) {
            
            alert("Audio edo bideo fitxategi bat gehitu behar duzu gorde aurretik.");
            
            return false;
        }
        
		return (confirm ("Ziur zaude ikus-entzunezkoa gorde nahi duzula?"));
	}*/
	
    $(document).ready(function() {
		
        // Ikus-entzunezko honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            id: '<?php echo $edit_id; ?>',
            mota: 'ikus-entzunezkoa'
        });
        
        $("#editatu-hipertranskribapena-botoia").click(function() {
			
			window.location = "<?php echo $url_base; ?>editatu-hipertranskribapena?edit_id=<?php echo $edit_id; ?>";
            
		});
        
	});
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo $url_base; ?>">Ikus-entzunezkoak</a> > <?php if ($edit_id) { echo $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->izenburua; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
    <form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
        <input type="hidden" name="gorde" value="BAI" />
        <input id="hidden_edit_id" type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
        
        <fieldset>
            
            <legend><strong>Informazio orokorra</strong></legend>
            
            <div class="control-group">
                <label for="izenburua_<?php echo $hizkuntza["id"]; ?>">Izenburua:</label>
                <input class="input-xxlarge" type="text" id="izenburua_<?php echo $hizkuntza["id"]; ?>" name="izenburua_<?php echo $hizkuntza["id"]; ?>" value="<?php echo testu_formatua_input($ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->izenburua); ?>" />
            </div>
            
            <div class="control-group">
                <label for="etiketak_<?php echo $hizkuntza["id"]; ?>">Etiketak:</label>
                <input id="etiketak_<?php echo $hizkuntza["id"]; ?>" name="etiketak_<?php echo $hizkuntza["id"]; ?>" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
            </div>
            
        </fieldset>
        
        <fieldset>
            
            <?php if ($mezua != "") { ?>
                
                <div class="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $mezua; ?>
                </div>
                
            <?php } ?>
            
            
            <legend><strong>Ikus-entzunezkoa</strong></legend>
            
            <?php if($edit_id != 0){ // fitxa berria bada, fitxategia igotzeko aukera kendu?>
            
                <div class="control-group">
                    <label for="ikus_entzunezkoa_jatorrizkoa">Jatorrizko ikus-entzunezkoa:</label>
                    
                    <?php
                    if ($ikus_entzunezkoa->mota == "audioa") {
                        $path = $ikus_entzunezkoa->audio_path;
                        $jatorrizkoa = $ikus_entzunezkoa->audio_jatorrizkoa;
                    } else if ($ikus_entzunezkoa->mota == "bideoa") {
                        $path = $ikus_entzunezkoa->bideo_path;
                        $jatorrizkoa = $ikus_entzunezkoa->bideo_jatorrizkoa;
                    }
                    
                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $path . $jatorrizkoa)) {
                        echo "<a id='ikus-entzunezkoa-jatorrizkoa-ikusi' href='" . URL_BASE . $path . $jatorrizkoa . "' target='_blank'>Ikusi</a>";
                        echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&h_id=" . $hizkuntza["id"] . "&ezabatu=".strtoupper($ikus_entzunezkoa->mota)."\" onClick=\"javascript: return (confirm ('Ziur zaude fitxategia ezabatu nahi duzula?'));\">Ezabatu</a>";
                    }
                    ?>
                    
                    <div class='alert'>OHARRA: Ikus-entzunezkoa gehitu eta gorde botoia sakatu behar duzu. Gehienez 100 MB.</div>
                    <?php //<input class="input-xxlarge" name="ikus_entzunezkoa_jatorrizkoa" type="file" id="ikus_entzunezkoa_jatorrizkoa" /> ?>
                    <span class="btn fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Aukeratu fitxategia</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload" type="file" name="files[]" multiple>
                    </span>
                    <br>
                    <br>
                    <!-- The global progress bar -->
                    <div id="progress" class="progress">
                        <div class="bar bar-success"></div>
                    </div>
                    <!-- The container for the uploaded files -->
                    <div id="files" class="files"></div>
                   
                </div>
                
            <?php } else { ?>
                <div class='alert'>OHARRA: Lehendabizi Ikus-entzunezkoaren datuak sartu eta gorde behar dituzu ikus-entzunezkoa gehitu ahal izateko.</div>
            <?php } ?>
        </fieldset>
        
        <?php  if (is_file($_SERVER['DOCUMENT_ROOT'] . $path . $jatorrizkoa)) {?>
		<fieldset>
			<legend><strong>Eskuz sortutako azpitituluak</strong></legend>
			<div class='alert'>OHARRA: Hipertranskribapena sortzeko SRT azpititulu-fitxategi bat erabili nahi baduzu hautatu aukera hau.</div>
			<div class="control-group">
                <label for="azpitituluak_<?php echo $hizkuntza["id"]; ?>">SRT azpitituluak:</label>
                <?php if (!is_file($_SERVER['DOCUMENT_ROOT'] . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->azpitituluak)) { echo "<div class='alert'>OHARRA: Hipertranskribapena sortu ahal izateko SRT azpititulu bat gehitu eta gorde botoia sakatu behar duzu lehenik.</div>"; } ?>
                <input class="input-xxlarge" name="azpitituluak_<?php echo $hizkuntza["id"]; ?>" type="file" id="azpitituluak_<?php echo $hizkuntza["id"]; ?>" />
                <?php
                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->azpitituluak)) {
                        echo "<a href='" . URL_BASE . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->azpitituluak . "' target='_blank'>Ikusi</a>";
                        echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&h_id=" . $hizkuntza["id"] . "&ezabatu=AZPITITULUA\" onClick=\"javascript: return (confirm ('Azpitituluak ezabatzea aukeratu duzu. Ziur al zaude?'));\">Ezabatu</a>";
                    }
                ?>
                <button id="editatu-hipertranskribapena-botoia" type="button" class="btn"<?php if (!is_file ($_SERVER['DOCUMENT_ROOT'] . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$hizkuntza["id"]]->azpitituluak)) {echo " disabled";} ?>>Editatu hipertranskribapena</button>
            </div>
		</fieldset>
		
        <fieldset>
            <legend><strong>Azpititulu automatikoak</strong></legend>
            <div class='alert'>OHARRA: Hipertranskribapena sortzeko transkribapenetik automatikoki sortutako azpitituluak erabili nahi badituzu hautatu aukera hau.</div>
            <a class="btn" href="<?php echo $url_base?>azpitituluak?edit_id=<?=$edit_id?>">Sortu azpitituluak eta editatu hipertranskribapena</a>
            
            
        </fieldset>
        <?php } ?>
        
        <div class="control-group text-center">
            <button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
            <button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
        </div>
    </form>
</div>

<?php // MODAL ezagutza_txt?>
<div id="ezagutza_txt_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ezagutza_txt_izenburua_etiketa" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
        </div>
        
        <div class="modal-body">
		<h3>Zuzendu ezagutza automatikoaren emaitza testua</h3>
		<p>Amaitzean sakatu "Hurrengoa".</p>
		<?php // TODO: kargatu dagokion ikusesntzunezkoa?>
		<div>
			<div>
			    <video style="width: 300px" id="bideoa-aurrebista-erreproduktorea" controls>
				<source id="bideoa-aurrebista-erreproduktorea-mp4" src="<?php echo URL_BASE . BIDEOEN_PATH . "68_Kermanen_esku_lanak.mp4"; ?>" type="video/mp4"></source>
				<source id="bideoa-aurrebista-erreproduktorea-webm"  src="<?php echo URL_BASE . BIDEOEN_PATH . "68_Kermanen_esku_lanak.webm";?>" type="video/webm"></source>
			    </video>
			</div>
          
		</div>
		
            <div>
                <input type="hidden" name="gorde" value="BAI" />
                <input type="hidden" id="ezagutza_txt_file_url" name="ezagutza_txt_file_url" value="" />
                <textarea style="width:100%" rows="8" id="ezagutza_txt_textarea" name="ezagutza_txt_textarea"></textarea>
	    </div>
                
          
        </div>
        
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
            <button id="ezagutza_txt_hurrengoa_botoia" class="btn btn-primary"  type="submit">Hurrengoa</button>
        </div>
    </div>
</div>
<?php // END modal_txt?>

<script type="text/javascript" src="<?php echo URL_BASE_ADMIN; ?>js/jscolor/jscolor.js"></script>


<script src="<?php echo URL_BASE_ADMIN; ?>js/jquery.blockUI.js"></script>

<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/js/jquery.fileupload-validate.js"></script>
<script>
/*
function post_upload(url) {
       // kargatu denez, exekutatu konbertsio eta sailkatze script-a
       var request = $.ajax({
           url: "<?php echo URL_BASE_ADMIN; ?>scriptak/post_upload.php",
           type: "POST",
           data: { fitxategia : url }
       });
       request.done(function( msg ) {
           $( "#files" ).html( msg );
       });
       request.fail(function( jqXHR, textStatus ) {
           alert( "Request failed: " + textStatus );
       });
}
*/
$('.control-group').on('click', '.azpitituluak_sortu_btn', function(event){
       event.preventDefault();
       var url = $(this).attr('file_url');
       $('#files').block({message: null});
       var request = $.ajax({
           url: "<?php echo URL_BASE_ADMIN; ?>scriptak/ezagutza_egin.php",
           type: "POST",
	   dataType: "json",
           data: { fitxategia : url }
       });
       request.done(function( msg ) {
		if (msg.egoera == 'ondo') {
			$("#ezagutza_txt_textarea").val(msg.testua);
			$("#ezagutza_txt_file_url").val(url);
			$("#ezagutza_txt_modal").modal('show');
		}
		
		$( "#files" ).html( msg );
		$('#files').unblock();
           
       });
       request.fail(function( jqXHR, textStatus ) {
           alert( "Request failed: " + textStatus );
           $('#files').unblock();
       });
       
       
       
});

$('#ezagutza_txt_modal').on('click', '#ezagutza_txt_hurrengoa_botoia', function(event){
       event.preventDefault();
       var url = $("#ezagutza_txt_textarea").val();
       var testua = $("#ezagutza_txt_file_url").val();
       $('#files').block({message: null});
       var request = $.ajax({
           url: "<?php echo URL_BASE_ADMIN; ?>scriptak/azpitituluak_sortu.php",
           type: "POST",
	   dataType: "json",
           data: { fitxategia : url,
		   testua: testua}
       });
       request.done(function( msg ) {
		if (msg.egoera == 'ondo') {
			
			$("#azpitituluak_sortu_link").attr("href", msg.azpititulua_url);
			$("#azpitituluak_sortu_div").toggle(1000);
			$("#ezagutza_txt_modal").modal('hide');
		}
		
		$( "#files" ).html( msg );
		$('#files').unblock();
           
       });
       request.fail(function( jqXHR, textStatus ) {
	   $("#ezagutza_txt_modal").modal('hide');
           alert( "Request failed: " + textStatus );
           $('#files').unblock();
       });
       
       
       
});


$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = '<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/server/php/',
        uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Utzi')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $('#fileupload').fileupload({
        url: url,
        formData: [{name: 'edit_id', value: $("#hidden_edit_id").val() } ],
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(webm|mp4|mp3|ogg)$/i,
        limitMultiFileUploads: 1,
        disableVideoPreview: true,
        disableAudioPreview: true,
        messages: {
            acceptFileTypes: 'Fitxategi-mota baliogabea. Onartutako fitxategi-motak: mp4, webm, mp3 edo ogg.'
        }
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>')
                    .append($('<span/>').text(file.name));
            if (!index) {
                node
                    .append('<br>')
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Igo fitxategia')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
                var link = $('<a>')
                    .attr('target', '_blank')
                    .prop('href', file.url);
                    
                //sortu "Azpitituluak sortu" botoia
		
                var botoia = $('<button>')
                    .attr('class', 'btn-default azpitituluak_sortu_btn')
                    .attr('file_url', file.url)
                    .html('Sortu azpitituluak');

                var lotura = $(data.context.children()[index])
                    .wrap(link);
                    
                //    $(lotura).parent().parent().append(botoia);
          
                
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('Ezin izan da fitxategia igo: ' + data.textStatus);
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

  
});




</script>
