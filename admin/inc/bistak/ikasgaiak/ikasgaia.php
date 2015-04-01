<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>ikasgelak/form?edit_id=<?php echo $fk_ikasgela?>#ikasgaiak">Ikasgaiak</a> > <?php if ($edit_id) { echo $ikasgaia->hizkuntzak[$hizkuntza["id"]]->izenburua; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo URL_BASE_ADMIN; ?>ikasgelak/form?edit_id=<?php echo $fk_ikasgela?>#ikasgaiak"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>


<div id="formularioa" class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		<input type="hidden" name="fk_ikasgela" value="<?php echo $fk_ikasgela; ?>" />
		
		<fieldset>
            
			<legend><strong>Datuak</strong></legend>
			
			<div class="control-group">
			    <label for="hasiera_data">Hasiera data:</label>
			    <input class="input-large" name="hasiera_data" type="text" id="hasiera_data" value="<?php echo $ikasgaia->hasiera_data?>" />
			</div>
			<div class="control-group">
			    <label for="hasiera_data">Bukaera data:</label>
			    <input class="input-large" name="bukaera_data" type="text" id="bukaera_data" value="<?php echo $ikasgaia->bukaera_data?>" />
			</div>
			
		</fieldset>
		
		 <?php
		foreach (hizkuntza_idak() as $h_id) {
		?>
		<fieldset>
		    
		    <legend><strong><?php echo get_dbtable_field_by_id("hizkuntzak", "izena", $h_id); ?></strong></legend>
		    
		    <div class="control-group">
			<label for="izenburua_<?php echo $h_id; ?>">Izenburua:</label>
			<input class="input-xxlarge" type="text" id="izenburua_<?php echo $h_id; ?>" name="izenburua_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input($ikasgaia->hizkuntzak[$h_id]->izenburua); ?>" />
		    </div>
		    
		    <div class="control-group">
			<label for="azalpena_<?php echo $h_id; ?>">Azalpena:</label>
			<textarea class="input-xxlarge" type="text" id="azalpena_<?php echo $h_id; ?>" name="azalpena_<?php echo $h_id; ?>"><?php echo testu_formatua_input($ikasgaia->hizkuntzak[$h_id]->azalpena); ?></textarea>
		    </div>
		    
		    <div class="control-group">
			<label for="etiketak_<?php echo $h_id; ?>">Etiketak:</label>
			<input id="etiketak_<?php echo $h_id; ?>" name="etiketak_<?php echo $h_id; ?>" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
		    </div>
		    
		</fieldset>
		<?php
		    }
		?>
		<fieldset>
            
			<legend><strong>Ariketak</strong></legend>
			
			
			<div class="control-group">
			    <label for="konbo_ariketak">Aukeratu:</label>
			    <select id="konbo_ariketak"  name="ariketak[]" multiple="multiple">
						<?php foreach($ariketa_guztiak as $ariketa){?>
						<option <?php if(in_array($ariketa['id'], $ariketa_idak)){?>selected="selected"<?php }?> value="<?=$ariketa['id']?>" ><?php echo $ariketa['izena'] . " [" . $ariketa["ariketa_mota"] . "]"; ?></option>
						<?php }?>
				</select>
			   
			</div>
			
		</fieldset>
		
		
		
		<div class="control-group text-center">
			<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
			<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/chosen/chosen.jquery.js"></script>
<script type="text/javascript">
        
        function verif(){
            return (confirm ("Ikasgaia gorde?"));
        }
        
        function aukeratu_zerrendatik(elementua, eremua){
        
            var hidden = $('#hidden_' +eremua);
            var badago;
            var zerrenda_value = hidden.val();
            // konprobatu ez dagoela zerrendan
            badago =zerrenda_value.match( new RegExp("(^|;)"+elementua['item'].id+"($|;)", "gi") );
            console.log(badago);
            if ( badago === null ){
                hidden.val( hidden.val() + elementua['item'].id + ";");
                var zerrenda_id = 'zerrenda_' + eremua;
                var zerrenda = $('#'+zerrenda_id);
                
                zerrenda.append('<li>'+ ' <a href="#" hidden_id="hidden_'+eremua+'" class="ezabatu_elementua" id="'+elementua['item'].id+'">Ezabatu</a> - ' +elementua['item'].label + '</li>');
                var input_id = 'ac_' + eremua;
            }
            $('#'+input_id).val('');
        }
    
		$(document).ready(function() {
            
            $("#hasiera_data, #bukaera_data").datetimepicker({
                dateFormat: "yy-mm-dd",
                timeFormat: "HH:mm:ss",
                
                // Datetimepicker-a goiburuaren azpian ezkutatzen zen eta ezin zen data aldatu. Horregatik erabiltzen dugu hau.
                // http://stackoverflow.com/questions/15131465/how-to-change-jquery-ui-datepicker-position
                beforeShow: function (input, inst) {
                    // setTimeout-a gabe ez zuen funtzionatzen.
                    setTimeout(function () {
                        inst.dpDiv.css({
                            top: 40
                        });
                    }, 0);
                }
            });
            
            $("#konbo_ariketak").chosen({
                placeholder_text_multiple : "Aukeratu...",
                no_results_text : "Emaitzarik ez"
            });
            
            // Ikus-entzunezko honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
            // eta typeahead hasieratuko dugu.
            $(".tm-input").etiketatu({
                bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
                id: '<?php echo $edit_id; ?>',
                mota: 'ikasgaia'
            });
            
            $( "#ac_ariketak" ).autocomplete({
                
                source: function (request, response) {
                    
                    $.ajax({
                        type: "GET",
                        url: '<?php echo URL_BASE; ?>API/v1/ariketak',
                        dataType: "json",
                        data: {
                            term: request.term,
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            alert('Error: ' + xhr.responseText);
                        },
                        success: function (data) {
                            response($.map(data.emaitzak, function (item) {
                                return {
                                    label: item.izena,
                                    value: item.id,
                                    id: item.id,
                                    mota: item.mota
                                }
                            }));
                        }
                    });
                },
                
                response: function (event, ui){
                    
                },
                
                select: function (event, ui){
                        console.log(ui);
                        event.preventDefault();
                        aukeratu_zerrendatik(ui, 'ariketak');
                }
            });
            
            $('#zerrenda_ariketak').on('click', ".ezabatu_elementua", function(event){
                event.preventDefault();
                var id = $(this).attr("id");
                var hidden = $('#'+$(this).attr("hidden_id"));
                $(this).parent().remove();
                var testua = hidden.val();
                testua = testua.replace(new RegExp("(^|;)"+id+"($|;)", "gi"), ";");
                hidden.val(testua);
            });
		});
</script>