<?php @session_start(); {$title = '- SERVICIOS';}
  include '../dependencias/php/head.php';
  include_once '../controlador/Servicios.php';
  include_once '../controlador/Catalogo.php';

$popper = true;
$oServ = new Servicios();
$oCat = new Catlogo();

$sucursal = (int) base64_decode($_GET['suc']);
$catalogo = isset($_GET['catalogo']) && !empty(@$_GET['catalogo']) ? base64_decode($_GET['catalogo']) : 1; //Default
$tipo = $_GET['tipo'] ?? '';
switch ($tipo) {
    case 'revpreeliminar':
        $rol->getPermissionControl($_SESSION['controles'],Controls::revpreeliminar->value,$sucursal);
        break;
    case 'cotizacion':
            $rol->getPermissionControl($_SESSION['controles'],Controls::cotizacion->value,$sucursal);
            break;
    
    default:
        echo "NO SE HA ESPESIFICADO UN TIPO DE SERVICIO";
        break;
        
}

//Se verifica las operaciones
$viewCosto = !in_array(Operacion::costo->value,$rol->getOperacion()) ? 'view' : '';

  ?>
<style>
    .view{
    filter: blur(5px);
    pointer-events: none;
    user-select: none;
}
</style>
  <div style="width:100%">
    <!-- ! Main -->
        <div class="row" style="margin:0;width:100%">
            <div class="col-12" style="width:100%">
                    <div class="row" >
                        <div class="col-12" >
                            <div style="width:100%;font-size:12px;margin-top:10px;margin-bottom:10px;">
                                <?php
                                    if(isset($_GET['catalogo']) && !empty(@$_GET['catalogo'])){
                                        $url = preg_replace('/&catalogo=[^&]*/', '', $_SERVER['REQUEST_URI']);
                                    }
                                    foreach ($oCat->GetDataAll($sucursal) as $value) {
                                            $active = isset($catalogo) && $catalogo == $value['pkcatalogo'] ? 'active-serv' : '';
                                        ?>
                                        <a href="<?php echo  isset($url) ? $url.'&catalogo='.base64_encode($value['pkcatalogo']) : $_SERVER['REQUEST_URI'].'&catalogo='.base64_encode($value['pkcatalogo']); ?>" class="head-servicios <?php echo $active; ?>"><?php echo $value['nombre_cat']; ?></a>
                                 <?php   }
                                ?>

                            </div>

                        </div>
                    </div>

                    
                    <form style="margin-bottom:10px;">
                    <div class="row">
                        <div class="col">
                        <input type="text" class="form-control form-control-sm" onkeyup="filtro(this,'f-descripcion');" style="height:25px;font-size:11px;padding:2px" placeholder="Buscar por descripcion">
                        </div>
                        <div class="col">
                        <input type="text" class="form-control form-control-sm" onkeyup="filtro(this,'f-item');" style="height:25px;font-size:11px;padding:2px" placeholder="Buscar por sub item">
                        </div>
                    </div>
                    </form>
                  
                    <div class="table-responsive" style="overflow: auto;height:88vh;">
                      <table class="table table-striped table-sm txt-11" style="background:white;padding:0px;width:100%;">
                            <thead class="table-primary" style="position:sticky;top:0;z-index:1" >
                                <tr>
                                    <th>ITEM</th>
                                    <th>TIPO SERVICIO</th>
                                    <th>SUBITEM</th>
                                    <th>DESCRIPCION</th>
                                    <th>QTY</th>
                                    <th>UM</th>
                                    <th>COSTO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="servicios">
                                <?php 
                                
                                $contador = 0; foreach ($oServ->GetDataAll($sucursal,$catalogo) as $dataServ) { ?>
                                  
                                <tr style="text-align:center;color:#094264;">
                                    <input type="hidden" id="pkservicio-<?php echo $contador;?>" value="<?php echo $dataServ['pkservicios']; ?>">
                                    <td><div style="border:1px solid #D3D3D3 " id="fkgruposerv-<?php echo $contador;?>"><?php echo $dataServ['item']; ?></div></td>

                                    <td width="15%"><div style="border:1px solid #D3D3D3 " id="nombre-<?php echo $contador;?>"><?php echo $dataServ['tipo']; ?></div></td>

                                    <td id="f-item"><div style="border:1px solid #D3D3D3 " id="item-<?php echo $contador;?>"><?php echo $dataServ['subitem']; ?></div></td>

                                    <?php if($catalogo == 21){ //Se agrega tipo de servicio el la descripción ?>
                                        <td id="f-descripcion"><div style="border:1px solid #D3D3D3;font-weight:bold;text-align:left" id="descripcion-<?php echo $contador;?>"><?php echo '<div style=\'display:none\'>'.$dataServ['tipo'].'</div>'.' '.mb_convert_encoding($dataServ['descripcion'], 'UTF-8', 'UTF-8'); ?></div></td>
                                    <?php }else{ ?>
                                        <td id="f-descripcion"><div style="border:1px solid #D3D3D3;font-weight:bold;text-align:left" id="descripcion-<?php echo $contador;?>"><?php echo mb_convert_encoding($dataServ['descripcion'], 'UTF-8', 'UTF-8'); ?></div></td>
                                    <?php }?>
                                    
                                    <td><div style="border:1px solid #D3D3D3 " ><?php echo $dataServ['qty']; ?></div></td>
                                   
                                    <td><div style="border:1px solid #D3D3D3 " ><?php echo $dataServ['um']; ?></div></td>

                                    <td><div style="border:1px solid #D3D3D3" class="<?php echo $viewCosto?>" id="costo-<?php echo $contador;?>">$<?php echo number_format($dataServ['costo'], 2, '.', ','); ?></div></td>
                                    <?php if(isset($_GET['new']) && $_GET['new'] == 'true'){ ?>
                                        <td><button onclick="return add(this,<?php echo $_GET['row'];?>,true);" data-fil='<?php echo $contador;?>' style="padding:3px;font-size:10px;background:white;border:1px solid #D3D3D3;color:grey;width:100%;border-radius:3px ">Agregar</button></td>
                                        <?php }
                                            elseif(isset($_GET['newLoad']) && $_GET['newLoad'] == 'true'){ ?>

                                            <td><button onclick="return add(this,<?php echo $_GET['row'];?>,false,true);" data-fil='<?php echo $contador;?>' style="padding:3px;font-size:10px;background:white;border:1px solid #D3D3D3;color:grey;width:100%;border-radius:3px ">Agregar</button></td>

                                           <?php }
                                        else{ ?>
                                    <td><button onclick="return add(this,<?php echo $_GET['row'];?>);" data-fil="<?php echo $contador;?>" style="padding:3px;font-size:10px;background:white;border:1px solid #D3D3D3;color:grey;width:100%;border-radius:3px ">Agregar</button></td>
                                            <?php } ?>
                                </tr>
                                <?php $contador++; } ?>

                               
                            </tbody>
                      </table> 
                      </div>
            </div>
        </div>


<?php
    

  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Trazabilidad/Servicios.js"></script>
<script>
    function filtro(data,columna){
    var value = $(data).val().toLowerCase();
    $("#servicios tr").each(function() {
      let found = false;
      $(this).find('#'+columna).each(function(){
          if($(this).text().toLowerCase().indexOf(value) > -1){
            found = true;
            return false;
          }
      });
      $(this).toggle(found);
    });
  }
</script>


    