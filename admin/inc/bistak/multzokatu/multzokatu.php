<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>multzokatu">Multzokatu</a> > <?php if ($edit_id) { echo $multzokatu->hizkuntzak[$h_id]->izena; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<div class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		
        <fieldset>
            <legend><strong>Dokumentuak</strong></legend>
            
            <div class="control-group">
			    <select id="konbo-dokumentuak" class="input-xxlarge" name="dokumentuak[]" multiple="multiple">
					<?php foreach($dokumentuak as $dokumentua){?>
                        <option 
                        <?php if ($multzokatu->dokumentuak) {
                            foreach($multzokatu->dokumentuak as $ariketaren_dokumentua) { ?>
                            <?php if($ariketaren_dokumentua->id == $dokumentua["id"]){?>selected="selected"<?php }?>
                        <?php
                            }
                        }
                        ?>
                        value="<?=$dokumentua['id']?>" ><?php echo $dokumentua['izenburua']; ?></option>
					<?php }?>
				</select>
			   
			</div>
            
            <div id="dokumentuak-zerrenda">
                <?php if (count($multzokatu->dokumentuak) > 0) { ?>
                    <?php foreach ($multzokatu->dokumentuak as $dokumentua) { ?>
                    <div><a href="<?php echo URL_BASE . $dokumentua->path_dokumentua . $dokumentua->dokumentua; ?>"><?php echo $dokumentua->izenburua; ?></a></div>
                    <?php } ?>
                    
                <? } else { ?>
                    <div>Esaldiak zuzendu ariketa honek ez dauka dokumenturik oraindik.</div>
                <?php } ?>
            </div>
        </fieldset>
        
		<?php
			foreach (hizkuntza_idak() as $h_id){
		?>
		<fieldset>
			<legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
			
			<div class="control-group">
				<label for="izena_<?php echo $h_id; ?>">Izena:</label>
				<input class="input-xxlarge" type="text" id="izena_<?php echo $h_id; ?>" name="izena_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($multzokatu->hizkuntzak[$h_id]->izena); ?>" />
			</div>
            
            <div class="control-group">
				<label for="azalpena_<?php echo $h_id; ?>">Azalpena:</label>
				<textarea class="input-xxlarge" id="azalpena_<?php echo $h_id; ?>" name="azalpena_<?php echo $h_id; ?>"><?php echo testu_formatua_input($multzokatu->hizkuntzak[$h_id]->azalpena); ?></textarea>
			</div>
            
            <div class="control-group">
                <label for="etiketak_<?php echo $h_id; ?>">Etiketak:</label>
                <input id="etiketak_<?php echo $h_id; ?>" name="etiketak_<?php echo $h_id; ?>" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
            </div>
            
		</fieldset>
		<?php
			}
		?>
		
		<div class="control-group text-center">
			<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
			<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
		</div>
	</form>
</div>

<script src="<?php echo URL_BASE; ?>js/chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;
        
		return (confirm ("Ariketa gorde nahi duzu?"));
	}
    
    $(document).ready(function() {
        
        $("#konbo-dokumentuak").chosen({
            placeholder_text_multiple : "Aukeratu dokumentua(k)...",
            no_results_text : "Emaitzarik ez"
        }).change(function() {
            
            // Dokumentuen zerrenda garbitu
            $("#dokumentuak-zerrenda").empty();
            
            // Zerrendako elementu guztiak kargatu.
            $("#konbo-dokumentuak option:selected").each(function() {
                
                $.ajax({
                    
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo URL_BASE; ?>API/v1/dokumentuak/" + $(this).val()
                    
                }).done(function(data, textStatus, jqXHR) {
                    
                    $("#dokumentuak-zerrenda").append("<div><a href='<?php echo URL_BASE; ?>" + data.path_dokumentua + data.dokumentua + "'>" + data.izenburua + "</a></div>");
                    
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    
                    console.log("Errore bat gertatu da zerbitzaritik dokumentuen datuak eskuratzean.");
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    
                });
            });
        });
        
        // Ariketa honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            id: '<?php echo $edit_id; ?>',
            mota: 'ariketa'
        });
        
    });
</script>