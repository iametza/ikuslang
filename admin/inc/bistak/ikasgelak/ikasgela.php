<script type="text/javascript">
	function verifMezua() {
		// Mezuak ez du hutsik egon behar...
		if ($("#mezua").val() == "") {
			alert("Mezua hutsik dago");
			return false;
		}
		
		return true;
		
	}
	
	function verif(){
		return (confirm ("Ikasgela gorde?"));
	}
	
	function aukeratu_zerrendatik(elementua, eremua){
	
		var hidden = $('#hidden_' +eremua);
		var badago;
		var zerrenda_value = hidden.val();
		console.log(zerrenda_value);
		badago =zerrenda_value.match( new RegExp("(^|;)"+elementua['item'].id+"($|;)", "gi") );
		console.log(badago);
		if ( badago === null ){
			hidden.val( hidden.val() + elementua['item'].id + ";");
			var zerrenda_id = 'zerrenda_' + eremua;
			var zerrenda = $('#'+zerrenda_id);
			// konprobatu ez dagoela zerrendan
			
			zerrenda.append('<li>'+ ' <a href="#" hidden_id="hidden_'+eremua+'" class="ezabatu_elementua" id="'+elementua['item'].id+'">Ezabatu</a> - ' +elementua['item'].label + '</li>');
			var input_id = 'ac_' + eremua;
		}
		$('#'+input_id).val('');
	}
	
	$(function() {

		$( "#ac_ikasleak" ).autocomplete({
		source: "<?php echo URL_BASE_ADMIN?>ikasleak/bilatu",
		select: function (event, ui){
				event.preventDefault();
				aukeratu_zerrendatik(ui, 'ikasleak');
			}
		});
		$('#zerrenda_ikasleak').on('click', ".ezabatu_elementua", function(){
			var id = $(this).attr("id");
			var hidden = $('#'+$(this).attr("hidden_id"));
			$(this).parent().remove();
			var testua = hidden.val();
			testua = testua.replace(new RegExp("(^|;)"+id+"($|;)", "gi"), ";");
			hidden.val(testua);
		});
		
		
	});
	
	
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>ikasgelak">Ikasgelak</a> > <?php if ($edit_id) { echo $ikasgela->izena; } else { echo "Gehitu berria"; } ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>
<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#formularioa">Orokorra</a></li>
			<?php if($edit_id != ''){?>
			<li><a data-toggle="tab" href="#ikasleak">Ikasleak</a></li>
			<li><a data-toggle="tab" href="#ikasgaiak">Ikasgaiak</a></li>
			<?php }else{ ?>
			<li class="disabled"><a>Ikasleak</a></li>
			<li class="disabled"><a>Ikasgaiak</a></li>
			<?php }?>
		</ul>
	<div class="tab-content">
		
		<div id="formularioa" class="formularioa tab-pane active">
			<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
				<input type="hidden" name="gorde" value="BAI" />
				<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
				<input type="hidden" name="formulario_zatia" value="orokorra" />
				
				<fieldset>
					<div class="control-group">
						<label for="izena">Izena:</label>
						<input class="input-xxlarge" type="text" id="izena" name="izena" value="<?php echo testu_formatua_input ($ikasgela->izena); ?>" />
					</div>
					<div class="control-group">
						<label for="abizenak">Maila:</label>
						<select class="input-xxlarge" name="fk_maila" id="fk_maila">
								<option value="0">&nbsp;</option>
								<?php foreach (get_query ("SELECT id, izena FROM mailak ORDER BY izena") as $row){ ?>
								<option value="<?php echo $row["id"]; ?>"<?php echo ($row["id"] == $ikasgela->fk_maila ? " selected" : ""); ?>><?php echo $row['izena']; ?></option>
								<?php
									}
								?>
							</select>
					</div>
					<?php
					// irakaslea bada, zuzenean asignatu berea bezala
					if($erabiltzailea->get_rola() == 'irakaslea'){?>
					<input type="hidden" name="fk_irakaslea" value="<?=$erabiltzailea->get_fk_irakaslea()?>">
					<?php }elseif($erabiltzailea->get_rola() == 'admin'){?>
					<div class="control-group">
						<label for="izena">Irakaslea:</label>
						<select class="input-xxlarge" name="fk_irakaslea" id="fk_irakaslea">
								<option value="0">&nbsp;</option>
								<?php foreach (get_query ("SELECT id, izena, abizenak FROM irakasleak ORDER BY abizenak") as $row){ ?>
								<option value="<?php echo $row["id"]; ?>"<?php echo ($row["id"] == $ikasgela->fk_irakaslea ? " selected" : ""); ?>><?php echo $row['abizenak'].", ". $row['izena']; ?></option>
								<?php
									}
								?>
							</select>
					</div>
					<?php }?>
					
				</fieldset>
				<div class="control-group text-center">
					<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
					<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
				</div>
				</form>
			
		</div>
		<div id="ikasleak" class="tab-pane">
			<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param . "&edit_id=" . $edit_id; ?>#ikasleak" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verifMezua();">
				<fieldset>
					<input type="hidden" name="alerta" value="BAI" />
					<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
					<label for="mezua">Ikasgela honetako ikasleei tabletera alerta bat bidaltzeko idatzi mezua eta sakatu Bidali alerta botoia.</label>
					<input type="text" class="input-xxlarge" id="mezua" name="mezua" value="">
					<button type="submit" class="btn"><i class="icon-envelope"></i>&nbsp;Bidali alerta</button>
				</fieldset>
			</form>
			<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
				<input type="hidden" name="gorde" value="BAI" />
				<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
				<input type="hidden" name="formulario_zatia" value="ikasleak" />
				<fieldset>
					<div class="control-group">
						<label for="konbo_ikasleak">Ikasleak:</label>
									
						 <select id="konbo_ikasleak" name="ikasleak[]" multiple="multiple">
								<?php foreach($ikasle_guztiak as $ikaslea){?>
								<option <?php if(in_array($ikaslea['id'], $ikasle_idak)){?>selected="selected"<?php }?> value="<?=$ikaslea['id']?>" ><?=$ikaslea['izen_abizenak']?></option>
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
		
		
		<div id="ikasgaiak" class="tab-pane">
			<div>
				
				<div class="pull-right" style="margin-bottom:10px;">
					<a class="btn" href="<?php echo URL_BASE_ADMIN; ?>ikasgaiak/form?fk_ikasgela=<?=$edit_id?>">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
				</div>
			</div>
			<div style="clear:both"></div>
			<div>
				
			<form id="f1" name="f1" method="post" action="<?php echo URL_BASE_ADMIN . "ikasgaiak/form?fk_ikasgela=".$edit_id  ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
				<input type="hidden" name="gorde" value="BAI" />
				<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
				<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Izena</th>
						<th>Hasiera / bukaera data</th>
						<th>Ariketak</th>
						
						<th width="130">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $klassak = array ('', 'class="info"'); ?>
					<?php
						if($ikasgela->ikasgaiak === NULL) {
							$ikasgela->ikasgaiak = array();
						}
						
						foreach ($ikasgela->ikasgaiak as $elem) {
							
						$ikasgaia_datuak = get_ikasgaia_datuak($elem['id']);
					?>
					<tr <?php echo current ($klassak); ?>>
						<td class="td_klik"><?php echo elementuaren_testua("ikasgaiak", "izenburua", $elem["id"], $hizkuntza["id"]); ?></td>
						<td class="td_klik"><?php echo $elem['hasiera_data']?> / <?php echo $elem['bukaera_data']?></td>
						<td class="td_klik"><?php echo count($ikasgaia_datuak['ariketak'])?></td>
						<td class="td_aukerak">
						<a class="btn" data-toggle="tooltip" title="emaitzak" href="<?php echo URL_BASE_ADMIN . "ikasgaiak/emaitzak" . $url_param; ?>&edit_id=<?php echo $elem["id"]."&fk_ikasgela=".$elem["fk_ikasgela"]; ?>"><i class="icon-list"></i></a>
							<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo URL_BASE_ADMIN . "ikasgaiak/form?edit_id=" . $elem["id"]."&fk_ikasgela=".$elem["fk_ikasgela"]; ?>"><i class="icon-pencil"></i></a>
							<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo URL_BASE_ADMIN . "ikasgelak/form?edit_id=" . $elem["fk_ikasgela"] . "&ezab_ikasgaia_id=" . $elem["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
							
							
						</td>
					</tr>
					<?php if (!next ($klassak)) {
							reset ($klassak);
							}
						}
					?>
				</tbody>
				</table>
				
				
			<?php /*</form>*/?>
			</div>
		</div>
	</div>
</div><?php // end tabs?>

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/chosen/chosen.jquery.js"></script>
<script type="text/javascript">
		$(function() {
				$("#konbo_ikasleak").chosen({
				placeholder_text_multiple : "Aukeratu...",
				no_results_text : "Emaitzarik ez",
				width: "95%"
				});
		});
</script>