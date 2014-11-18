 <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/css/jquery.fileupload.css">

 
 <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Add files...</span>
        <!-- The file input field used as target for the file upload widget -->
        
              <input type="hidden" name="edit_id" value="" />
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
    <br>
   

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
$('#files').on('click', '.azpitituluak_sortu_btn', function(){
       
       var url = $(this).attr('file_url');
       $('#files').block({message: null});
       var request = $.ajax({
           url: "<?php echo URL_BASE_ADMIN; ?>scriptak/post_upload.php",
           type: "POST",
           data: { fitxategia : url }
       });
       request.done(function( msg ) {
           $( "#files" ).html( msg );
           $('#files').unblock();
           
       });
       request.fail(function( jqXHR, textStatus ) {
           alert( "Request failed: " + textStatus );
           $('#files').unblock();
       });
       
       
       
});

/*jslint unparam: true, regexp: true */
/*global window, $ */
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = '<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/server/php/',
        uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
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
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(mpe?g|webm|avi|mp4|mp3|ogg)$/i,
        limitMultiFileUploads: 1,
        disableVideoPreview: true
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
                .text('Upload')
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
                    .html('Azpitituluak sortu');

                var lotura = $(data.context.children()[index])
                    .wrap(link);
                    
                    $(lotura).parent().parent().append(botoia);
           
                
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

  
});




</script>