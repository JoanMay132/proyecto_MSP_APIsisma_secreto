<?php 
  include '../dependencias/php/head.php';
  include '../controlador/Sucursal.php';
  include '../controlador/Revpreeliminar.php';
  include '../class/Fecha.php';
  $popper = true;

  $obsuc = new Sucursal();
  $oRev = new Revpreeliminar();

  $idSuc = 1;
   ?>

  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0">
      
        <div class="row" style="margin-top:-10px;">
            <div class="form-group col-12 col-sm-1">
                <a href="javascript:ventana1('addlevantamiento')" class="btn btn-primary btn-sm">Nuevo</a>
            </div>&nbsp;&nbsp;
            <div class="form-group col-12 col-sm-2 text-center" style="padding:2px;margin-top:-10px">
                <label class="txt-12 text-secondary">Levantamiento</label>
                <input type="text" id="lev" class="form-control form-control-sm txt-12 text-center" style="height:25px;margin-top:-10px;font-size:11px">
            </div>

            <div class="form-group col-12 col-sm-2 text-center" style="padding:2px;margin-top:-10px">
                <label class="txt-12 text-secondary">Cliente</label>
                <input type="text" class="form-control form-control-sm txt-12 text-center" style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="padding:2px;margin-top:-10px">
                <label class="txt-12 text-secondary">Sucursal</label>
                <select id="sucursalView" class="form-control txt-12 text-center" style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
                  <?php foreach($obsuc->GetDataAll() as $ressuc){ ?>
                  <option><?php echo $ressuc['nombre']; ?></option>
                  <?php } ?>
                </select>
              
            </div>
            <div class="form-group col-12 col-sm-1 text-center" style="padding:2px;margin-top:-10px">
                <label class="txt-12 text-secondary">Año</label>
                <select class="form-control txt-12 text-center" style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
                  <option>2023</option>
                  <option>2022</option>
                </select>
              
            </div>
        </div>
        <div class="row" style="margin-top:-15px;">
            <div class="col-12">
                <div style="overflow:auto;height: 83vh;margin-bottom:-58px" id="display-revpreeliminar">
                  
                    <table class="table-stripped table-hover display-table"  width="100%" id="table" style="background-color: white;font-size:11px">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;" >
                          <tr style="border:1px solid #DFDFDF;">
                            <th>LEVANTAMIENTO</th>
                            <th>FECHA</th>
                            <th>CLIENTE</th>
                            <th>RECIBIO</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="revision">
                          <?php foreach($oRev->GetDataJoin($idSuc) as $result){ ?>
                          <tr style="font-weight:bold" ondblclick="javascript:ventana1('editrevpreeliminar?edit=<?php echo base64_encode($result['pkrevpreeliminar']); ?>')" >
                            <td style="border-right:1px solid #DFDFDF"><p id="folio" style="margin-left:3px;padding:3px"><?php echo $result["folio"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php if($result["fecha"] != '0000-00-00') echo Fecha::convertir($result["fecha"]); ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["ncliente"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["nempleado"]." ".$result["apellidos"]; ?></p></td>
                          </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </main>

<?
  include_once '../dependencias/php/footer.php';
?>
<script>
  $(document).ready(function(){
    $("#rev").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#revision tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
  });

    function ventana1(URL){ 
      ventanaEmergente = window.open(URL,"","width=1000,height=600,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0" );

    }
   
</script>