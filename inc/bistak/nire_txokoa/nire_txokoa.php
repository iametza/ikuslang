
<div id="nire-txokoa-egin-beharreko-ariketak" class="col-md-12">
<h3>Kaixo <?php echo $erabiltzailea->get_erabiltzailea(); ?></h3> 
    
    <div class="tabbable"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab">Egin beharreko ariketak</a></li>
            <li><a href="#tab2" data-toggle="tab">Egindako ariketak</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
            
                <table class="table table-striped">
        
        <thead>
            <td><strong>Izena</strong></td>
            <td><strong>Mota</strong></td>
            <td><strong>Ikasgaia</strong></td>
            <td><strong>Epea</strong></td>
        </thead>
        
        <?php foreach ($ariketak->egitekoak as $egitekoa) { ?>
        <tr>
            <td>
                <a href="<?php echo URL_BASE . "ariketa/" . $egitekoa["id"] . "?id_ikasgaia=" . $egitekoa["id_ikasgaia"]; ?>"><?php echo $egitekoa["izena"]; ?></a>
            </td>
            <td><?php echo $egitekoa["ariketa_mota"]; ?></td>
            <td><?php echo $egitekoa["ikasgaia"]; ?></td>
            <td><?php echo $egitekoa["bukaera_data"]; ?></td>
        </tr>
        <?php } ?>
        
    </table>
            
            </div>
            <div class="tab-pane" id="tab2">
                
                <table class="table table-striped">
        
        <thead>
            <td><strong>Izena</strong></td>
            <td><strong>Mota</strong></td>
            <td><strong>Ikasgaia</strong></td>
            <td><strong>Epea</strong></td>
            <td><strong>Eginda</strong></td>
        </thead>
        
        <?php foreach ($ariketak->egindakoak as $egindakoa) { ?>
        <tr>
            <td>
                <a href="<?php echo URL_BASE . "ariketa/" . $egindakoa["id"]. "?id_ikasgaia=" . $egindakoa["id_ikasgaia"]; ?>"><?php echo $egindakoa["izena"]; ?></a>
            </td>
            <td><?php echo $egindakoa["ariketa_mota"]; ?></td>
            <td><?php echo $egindakoa["ikasgaia"]; ?></td>
            <td><?php echo $egindakoa["bukaera_data"]; ?></td>
            <td><?php echo $egindakoa["egindako_data"]; ?></td>
        </tr>
        <?php } ?>
        
    </table>
                
            </div>
        </div>
    </div>

    
</div>

