<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/css/jquery.fileupload.css">

<script type="text/javascript">
    $(document).ready(function() {
		
       
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
            
        <?php if ($mezua != "") { ?>
            
            <div class="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $mezua; ?>
            </div>
            
        <?php } ?>
        
    
        <legend><strong>Ikus-entzunezkoa</strong></legend>
        <div class="control-group">
            <div class="span6">
                <?php if ($ikus_entzunezkoa->mota == "audioa") {
                $path = $ikus_entzunezkoa->audio_path;
                $jatorrizkoa = $ikus_entzunezkoa->audio_jatorrizkoa;
                ?>
                <audio style="width:100%" id="audioa-aurrebista-erreproduktorea" controls>
                    <source id="audioa-aurrebista-erreproduktorea-mp3" src="<?php echo  URL_BASE . $path . $jatorrizkoa ?>" type="audio/mpeg"></source>
                    <source id="audioa-aurrebista-erreproduktorea-ogg"  src="" type="audio/ogg"></source>
                </audio>
                <?php } //endif audio
                else {
                    $path = $ikus_entzunezkoa->bideo_path;
                    $jatorrizkoa = $ikus_entzunezkoa->bideo_jatorrizkoa;
                    $mp4 = $ikus_entzunezkoa->bideo_mp4;
                    $webm = $ikus_entzunezkoa->bideo_webm;
                ?>           
                <video style="width:100%" id="bideoa-aurrebista-erreproduktorea" controls>
                    <source id="bideoa-aurrebista-erreproduktorea-mp4" src="<?php echo URL_BASE . $path . $mp4?>" type="video/mp4"></source>
                    <source id="bideoa-aurrebista-erreproduktorea-webm"  src="" type="video/webm"></source>
                </video>
                <?php } //endif?>
            </div>
            <div class="span6">
                <h4>Azpitituluak</h4>
                
                
                <?php if($ikus_entzunezkoa->azpitituluak_ezagutzailea != ''){?>
                <p>Aurretik sortu diren azpitituluak / transkribapena:</p>
                <table>
                    <tr> <?php if($ikus_entzunezkoa->azpitituluak_ezagutzailea != ''){?>
                        <td><a class="btn" id="erabili_azpititulua_btn"><i class="icon-ok"></i></a></td>
                        <td>
                             <a href="<?php echo URL_BASE; ?>ezagutzailetik/<?php echo $edit_id?>.srt">Azpitituluak</a> <span><?php echo $ikus_entzunezkoa->ezagutzailea_noiz?></span>
                           
                        </td>
                        <?php }else{?>
                        
                        <?php }?>
                    </tr>
                </table>
                <table>
                    <tr>
                        <?php if($ikus_entzunezkoa->transkribapena != ''){?>
                                                
                        <td><a class="btn" id="erabili_transkribapena_btn"><i class="icon-edit"></i></a></td>
                        <td>
                             <a href="<?php echo URL_BASE; ?>ezagutzailetik/<?php echo $edit_id?>.txt">Transkribapena</a> <span><?php echo $ikus_entzunezkoa->ezagutzailea_noiz?></span>
                            <input type="hidden" name="transkribapena_hidden" id="transkribapena_hidden" value="<?php echo testu_formatua_input($ikus_entzunezkoa->transkribapena)?>" >
                        </td>
                        <?php }else{?>
                         
                        <?php }?>
                    </tr>
                </table>
                <?php } else{?>
                <p>Aurretik ez dira azpitituluak / transkribapenak sortu.</p>
              <?php }?>
            </div>
        </div>
          
    </fieldset>
        
    <?php  if (is_file($_SERVER['DOCUMENT_ROOT'] . $path . $jatorrizkoa)) {?>
    
    <?php /* TODO
    <fieldset>
        <legend><strong>Azpitituluak transkribapenetik</strong></legend>
        
        
        <div class="control-group">
         
            <p>Alineamendu teknologiaren bitartez azpititulu fitxategi bat sortuko da. Horretarako transkribapen hutsa idatzi edo txertatu hurrengo eremuan:</p>
            <div class="span8">
            <textarea name="transkribapena" id="transkribapena_txtarea" rows="7" style="width:100%"></textarea>
            </div>
            <div class="span3">
                <button id="transkribapena_kargatu_btn" file_url="<?php echo URL_BASE . $path . $jatorrizkoa?>" class="btn transkribapena_kargatu_btn">Transkribapena kargatu</button>    
            </div> 
            <div class="span8">
            <button id="azpitituluak_transkribapenetik_sortu_btn" file_url="<?php echo URL_BASE . $path . $jatorrizkoa?>" class="btn">Azpitituluak Sortu</button>
             </div>
            
        </div>
    </fieldset>
    <?php */?>
    
    <fieldset>
        <legend><strong>Azpititulu automatikoak</strong></legend>
               
        <div class="control-group" id="azpititulu_automatikoak_edukia">
            <?php if($ikus_entzunezkoa->ezagutzailea_egoera == 'lanean'){?>
            <p>Ikus-entzunezkoa ezagutzaile automatikoaren bitartez tratatzen ari da. Minutu batzuk barru emaitza ikusi ahal izango da.<p>
            <div style="text-align:center">
            <img src="<?php echo URL_BASE?>img/erreproduzitzailea/ajax-loader.gif" >
            </div>
            <?php } else{?>
                <p>Ahots ezagutzailearen bitartez azpititulu fitxategi bat eta transkribapena sortu</p>
            <button id="azpitituluak_sortu_btn" file_url="<?php echo URL_BASE . $path . $jatorrizkoa?>" class="btn azpitituluak_sortu_btn">Azpitituluak eta transkribapena sortu</button>    
            <?php }?>
            
            
        </div>
       
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

$('.control-group').on('click', '.transkribapena_kargatu_btn', function(event){
    event.preventDefault();
    $('#transkribapena_txtarea').val( $('#transkribapena_hidden').val() );
    
    
});


$('.control-group').on('click', '#azpitituluak_transkribapenetik_sortu_btn', function(event){
    event.preventDefault();
    // bidali eskaera
    var request = $.ajax({
           url: "<?php echo URL_BASE_ADMIN; ?>scriptak/azpitituluak_transkribapenetik.php",
           type: "POST",
           data: { transkribapena :  $('#transkribapena_txtarea').val(),
                    id:  $('#hidden_edit_id').val()
                }
       });
       request.done(function( msg ) {
           console.log('Eginda: ' + msg);
       });
       request.fail(function( jqXHR, textStatus ) {
           console.log('AKATSA: ' + textStatus);
       });
    
    
    
});



$('.control-group').on('click', '.azpitituluak_sortu_btn', function(event){
    event.preventDefault();
    var url = $(this).attr('file_url');
       
    
    var request = $.ajax({
           url: "<?php echo URL_BASE_ADMIN; ?>scriptak/azpitituluak_ikusentzunezkotik.php",
           type: "POST",
           data: { id:  $('#hidden_edit_id').val(),
                   url: url
                }
       });
       request.done(function( msg ) {
           console.log('Eginda: ' + msg);
           // izkutatu botoia eta erakutsi mezua
           $("#azpititulu_automatikoak_edukia").html('<p>Ikus-entzunezkoa ezagutzaile automatikoaren bitartez tratatzen ari da. Minutu batzuk barru emaitza ikusi ahal izango da.<p>' +
                                                     '<div style="text-align:center"><img src="<?php echo URL_BASE?>img/erreproduzitzailea/ajax-loader.gif" >'+
                                                    '</div>');
           
       });
       request.fail(function( jqXHR, textStatus ) {
           console.log('AKATSA: ' + textStatus);
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




</script>
