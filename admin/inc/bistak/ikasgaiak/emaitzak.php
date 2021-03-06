<?php

?>
<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE_ADMIN; ?>ikasgelak/form?edit_id=<?php echo $fk_ikasgela?>#ikasgaiak">Ikasgaiak</a> >
        <?php if ($edit_id) { echo $ikasgaia->hizkuntzak[$hizkuntza["id"]]->izenburua; } else { echo "Gehitu berria"; } ?>
                > Ariketen emaitzak</div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo URL_BASE_ADMIN; ?>ikasgelak/form?edit_id=<?php echo $fk_ikasgela?>#ikasgaiak"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>


<div id="formularioa" class="formularioa">
	<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
		<input type="hidden" name="gorde" value="BAI" />
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
		<input type="hidden" name="fk_ikasgela" value="<?php echo $ikasgaia->fk_ikasgela; ?>" />
		
        
        <table class="table">
            <thead>
            <tr>
                <th>Ikaslea</th>
                <th>Emaitza</th>
                <th>Noiz</th>
                <th>Oharrak</th>
            </tr>
            </thead>  
        <?php foreach($ikasgaia->ariketak as $ariketa){?>
         
            <tbody>
                <tr>
                    <td><h4><?php echo get_dbtable_field_by_id_hizkuntza ('ariketak', 'izena', $ariketa['id'], $hizkuntza['id'])?></h4></td>
                </tr>
                <?php foreach($ikasgela->ikasleak as $ikaslea){
                    // azken emaitza jasotzen dugu
                    if(isset( $emaitzak_ikasleka[$ikaslea['id']][$ariketa['id']][0] ) ){
                        $emaitza = $emaitzak_ikasleka[$ikaslea['id']][$ariketa['id']][0];
                        $zuzenak = $emaitza['zuzenak'];
                        $guztira = $emaitza['zuzenak'] + $emaitza['okerrak'];
                        $emaitza_testua = $zuzenak . " / " . $guztira;
                        $emaitza_data = $emaitza['data'];
                    }else{
                        $emaitza_testua = "Egiteko";
                        $emaitza_data = "-";
                    }
                    ?>
                <tr>
                    <td data-e-posta="<?php echo $ikaslea['e_posta']; ?>"><?php echo $ikaslea['izen_abizenak']?></td>
                    <td><?php echo $emaitza_testua?></td>
                    <td><?php echo $emaitza_data?></td>
                    <td>
                        <a class="btn oharra-bistaratu-botoia" type="button" name="oharra" data-toggle="modal" data-target="#oharrakModal" >
                        <i class="icon-comment"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        
        
        <?php }?>
        </table>
		
	</form>
</div>


<div id="oharrakModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Oharra<img id="oharrak-modala-spinnerra" style="display: none;" src="<?php echo URL_BASE; ?>img/spinner.gif"></h3>
  </div>
  <div class="modal-body">
    <form method="post" action="">
        <input type="hidden" name="">
        <label><b>Ariketa</b>:</label>
        <label class="oharra-ariketa"></label>
        <label><b>Ikaslea</b>:</label>
        <label class="oharra-ikaslea"></label>
        <label><b>E-posta</b>:<label>
        <label class="oharra-e-posta"></label>
        <label for="oharra"><b>Oharra</b>:</b></label>
        <textarea name="oharra" id="oharra" rows="4" style="width:70%"></textarea>
    </form>
  </div>
  <div class="modal-footer">
   
    <button class="btn oharra-bidali">Bidali</button>
  </div>
  <div class="modal-done" style="display: none;">
    <div class="alert alert-success">
        Oharra bidali da.
    </div>
    <div class="alert alert-error">
        Oharra bidaltzean arazo bat gertatu da. Ziurtatu mezua idatzi duzula eta saiatu berriro.
    </div>
  </div>
</div>


<script type="text/javascript">
	
	$(function() {
        
        $(".oharra-bistaratu-botoia").on("click", function() {
            $(".modal-body .oharra-ariketa").html($.trim($(this).closest('tr').siblings(':first-child').text()));
            $(".modal-body .oharra-ikaslea").html($.trim($(this).closest("td").prev().prev().prev().text()));
            $(".modal-body .oharra-e-posta").html($.trim($(this).closest("td").prev().prev().prev().attr("data-e-posta")));
            $(".modal-body #oharra").html("");
        });
        
        $('#oharrakModal').on('show', function () {
            $(".modal-body").show();
            $(".modal-footer").show();
            $(".modal-done").hide();
            $(".modal-done .alert-success").hide();
            $(".modal-done .alert-error").hide();
        })
     
        $("#oharrakModal").on("click", ".oharra-bidali", function(){
            
            $("#oharrak-modala-spinnerra").show();
            
            $.ajax({
                type: "POST",
                url: "<?php echo URL_BASE; ?>API/v1/bidaliOharra",
                data: {
                    gaia: "[Ikuslang] Irakaslearen oharra: " + $(".modal-body .oharra-ariketa").text(),
                    mezua: $("#oharra").val(),
                    e_posta: $(".modal-body .oharra-e-posta").text()
                }
            })
            .done(function(data, textStatus, jqXHR) {
                $("#oharra").val('');  
                $(".modal-body").hide();
                $(".modal-footer").hide();
                $(".modal-done").show();
                $(".modal-done .alert-error").hide();
                $(".modal-done .alert-success").show();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                $(".modal-done").show();
                $(".modal-done .alert-error").show();
            })
            .always(function() {
                $("#oharrak-modala-spinnerra").hide();
            });
        })
		
	});
	
	
</script>