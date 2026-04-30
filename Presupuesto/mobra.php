 <?php @session_start();  {$title = '- MANO DE OBRA';}
    
    include '../dependencias/php/head.php';
    spl_autoload_register(function ($class) {
        include_once "../controlador/" . $class . ".php";
    });
    $popper = true;
    $obunidad = new Unidad();
    $obsuc = new Sucursal();
    $oMobra = new Mobra();

    $suc =(int) base64_decode($_GET['suc']); //Sucursal a mostrar

    foreach ($obunidad->GetDataAll() as $value) {
        $data[] = array("pkunidad" => $value['pkunidad'], "nombre" => $value['nombre']);
    }
#region Permisos
    if(!$rol->getPermissionControl($_SESSION['controles'],Controls::manobra->value,$suc)){
        echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
        return false;
    }
    $rol->getBranchInPermission($_SESSION['controles'],Controls::manobra->value);
    $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
#endregion

    $budget = False;
    if(isset($_GET['budget']) && $_GET['budget'] == 'true') { $budget = True; } //Verifica el budget
    ?>
 <style>
     #draggable {
         border: 2px solid green;
         box-shadow: 0px 0px 20px grey;
         background: white;
         width: 100%;
         height: 100vh;
     }

     .modal-body {
         overflow: auto;
         height: 80vh;
     }

     .modal-backdrop.show {
         opacity: 0;
     }

     table input.form-control,
     table select.form-control {
         height: 23px;
         /* text-transform: uppercase; */
     }

     button {
         height: auto;
         font-size: 14px;
         padding: 3px;
         background: #007910;
         border-radius: 3px;
         color: white;
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
     }

     button:hover {
         opacity: 0.9;

     }
 </style>
 <!-- VENTANA MODAL MATERIALES-->
 <center><div class="loader"><h3>Cargando datos</h3></div></center>
 <div id="draggable">
     <form id="form-MObra" onsubmit="return addMObras();">
         <div class="modal-header">
             <h6>MANO DE OBRA</h6>
             <select name="sucursal" id="sucursal" onchange="CambioSucursal(this.value)" class="form-control form-control-sm txt-11" style="width:auto;float:right;height:23px" required>
                <option value=""></option>
                <?php
                    foreach($obsuc->GetDataAll() as $sucursal){
                        $selec = $suc == $sucursal['pksucursal'] ? "selected" : "";
                        if(in_array($sucursal['pksucursal'],$rol->getBranch()) ){
                ?>
                                    <option value="<?php echo base64_encode($sucursal['pksucursal']); ?>" <?php echo $selec ?>><?php echo $sucursal["nombre"]; ?></option>
                <?php } }?>
            </select>
            
         </div>
         <!-- <input type="search" class="form-control form-control-sm " placeholder="Buscar" id="search"> -->
         <div class="modal-body" id="view-MObra" style="width:100%;padding:0px">
                <div id="display-MObra">
             <input type="search" class="form-control form-control-sm" style="position: fixed;z-index:1" placeholder="Buscar" id="search"><br>
             <table class="table table-sm table-bordered txt-11">
                 <thead class="table-primary">
                     <?php if($budget) { echo "<th></th>"; } ?>
                     <th width="60%">BIEN</th>
                     <th>ORIGEN</th>
                     <th>COSTO X HORA</th>
                 </thead>
                 <tbody class="body-table table-MObra" >
                    <?php
                        foreach($oMobra->GetDataAll($suc) as $obra){ ?>
                                <tr ondblclick='addMObra(<?php echo $budget; ?>)' id="rowReg-<?php echo $obra['pkoperario']; ?>" onclick="menu(this);">
                                    <?php if($budget) { ?>
                                        <td width=10px><button class="btn btn-sm btn-info" style="padding: 0px;" title="Agregar a materiales" data-toggle="tooltip" onclick='return add(this,<?php echo json_encode($data); ?>);' ><small class="fa fa-arrow-left" style="font-size:15px;"></small></button></td>
                                    <?php } ?>
                                    <input type="hidden" name="pkoperario[]" value="<?php echo base64_encode($obra['pkoperario']); ?>">
                                    <td><input type="text" name="descripcionReg[]" class="form-control" value="<?php echo htmlspecialchars($obra['descripcion'],ENT_QUOTES, 'UTF-8'); ?>"></td>
                                    <td><select name="origenReg[]" class="form-control">
                                        <option value=""></option>
                                        <?php
                                            $Nselect = $obra['origen'] == 'NACIONAL' ? 'selected' : '';
                                            $Iselect = $obra['origen'] == 'IMPORTACION' ? 'selected' : '';
                                        ?>
                                            <option <?php echo $Nselect ?> value="NACIONAL">NACIONAL</option>
                                            <option <?php echo $Iselect ?> value="IMPORTACION">IMPORTACIÓN</option>
                                        </select></td>

                                    <td><input type="text" name="precioReg[]" class="form-control" onchange="moneda(this);" value="<?php echo '$'.number_format($obra['costo'], 2, '.', ','); ?>"></td>
                                </tr>
                       <?php } ?>
                    
                     <tr ondblclick='addMObra(<?php echo $budget; ?>)' id="row-0" onclick="menu(this);">
                                 <?php if($budget) { ?>
                                        <td width=10px><button class="btn btn-sm btn-info" style="padding: 0px;" title="Agregar a materiales" data-toggle="tooltip" onclick='return add(this,<?php echo json_encode($data); ?>);' ><small class="fa fa-arrow-left" style="font-size:15px;"></small></button></td>
                                    <?php } ?>
                         <td><input type="text" name="descripcion[]" class="form-control"></td>
                         <td><select name="origen[]" class="form-control">
                                <option value=""></option>
                                 <option value="NACIONAL">NACIONAL</option>
                                 <option value="IMPORTACION">IMPORTACIÓN</option>
                             </select></td>
                         <td><input type="text" name="precio[]" class="form-control" onchange="moneda(this);"></td>
                     </tr>

                 </tbody>
             </table>

            </div>

         </div>
         <?php if($modifica){ ?>
         <div class="modal-footer">
             <button name="guardar"><span class="fa fa-save" style="margin-right:5px"></span> Guardar</button>
         </div>
         <?php } ?>
     </form>
 </div>

 <!-- SUB MENU -->
  <?php if($modifica){ ?>
 <div id="menu" class="context-menu" style="display:none">
        <ul>
            <li><a id="delete"><span class="fa fa-trash-o" style="font-size:14px"></span> ELIMINAR</a></li>
        </ul>
    </div>
<?php } ?>

 <?php
    include_once '../dependencias/php/footer.php';
    ?>
 <script type="text/javascript" src="../dependencias/js/Presupuesto/MObra.js?v=1.0.0"></script>

 <script>
$(document).ready(function(){
  $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("table tbody tr").hide().filter(function() {
      return $(this).find('input[type="text"]').val().toLowerCase().indexOf(value) > -1;
    }).show();
  });

  
});
</script>