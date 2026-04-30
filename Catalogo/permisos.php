<?php @session_start(); {$title = "-PERMISOS"; }

if($_SESSION['tipo_user'] != 'ADMIN' && $_SESSION['tipo_user'] != 'ROOT'){
    echo '<script>window.close();</script>';
    exit;
 }

  include '../dependencias/php/head.php';
  include_once '../controlador/Sucursal.php';
  include_once '../controlador/Controles.php';
  include_once '../class/Controles.php';

  

  $control = new Controles();

    $operations = $control->getOperations();

    $user = $_GET['user']; //Se obtine el id pasado por la URL
    $sucursal = $_GET['suc'];

    $resUser = $control->getPermission(base64_decode($user),base64_decode($sucursal));
   
  $nameModule = "";
  $beforeModule = "";

 //Configuracion de los controles con costos
  $inCosto = [Controls::revpreeliminar->value,Controls::cotizacion->value,Controls::analisiscosto->value];
  ?>
<style>
    body{
        background:white;
    }
    .form-control{
        border-radius: unset ;
        height:20px;
        font-size:11px;
        padding:2px;
      }
      .title-permiso{
        background:cadetblue;
        padding:5px;
        color:white
      }
</style>
  <div style="width:100%;margin:0;padding:0px">
    <!-- ! Main -->
    <main >
     
            
            <div class="row" style="width:100%;margin:0px">
        
            <div class="col-12" style="margin:0px;padding:0px">
            <div class="card" style="border:0px;">
            <form id="form-permisos">
                <input type="hidden" name="usuario" value="<?php echo $user; ?>" id="usuario">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 col-sm-8" style="color:darkblue">ASIGNACIÓN DE PERMISOS A: <strong>FREDDY HERNANDEZ JIMENEZ</strong></div>
                        <div class="col-3 col-sm-2">Sucursal:</div>
                        <div class="col-3 col-sm-2">
                        <select name="sucursal" class="form-control" required id="sucursalView" onchange="return selecciona();">
                                <?php 
                                        $obsuc = new Sucursal();
                                        foreach($obsuc->GetDataAll() as $data){
                                            $selSuc = base64_decode($sucursal) == $data['pksucursal'] ?'selected':'';
                                    ?>
                                    <option <?php echo $selSuc; ?> value="<?php echo base64_encode($data["pksucursal"]); ?>"><?php echo $data["nombre"]; ?></option>
                                    <?php }?>
                                </select>
                        </div>
                    </div>
                   
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <table width="100%" style="font-size:11px">
                                    <?php foreach ($control->getControls() as $value) {
                                        $nameModule = $value['modulo'];
                                        $control = base64_encode($value['pkcontrol']); //Se obtiene la clave del control
                                        if($beforeModule != $nameModule){ ?>
                                    
                                    
                                    <tr style="text-align: center;">
                                    
                                        <th style="width:25%;"><div class="title-permiso"><p><?php echo $value['modulo']; ?></p></div></th>

                                        <?php foreach($operations as $operation){ ?>
                                            <th style="width:15%;background:antiquewhite"><?php echo $operation['nombre']; ?></th>
                                        <?php } ?>
                                    </tr>
                                    <tr style="text-align: center;">
                                        <td style="text-align:right;"><?php echo $value['nombre']; ?></td>
                                        <input type="hidden"  name="control[]" value="<?php echo $control; ?>" />
                                    <?php foreach($operations as $operation){
                                                $values = base64_encode($operation['pkoperacion']);
                                                $name = $operation['nombre'];
                                                $selected = null;
                                                $input = null;
                                                $disabled = false;
                                                foreach ($resUser as $valuePermission) {
                                                     if($valuePermission['fkcontrol'] === $value['pkcontrol'] && $valuePermission['fkoperacion'] === $operation['pkoperacion']){
                                                            $selected = 'checked="true"';
                                                            $input = '<input type="hidden"  name="permiso[]" value="'.$valuePermission['pkpermiso'].'" />';
                                                            break;
                                                     }else{ $selected = ''; }
                                                }
                                               
                                                $disabled = !in_array($value['pkcontrol'],$inCosto) && $name === 'VER COSTOS' ? true : false;
                                                if($disabled){
                                                echo  '<td>'.$input.'<input '.$selected.' value="'.$values.'" disabled type="checkbox" onchange="return addPermission(this);" name="'.$name.'[]"></td>'; }
                                                    else{
                                                        echo  '<td>'.$input.'<input '.$selected.' value="'.$values.'" type="checkbox"  onchange="return addPermission(this);" name="'.$name.'[]"></td>';
                                                    }
                                             } 
                                             
                                     echo '</tr>'; 
                                    
                                } else{ ?>
                                        
                                    <tr style="text-align: center;">
                                        <td style="text-align:right;"><?php echo $value['nombre']; ?></td>
                                        <input type="hidden"  name="control[]" value="<?php echo $control; ?>" />
                                        <?php foreach($operations as $operation){
                                                $values = base64_encode($operation['pkoperacion']);
                                                $name = $operation['nombre'];
                                                $selected = null;
                                                
                                                foreach ($resUser as $valuePermission) {
                                                    if($valuePermission['fkcontrol'] == $value['pkcontrol'] && $valuePermission['fkoperacion'] == $operation['pkoperacion']){
                                                           $selected = 'checked="true"';
                                                           $input = '<input type="hidden"  name="permiso[]" value="'.$valuePermission['pkpermiso'].'" />';
                                                           break;
                                                    }
                                               }

                                               $disabled = !in_array($value['pkcontrol'],$inCosto) && $name === 'VER COSTOS' ? true : false;
                                                if( $disabled){

                                                echo '<td>'.$input.'<input '.$selected.' disabled onchange="return addPermission(this);" value="'.$values.'"  type="checkbox" name="'.$name.'[]"></td>';}
                                                else{
                                                    echo '<td>'.$input.'<input '.$selected.' onchange="return addPermission(this);" value="'.$values.'"  type="checkbox" name="'.$name.'[]"></td>';
                                                }
                                             }        
                                    echo '</tr>';     }  
                                  $beforeModule = $nameModule;  } ?>

                                </table>
                            </div>
                            
                                
                        </div>                  
                </div>
                </form> <br> 
            </div>

        </div>
            
        </div>
       

    </main>
<?php
  include_once '../dependencias/php/footer.php';
?>
<script src="../dependencias/js/cargas.js" type="text/javascript"></script>
<script type="text/javascript" src="../dependencias/js/Catalogo/Controles.js"></script>
<script>
    function selecciona(){
        let suc =  document.getElementById('sucursalView').value;
        let usuario = document.getElementById('usuario').value;

            location.href = "permisos?user="+usuario+"&suc="+suc;

    }
</script>


