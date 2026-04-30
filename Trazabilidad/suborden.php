<?php @session_start();  {$title = '- ALTA ORDEN';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
$idOrden= (int) base64_decode($_GET['ot']);
if(!filter_var($idOrden,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}
$popper = true;

$obsuc = new Sucursal();
$obunidad = new Unidad();
$obcli = new Cliente();
$oSuborden = new Suborden();

//Consultamos la orden
$resOt=  $oSuborden->GetData($idOrden);

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }
  $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");

  #region verificacion (Verifica si cuenta con los permisos)
  if(!$rol->getPermissionControl($_SESSION['controles'],Controls::ot->value,$resOt['fksucursal'])){
    echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
    return false;
}

$rol->getBranchInPermission($_SESSION['controles'],Controls::ot->value);

$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
#endregion


   ?>
<style>
    body::-webkit-scrollbar{
    width: 0px; /* Ancho de la barra de desplazamiento */
    height: 8px;
    }
    .form-control{
        border-radius: unset ;
        height:20px;
        font-size:11px;
        padding:2px;
    }
    .form-group.row,
    .form-group{
        padding: 0px !important;
        margin-top: unset !important;
        margin-bottom: -3px !important;

        /* border:1px solid black; */
    }

   .row{
        padding: unset !important;
        margin-top: unset !important;
        margin-bottom: unset !important;
    }
    .top-8{
        margin-top: -8px;
    }
    .top-3{
        margin-top: -3px;
    }
    input[type="radio"]{
        transform: scale(0.6) !important;
    }
    body{
        background:#D6EAF8   !important;
    }
    
    td:focus-within {
        border: 1.7px solid #b9ddea ;
    }


</style>

  <div class="main-wrapper">
  <center><div class="loader"><h3>Cargando OT...</h3></div></center>
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">
    
    <form id="form-suborden">
        <input type="hidden" name="orden" value="<?php echo base64_encode($resOt['fkorden']);?>">
        <input type="hidden" name="suborden" value="<?php echo base64_encode($resOt['pksuborden']);?>">
        <div class="row">
            <div class="col-12 col-sm-5" >
                <div class="form-group row" >
                    <label for="listClientes" class="txt-11 text-secondary col-3 col-sm-3">CLIENTE:</label>
                    <select name="cliente" required id="listClientes" class="form-control txt-12 col-9 col-sm-9" onchange="return usercustomer(this.value);" >
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row">
                    <label for="listUsercustomer" class="txt-11 text-secondary col-3 col-sm-3">SOLICITO:</label>
                    <select name="solicito" id="listUsercustomer" onchange="return viewDepto(this.value);" class="form-control form-control-sm col-8 col-sm-8 listUsercustomer">
                        <option></option>
                        
                    </select>
                    <div class="col-1 col-sm-1 text-center" style="padding:0">
                        <button type="button"  id="buttonUser" title="Agregar usuario" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px" class="fa fa-user-plus"></i></button>
                    </div>
                    
                </div>
                <div class="form-group row" >
                    <label for="deptouser" class="txt-11 text-secondary col-3 col-sm-3">DEPARTAMENTO:</label>
                    <input type="text" name="depto" id="deptouser" class="form-control form-control-sm col-9 col-sm-9" value="<?php echo $resOt['depto']; ?>">
                </div>
            </div>
            <div class="col-12 col-sm-4" >
                <div class="form-group row" >
                    <label for="sucursal" class="txt-11 text-secondary col-3 col-sm-3">SUCURSAL:</label>
                    <select onchange="return Sucursal(this.value);" name="sucursal" id="sucursal"  class="form-control form-control-sm col-9 col-sm-9" >
                         <option selected value="<?php echo base64_encode($resOt['fksucursal']); ?>" ><?php echo $resOt['nombre'] ?></option>
                    </select>
                </div>
                <div class="form-group row" >
                    <label for="fecha" class="txt-11 text-secondary col-3 col-sm-3">FECHA:</label>
                    <input type="date" name="fecha" id="fecha" required class="form-control form-control-sm col-9 col-sm-9 " value="<?php echo $resOt['fecha']; ?>">
                </div>
                <div class="form-group row">
                    <label for="folio" class="txt-11 text-secondary col-3 col-sm-3">FOLIO:</label>
                    <div class="txt-12 col-4 col-sm-4" style="padding:5px;display:flex;"><p id="displayFolio" style="color:blue;border: 1px solid grey;padding:2px;font-weight:bold"><?php echo $resOt['folio']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3" >
                <div class="form-group row" style="margin-left:15px">
                    <?php if($modifica){ ?>
                        <button type="submit"  class="btn btn-info btn-sm" style="border-radius:0px;" id="printSuborden"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                     <button type="submit" class="btn btn-primary btn-sm" style="border-radius:0px;" id="guardarSuborden"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
                     <?php }else{ ?>
                        <button type="button" onclick="return Print('<?php echo base64_encode($resOt['pksuborden']); ?>','suborden','print_suborden');" class="btn btn-info btn-sm" style="border-radius:0px;" ><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                    <?php }?>
                </div>
                    

            </div>

        </div>

        <div class="row">
            <div class="col-12" style="padding:2px">
                <div style="height: 60vh;background:white;border:2px solid grey;overflow:auto" class="scroll table-responsive">
                <table class=" table-bordered table-ot" width="100%" id="table" style="background-color: white;font-size:11px; border-collapse:collapse;overflow-x: auto;">
                        <thead style="position: sticky;top:0;z-index: 10;" class="table-info" >
                          <tr>
                            <th width="5%">PDA</th>
                            <th width="5%">CANT.</th>
                            <th width="6%">UNIDAD</th>
                            <th width="45%">DESCRIPCIÓN</th>
                            <th width="15%">TIPO TRABAJO</th>
                            <th width="9%">DIBUJO</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="serv-ot">
                            <?php $cont= 0; foreach($oSuborden->GetDataAllServ($resOt['pksuborden']) as $rowServ){  ?>
                                
                          <tr style="max-height: 80px;" ondblclick='addServot(<?php echo json_encode($data); ?>,<?php echo json_encode($tipo); ?>)'>
                            <input type="hidden" name="pkserv[]" value="<?php echo base64_encode($rowServ['pksubservorden']); ?>">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric" value="<?php echo $rowServ['pda'] ?>"></td>
                            <td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" value="<?php echo $rowServ['cantidad'] ?>"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm text-center">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){
                                            $selec = $rowServ['unidad'] == $unidad['pkunidad'] ? 'selected' : '';
                                        ?>
                                        <option <?php echo $selec; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><textarea name="descripcion[]" id="descripcion-<?php echo $cont ?>" class="form-control form-control-sm cajas-texto scrollHidden" autocomplete="off" style="resize:none;min-height:auto"  onclick="menu(this,'true'); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descripcion-<?php echo $cont ?>')" onblur="ocultarScroll('descripcion-<?php echo $cont ?>')"><?php echo $rowServ['descripcion'] ?></textarea></td>
                            <td valign="top" >
                                <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                                        <option value=""></option>
                                        <?php   
                                                foreach ($tipo as $value) {
                                                    $selTipo = $rowServ['tipotrabajo'] == $value ? 'selected' : '';
                                                    echo "<option ".$selTipo." value='".$value."'>".$value."</option>";
                                                }
                                        ?>
                                   
                                </select>
                            </td>
                            <td valign="top"><textarea id="" name="dibujo[]" class="form-control" autocomplete="off" oninput="autoResize(this);" spellcheck="false" style="resize:none;height:auto;"><?php echo $rowServ['dibujo'] ?></textarea></td>

                        </tr>
                        <?php $cont++; }
                            if($cont == 0){ ?>
                            <tr style="max-height: 80px;" ondblclick='addServot(<?php echo json_encode($data); ?>,<?php echo json_encode($tipo); ?>)'>
                                <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric" ></td>
                                <td valign="top"><input type="number" name="cant[]" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" ></td>
                                <td valign="top">
                                    <select name="unidad[]" class="form-control form-control-sm text-center">
                                        <option value=""></option>
                                        <?php foreach($data as $unidad){ ?>
                                            <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td valign="top"><textarea name="descripcion[]" id="descripcion-<?php echo $cont ?>" class="form-control form-control-sm cajas-texto scrollHidden" autocomplete="off" style="resize:none;min-height:auto"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descripcion-<?php echo $cont ?>')" onblur="ocultarScroll('descripcion-<?php echo $cont ?>')"></textarea></td>
                                <td valign="top" >
                                    <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                                            <option value=""></option>
                                            <?php   
                                                    foreach ($tipo as $value) {
                                                        echo "<option value='".$value."'>".$value."</option>";
                                                    }
                                            ?>
                                       
                                    </select>
                                </td>
                                <td valign="top"><textarea id="" name="dibujo[]" class="form-control" autocomplete="off" oninput="autoResize(this);" spellcheck="false" style="resize:none;height:auto;"></textarea></td>
    
                            </tr>                           
                        

                        <?php } ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">

                <div class=" offset-1  col-8 col-sm-6 form-group ">
                       <center> <label for="observaciones" class="txt-11 text-secondary">REQUERIMIENTOS ESPECIALES / OBSERVACIONES</label></center>
                       <textarea class="form-control top-8 scrollHidden" id="observaciones" name="observaciones" rows="3"><?php echo $resOt['observaciones'] ?></textarea>
                       <div class="row " >
                            <div class="col-12 col-sm-6 form-group" >
                                <div class="text-center" >
                                        <label for="auxiliar" class="txt-11 text-secondary">AUXILIAR DE PRODUCCIÓN</label>
                                        <select id="auxiliar" name="auxiliar" class="form-control form-control-sm listEmployee top-8" >
                                            <option></option>
                                        </select> 
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 form-group" >
                                <div class="text-center" >
                                        <label for="enterado" class="txt-11 text-secondary">ENTERADO</label>
                                        <select id="enterado" name="enterado"  class="form-control form-control-sm listEmployee top-8">
                                            <option></option>
                                        </select> 
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4" >
                    <div class="row form-group">
                            <label for="diaentrega" class="txt-11 text-secondary col-5">DIAS PROGRAMADOS DE ENTREGA</label>
                            <input type="number" id="diaentrega" name="diaentrega" class="form-control form-control-sm col-3 text-center" value="<?php echo $resOt['diaentrega']; ?>">
                    </div><br>
                    <div class="row form-group" style="border:1px solid grey;margin-left:5px;margin-right:5px;">
                                               <?php $urgente = $resOt['tipo'] === 'urgente' ? 'checked' : '';
                                                    $normal = $resOt['tipo'] === 'normal' ? 'checked' : ''; ?> 
                                     <label for="urgente" class="txt-11 text-secondary col-2">URGENTE</label>
                                    <input type="radio" name="tipo" id="urgente" value="urgente" class="form-control form-control-sm col-3 top-3" <?php echo $urgente; ?> >
                                    <label class="txt-11 text-secondary col-2" for="normal">NORMAL</label>
                                    <input type="radio" name="tipo" value="normal" id="normal" class="form-control form-control-sm col-3 top-3" <?php echo $normal; ?> >
                                
                      
                    </div>
                    
                </div>
        </div>

        
        </form>
    </main>
      
    <div id="menu" class="context-menu">
        <ul>
            <li><a id="eliminar" ><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR REGISTRO</a></li>
        </ul>
    </div> 
    
<?php
  include_once '../dependencias/php/footer.php';


  $dato = array(
    "cotizacion" =>base64_encode($resOt["fkcotizacion"]),
    "cliente" => base64_encode($resOt["fkcliente"])
  );
?>
<script type="text/javascript" src="../dependencias/js/Trazabilidad/Orden.js"></script>
<script>
    $(async function () {
        $('[data-toggle="tooltip"]').tooltip();

        //Carga la sucursal
        let sucursalSelect = document.getElementById('sucursal').value;
        await Sucursal(sucursalSelect,<?php echo json_encode($dato) ?>);
        await usercustomer("<?php echo base64_encode($resOt['fkcliente'])?>");

        let solicito =document.getElementById('listUsercustomer');
        solicito.value = "<?php echo base64_encode($resOt['fkusercli']); ?>";

        let auxiliar = document.getElementById('auxiliar');
        auxiliar.value = "<?php echo base64_encode($resOt['fkeproduccion']); ?>";

        let enterado = document.getElementById('enterado');
        enterado.value = "<?php echo base64_encode($resOt['fkeenterado']); ?>";

        let area = document.querySelectorAll(".cajas-texto");
        area.forEach((elemento) => {
            elemento.style.height = Math.min(elemento.scrollHeight, 150) + "px";
          })

        
    });

    
    window.addEventListener('beforeunload', function(event) {
       
       let ventana =  window.opener;

       if(ventana.location.href.includes("eorden")){
           ventana.$('#displaySuborden').load(ventana.location.href + ' .suborden');
       }
       event.returnValue = ''; 
});

</script>

