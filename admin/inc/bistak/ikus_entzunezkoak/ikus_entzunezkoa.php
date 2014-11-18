<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo URL_BASE_ADMIN; ?>js/jqueryfileupload/css/jquery.fileupload.css">

<script type="text/javascript">
    
	function verif() {
		var patroi_hutsik = /^\s*$/;
		
        // Fitxa aldatzen ari bagara eta erabiltzaileak ez badu ikus-entzunezko fitxategirik gehitu eta dagoeneko ez badago fitxategirik ez utzi gordetzen.
        if ($("#hidden_edit_id").val() != "0" && !$("#ikus_entzunezkoa_jatorrizkoa").val() && !$("#ikus-entzunezkoa-jatorrizkoa-ikusi").length > 0) {
            
            alert("Audio edo bideo fitxategi bat gehitu behar duzu gorde aurretik.");
            
            return false;
        }
        
		return (confirm ("Ziur zaude ikus-entzunezkoa gorde nahi duzula?"));
	}
	
    $(document).ready(function() {
		
        // Ikus-entzunezko honi dagozkion etiketak zerbitzaritik eskuratu eta bistaratuko ditugu tagsManager erabiliz
        // eta typeahead hasieratuko dugu.
        $(".tm-input").etiketatu({
            bidea: '<?php echo URL_BASE; ?>API/v1/etiketak',
            id: '<?php echo $edit_id; ?>',
            mota: 'ikus-entzunezkoa'
        });
        
        $("#editatu-hipertranskribapena-botoia").click(function() {
			
			window.location = "<?php echo $url_base; ?>editatu-hipertranskribapena&edit_id=<?php echo $edit_id; ?>";
            
		});
        
        $(document).on("click", "#editatu_hizlaria_kolorea", function() {
            
        	// Hau gabe scroll egitean kolore-hautatzailea ezkutatu ondoren ezin zen berriz bistaratu.
        	document.getElementById("editatu_hizlaria_kolorea").color.showPicker();
            
        });
        
        $(document).on('shown', '#editatu_hizlaria', function() {
            
            // Hizlaria editatzeko modala bistaratzean edukien scrolla gora eraman
            $("#editatu_hizlaria .modal-body").scrollTop(0);
            
            $("#editatu_hizlaria .modal-body").unbind("scroll");
            $("#editatu_hizlaria .modal-body").scroll(function() {
                
                // Modalaren barruko scrolla hastean kolore-hautatzailea ezkutatu.
                document.getElementById("editatu_hizlaria_kolorea").color.hidePicker();
                
            })
            
        });
        
        $(document).on("click", "#gehitu_hizlaria_botoia", function(event) {
            var id_hizlaria = 0;
            
            // 0ak id berria behar duela adierazten du
            $("#editatu_hizlaria_id").val(id_hizlaria);
            
            // Eztabaidaren id-a gordeko dugu ezkutuko input batean.
            $("#editatu_hizlaria_id_ikus_entzunezkoa").val($("#hidden_edit_id").val());
            
            // Leiho modalaren izenburuan Gehitu hizlaria jarri.
            $("#editatu_hizlaria_izenburua_etiketa").text("Gehitu hizlaria");
            
            // Irudiaren inputa garbitu
            $("#editatu_hizlaria_grafismoa_irudia").val("");
            
            // Aurretik egon daitezkeen Ikusi eta Ezabatu estekak kendu (eta tarteko | ere bai)
            $("#editatu_hizlaria_grafismoa_irudia_ikusi").remove();
            $('#editatu_hizlaria_grafismoa_irudia_banatzailea').remove();
            $("#editatu_hizlaria_grafismoa_irudia_ezabatu").remove();
            
            // Hizkuntza kopuruaren arabera beharrezko fieldset-ak sortu.
            $.ajax({
                
                type: "GET",
                dataType: "json",
                url: "<?php echo URL_BASE; ?>API/v1/hizkuntzak/"
                
            }).done(function(data, textStatus, jqXHR) {
                
                console && console.log(data);
                
                // Testuen fieldseta garbitu.
                $("#editatu_hizlaria_fieldset_edukinontzia").empty();
                
                for (var i = 0; i < data["hizkuntzak"].length; i++) {
                    
                    $("#editatu_hizlaria_fieldset_edukinontzia").append("<fieldset data-h_id='" + data["hizkuntzak"][i].id + "' id='editatu_hizlaria_fieldset_" + i + "'>" +
                            "<legend><strong>Testuak: " + data["hizkuntzak"][i].izena + "</strong></legend>" +
                            "<div class='control-group'>" +
                                "<label for='editatu_hizlaria_izena_" + data["hizkuntzak"][i].id + "'>Izena:</label>" +
                                "<input class='input-xlarge editatu_hizlaria_izena' type='text' id='editatu_hizlaria_izena_" + data["hizkuntzak"][i].id + "' data-h_id='" + data["hizkuntzak"][i].id + "' name='editatu_hizlaria_izena_" + data["hizkuntzak"][i].id + "' value='' />" +
                            "</div>" +
                            "<div class='control-group'>" +
								"<label for='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].id + "'>Aurrizkia:</label>" +
								"<input class='input-xlarge editatu_hizlaria_aurrizkia' type='text' id='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].id + "' data-h_id='" + data["hizkuntzak"][i].id + "' name='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].id + "' value='' />" +
							"</div>" +
                        "</fieldset>"
                    );
                }
                
                // Propietate orokorrak (hizkuntzei lotuak ez daudenak) berrezarri.
                $("#editatu_hizlaria_bilagarria").prop("checked", false);
                $("#editatu_hizlaria_kolorea").val("FFFFFF");
                $("#editatu_hizlaria_kolorea").css("background-color", "#FFFFFF");
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                
                console.log("Errore bat gertatu da zerbitzaritik bideoaren datuak eskuratzean.");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                
            });
            
        });
        
        $(document).on("click", ".editatu_hizlaria_botoia", function(event) {
            
            // editatu_hizlaria_botoia edo bere barruko <i>-a izan daitezke klikatutakoak. Biei gehitu diet data-id-hizlaria atributua.
            var id_hizlaria = $(event.target).attr("data-id-hizlaria");
            
            // id-a gorde gero datuak gorde behar badira ere.
            $("#editatu_hizlaria_id").val(id_hizlaria);
            
            // Leiho modalaren izenburuan Editatu hizlaria jarri.
            $("#editatu_hizlaria_izenburua_etiketa").text("Editatu hizlaria");
            
            // Irudiaren inputa garbitu
            $("#editatu_hizlaria_grafismoa_irudia").val("");
            
            // Aurretik egon daitezkeen Ikusi eta Ezabatu estekak kendu (eta tarteko | ere bai)
            $("#editatu_hizlaria_grafismoa_irudia_ikusi").remove();
            $('#editatu_hizlaria_grafismoa_irudia_banatzailea').remove();
            $("#editatu_hizlaria_grafismoa_irudia_ezabatu").remove();
            
            // Testuen fieldseta garbitu
            $("#editatu_hizlaria_fieldset_edukinontzia").empty();
            
            // Hizkuntza kopuruaren arabera beharrezko fieldset-ak sortu.
            $.ajax({
                
                type: "GET",
                dataType: "json",
                url: "<?php echo URL_BASE; ?>API/v1/hizlariak/" + id_hizlaria
                
            }).done(function(data, textStatus, jqXHR) {
                
                console && console.log(data);
                console.log(data["hizkuntzak"].length);
                
                for (var i = 0; i < data["hizkuntzak"].length; i++) {
                    
                    $("#editatu_hizlaria_fieldset_edukinontzia").append("<fieldset data-h_id='" + data["hizkuntzak"][i].h_id + "' id='editatu_hizlaria_fieldset_" + i + "'>" +
                            "<legend><strong>Testuak: " + data["hizkuntzak"][i].hizkuntza + "</strong></legend>" +
                            "<div class='control-group'>" +
                                "<label for='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "'>Izena:</label>" +
                                "<input class='input-xlarge editatu_hizlaria_izena' type='text' id='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].izena + "' />" +
                            "</div>" +
                            "<div class='control-group'>" +
								"<label for='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "'>Aurrizkia:</label>" +
								"<input class='input-xlarge editatu_hizlaria_aurrizkia' type='text' id='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].aurrizkia + "' />" +
							"</div>" +
                        "</fieldset>"
                    );
                }
                
                $("#editatu_hizlaria_kolorea").val(data["kolorea"]);
                
                // Hizlariak kolorerik badu kolore hori ezarri,
                // bestela zuriz margotu.
                if (data["kolorea"]) {
                    $("#editatu_hizlaria_kolorea").css("background-color", "#" + data["kolorea"]);
                } else {
                    $("#editatu_hizlaria_kolorea").css("background-color", "#FFFFFF");
                }
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                
                console.log("Errore bat gertatu da zerbitzaritik bideoaren datuak eskuratzean.");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                
            });
            
        });
        
        $("#editatu_hizlaria_gorde_botoia").click(function() {
            
            var datuak = {};
            
            // Ezkutuko elementu batean gorde dugun id berreskuratu
			var id_hizlaria = $("#editatu_hizlaria_id").val();
			var hizlari_kop = $("#hizlariak_taula tr").length;
			
			// hizlariak_taula-ri gehitu diogun katea.
			var katea = "";
            
            datuak["id_ikus_entzunezkoa"] = $("#editatu_hizlaria_id_ikus_entzunezkoa").val();
            datuak["id_hizlaria"] = id_hizlaria;
            datuak["kolorea"] = $("#editatu_hizlaria_kolorea").val();
            
            $("#editatu_hizlaria_fieldset_edukinontzia fieldset").each(function(index, element) {
                
                var h_id = $(element).attr("data-h_id");
                
                datuak["izena_" + h_id] = $("#editatu_hizlaria_izena_" + h_id).val();
                datuak["aurrizkia_" + h_id] = $("#editatu_hizlaria_aurrizkia_" + h_id).val();
                
            });
            
            console.log(datuak);
            
            $.ajax({
                
                type: "POST",
                url: "<?php echo URL_BASE; ?>API/v1/hizlariak/",
                dataType: "JSON",
                data: datuak
                
            }).done(function(data, textStatus, jqXHR) {
                
                if (id_hizlaria !== "0") {
                    
					// Existitzen den hizlari bat editatzen ari gara eta
					// bere izena eguneratu behar dugu zerrendan.
					$("#hizlaria_izena_" + id_hizlaria).html($("#editatu_hizlaria_izena_<?php echo $hizkuntza["id"]; ?>").val());
                    
				} else {
                    
					// Aurretik dauden hizlari guztiei ordena posible berri bat gehitu behar zaie
					$("#hizlariak_taula tr td select").each(function() {
						$(this).append("<option>" + hizlari_kop + "</option>");
					});
					
					katea = "<tr>" +
								"<td>" +
									"<select class='input-mini' name='orden_" + data["id_hizlari_berria"] + "' onchange='javascript:document.location=\"<?php echo $url_base . "form" . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_hizlaria=" + data["id_hizlari_berria"] + "&bal=\" + this.options[this.selectedIndex].value;'>";
					
					// Ordena posibleak: 0 eta lehendik dauden hizlari kopurua + 1 arteko guztiak.
					for (var i = 0; i <= hizlari_kop; i++) {
						katea = katea + "<option value='" + i + "'>" + i + "</option>";
					}
					
					katea = katea + "</select>" +
								"</td>" +
								"<td id='hizlaria_izena_" + data["id_hizlari_berria"] + "' class='td_klik'>" + $("#editatu_hizlaria_izena_<?php echo $hizkuntza["id"]; ?>").val() + "</td>" +
								"<td class='td_aukerak'>" +
								   "<a href='#editatu_hizlaria' data-id-hizlaria='" + data["id_hizlari_berria"] + "' role='button' class='btn editatu_hizlaria_botoia' data-toggle='modal'><i class='icon-pencil' data-id-hizlaria='" + data["id_hizlari_berria"] + "'></i></a>&nbsp;" +
								   "<a class='btn' data-toggle='tooltip' title='ezabatu' href='<?php echo $url_base . 'form' .  $url_param . '&edit_id=' . $edit_id; ?>&ezab_hizlaria_id=" + data["id_hizlari_berria"] + "' onclick='javascript: return(confirm(\"Seguru hizlaria ezabatu nahi duzula?\"));'><i class='icon-trash'></i></a>" +
								"</td>" +
							 "</tr>";
					
					// Hizlari berri bat sortu dugu eta
					// zerrendara gehitu behar dugu.
					$("#hizlariak_taula").append(katea);
				}
				
                // Ikus-entzunezko berri bat sortu badugu.
                if (data.id_ikus_entzunezko_berria && data.id_ikus_entzunezko_berria > 0) {
                    
                    // Ezkutuko input-ean jarri id-a, gordetzean dagokion balioa erabiltzeko.
                    $("#hidden_edit_id").val(data.id_ikus_entzunezko_berria);
                    
                    // Hizlariak editatzeko ezkutuko input-ean ere jarri id-a.
                    $("#editatu_hizlaria_id_ikus_entzunezkoa").val(data.id_ikus_entzunezko_berria);
                    
                }
                
				$("#editatu_hizlaria").modal('hide');
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                
                console.log("Errore bat gertatu da zerbitzaritik bideoaren datuak eskuratzean.");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                
            });
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
        
	
	<?php
            foreach (hizkuntza_idak() as $h_id) {
        ?>
        <fieldset>
            
            <legend><strong>Informazio orokorra</strong></legend>
            
            <div class="control-group">
                <label for="izenburua_<?php echo $h_id; ?>">Izenburua:</label>
                <input class="input-xxlarge" type="text" id="izenburua_<?php echo $h_id; ?>" name="izenburua_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input($ikus_entzunezkoa->hizkuntzak[$h_id]->izenburua); ?>" />
            </div>
            
            <div class="control-group">
                <label for="etiketak_<?php echo $h_id; ?>">Etiketak:</label>
                <input id="etiketak_<?php echo $h_id; ?>" name="etiketak_<?php echo $h_id; ?>" autocomplete="off" type="text" placeholder="Etiketak" class="tm-input input-xxlarge" />
            </div>
            
            <div class="control-group">
                <label for="azpitituluak_<?php echo $h_id; ?>">SRT azpitituluak:</label>
                <?php if (!is_file($_SERVER['DOCUMENT_ROOT'] . $ikus_entzunezkoa->hizkuntzak[$h_id]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$h_id]->azpitituluak)) { echo "<div class='alert'>OHARRA: Hipertranskribapena sortu ahal izateko SRT azpititulu bat gehitu eta gorde botoia sakatu behar duzu lehenik.</div>"; } ?>
                <input class="input-xxlarge" name="azpitituluak_<?php echo $h_id; ?>" type="file" id="azpitituluak_<?php echo $h_id; ?>" />
                <?php
                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $ikus_entzunezkoa->hizkuntzak[$h_id]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$h_id]->azpitituluak)) {
                        echo "<a href='" . URL_BASE . $ikus_entzunezkoa->hizkuntzak[$h_id]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$h_id]->azpitituluak . "' target='_blank'>Ikusi</a>";
                        echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&h_id=" . $h_id . "&ezabatu=AZPITITULUA\" onClick=\"javascript: return (confirm ('Azpitituluak ezabatzea aukeratu duzu. Ziur al zaude?'));\">Ezabatu</a>";
                    }
                ?>
                <button id="editatu-hipertranskribapena-botoia" type="button" class="btn"<?php if (!is_file ($_SERVER['DOCUMENT_ROOT'] . $ikus_entzunezkoa->hizkuntzak[$h_id]->path_azpitituluak . $ikus_entzunezkoa->hizkuntzak[$h_id]->azpitituluak)) {echo " disabled";} ?>>Editatu hipertranskribapena</button>
            </div>
            
        </fieldset>
        <?php
            }
        ?>
	
	
	<?php if($edit_id != 0){ // fitxa berria bada, fitxategia igotzeko eta hizlariak aukera kendu?>
        <fieldset>
            
            <?php if ($mezua != "") { ?>
                
                <div class="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $mezua; ?>
                </div>
                
            <?php } ?>
            
	    
            <legend><strong>Ikus-entzunezkoa</strong></legend>
            
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
		?>
                <?php
                        if (is_file($_SERVER['DOCUMENT_ROOT'] . $path . $jatorrizkoa)) {
                            echo "<a id='ikus-entzunezkoa-jatorrizkoa-ikusi' href='" . URL_BASE . $path . $jatorrizkoa . "' target='_blank'>Ikusi</a>";
                            echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&h_id=" . $h_id . "&ezabatu=".strtoupper($ikus_entzunezkoa->mota)."\" onClick=\"javascript: return (confirm ('Ziur zaude fitxategia ezabatu nahi duzula?'));\">Ezabatu</a>";
                        }
                ?>    
                
		
		
                <div class='alert'>OHARRA: Gehitu ikus-entzunezkoa eta gorde botoia sakatu behar duzu. Gehienez 100 MB.</div>
		<?php //<input class="input-xxlarge" name="ikus_entzunezkoa_jatorrizkoa" type="file" id="ikus_entzunezkoa_jatorrizkoa" /> ?>
                <span class="btn fileinput-button">
			<i class="glyphicon glyphicon-plus"></i>
			<span>Fitxategia aukeratu</span>
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
            
        </fieldset>
        
        <?php  if (is_file($_SERVER['DOCUMENT_ROOT'] . $path . $jatorrizkoa)) {?>
        <fieldset>
            <legend><strong>Azpititulu automatikoak</strong></legend>
            
            <a class="btn" href="<?php echo $url_base?>azpitituluak?edit_id=<?=$edit_id?>">Azpitituluak sortu</a>
            
		    
		</fieldset>
        <?php } ?>    
       
        <fieldset>
            
			<legend><strong>Hizlariak</strong></legend>
			
			<table id="hizlariak_taula" class="table table-bordered table-hover">
				
                <thead>
					<tr>
						<th width="50">Ordena</th>
						<th>Izena</th>
						<th width="85">
							<a id="gehitu_hizlaria_botoia" class="btn" href="#editatu_hizlaria" data-toggle="modal">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
						</th>
					</tr>
				</thead>
				
                <tbody>
					<?php
						$orden_max = orden_max("ikus_entzunezkoak_hizlariak", "fk_elem = " . $edit_id);
						
						foreach ($ikus_entzunezkoa->hizlariak as $elem) {
					?>
					<tr <?php if ($klassak) { echo current($klassak); } ?>>
						<td>
							<select class="input-mini" name="orden_<?php echo $elem->id; ?>" onchange="javascript:document.location='<?php echo $url_base . "form" . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_hizlaria=<?php echo $elem->id; ?>&bal=' + this.options[this.selectedIndex].value;">
								<option value="0">0</option>
							<?php for ($i = 1; $i <= ($elem->orden == 0 ? $orden_max + 1 : $orden_max); $i++){ ?>
								<option value="<?php echo $i; ?>"<?php echo $i == $elem->orden ? " selected" : ""; ?>><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</td>
						<td id="hizlaria_izena_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->hizkuntzak[$hizkuntza["id"]]->izena; ?></td>
						<td class="td_aukerak">
							<a href="#editatu_hizlaria" data-id-hizlaria="<?php echo $elem->id; ?>" role="button" class="btn editatu_hizlaria_botoia" data-toggle="modal"><i class="icon-pencil" data-id-hizlaria="<?php echo $elem->id; ?>"></i></a>
							<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_hizlaria_id=<?php echo $elem->id; ?>" onclick="javascript: return (confirm ('Seguru hizlaria ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
						</td>
					</tr>
					<?php if ($klassak && !next($klassak)) { reset($klassak); } } ?>
				</tbody>
                
			</table>
		
        </fieldset>
        <?php } // endif fitxa berria bada?>
        
        
        <div class="control-group text-center">
            <button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
            <button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
        </div>
    </form>
</div>

<div id="editatu_hizlaria" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editatu_hizlaria_izenburua_etiketa" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="editatu_hizlaria_izenburua_etiketa"></h3>
        </div>
        
        <div class="modal-body">
            <fieldset>
                <input type="hidden" name="gorde" value="BAI" />
                <input type="hidden" id="editatu_hizlaria_id" name="editatu_hizlaria_id" value="" />
                <input type="hidden" id="editatu_hizlaria_id_ikus_entzunezkoa" name="editatu_hizlaria_id_ikus_entzunezkoa" value="" />
                
                <span id="editatu_hizlaria_fieldset_edukinontzia"></span>
                
                <div class="control-group">
                    <label for="editatu_hizlaria_kolorea">Kolorea:</label>
                    <input class="color" id="editatu_hizlaria_kolorea" name="editatu_hizlaria_kolorea" />
                </div>
            </fieldset>
        </div>
        
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
            <button id="editatu_hizlaria_gorde_botoia" class="btn btn-primary" type="submit">Gorde</button>
        </div>
    </div>
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
	formData: [{name: 'edit_id', value: $("#hidden_edit_id").val() } ],
	dataType: 'json',
	autoUpload: false,
        acceptFileTypes: /(\.|\/)(mpe?g|webm|avi|mp4|mp3|ogg)$/i,
        limitMultiFileUploads: 1,
        disableVideoPreview: true,
        disableAudioPreview: true
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
                .text('Fitxategia igo')
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
            var error = $('<span class="text-danger"/>').text('Ezin izan da fitxategia igo.' + data.textStatus);
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

  
});




</script>
