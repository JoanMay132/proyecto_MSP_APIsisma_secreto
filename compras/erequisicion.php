<?php @session_start(); {$title = '- REQUISICION';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
$idReq= (int) base64_decode($_GET['edit']);
if(!filter_var($idReq,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}
$popper = true;

 $obsuc = new Sucursal();
 $obunidad = new Unidad();
 $obcli = new Cliente();
 $oReq = new Requisicion();
  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }

  //Consultamos la requisición
$resReq=  $oReq->GetData($idReq);

  //$rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::ot->value);
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
    td:focus-within {
        border: 1.7px solid #b9ddea ;
    }
    table .form-control{
        height: 30px;
    }


</style>

  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:1px 15px;margin-left:0;margin-right:0;margin-bottom:0px;">
    
    <form id="form-requisicion">
           <input type="hidden" name="requisicion" value="<?php echo base64_encode($resReq['pkrequisicion']); ?>"> 
        <div class="row" style="width: 100%;" >
            
                <div class="col-sm-5">
                    <label for="proyecto" class="txt-11 text-secondary">PROYECTO:</label>
                    <textarea class="form-control top-8" id="proyecto" name="proyecto" rows="4"><?php echo $resReq['proyecto']; ?></textarea>
                </div>
                <div class="col-12 col-sm-6"  >
                    <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row" >
                            <label for="orden" class="txt-11 text-secondary col-12 col-sm-6">O.T. DE TRABAJO</label>
                            <select name="orden" id="listorden" class="form-control col-12 col-sm-6">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group row" >
                            <label for="fecha" class="txt-11 text-secondary col-12 col-sm-3">FECHA</label>
                            <input type="date" required name="fecha" required id="fecha" class="form-control form-control-sm col-12 col-sm-9 " value="<?php echo $resReq['fecha']; ?>">
                        </div>
                        <div class="form-group row" >
                            <label for="estado" class="txt-11 text-secondary col-12 col-sm-3">ESTADO</label>
                            <select   name="estado" id="estado"  class="form-control form-control-sm col-12 col-sm-9" >
                                <?php $pro = $resReq['estado'] === 'En proceso' ? 'selected' : '';
                                      $ter = $resReq['estado'] === 'Terminado' ? 'selected' : '';
                                 ?>
                                <option <?php echo $pro; ?> value="En proceso">En proceso</option>
                                <option <?php echo $ter; ?> value="Terminado">Terminado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6" >
                        <div class="form-group row" >
                            <label for="sucursal" class="txt-11 text-secondary col-12 col-sm-4">SUCURSAL</label>
                            <select onchange="return Sucursal(this.value);"  name="sucursal" id="sucursal"  class="form-control form-control-sm col-12 col-sm-8" >
                                <option selected value="<?php echo base64_encode(string: $resReq['fksucursal']); ?>" ><?php echo $resReq['nombre'] ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group row">
                            <label for="folio" class="txt-11 text-secondary col-4 col-sm-4">FOLIO</label>
                            <div class="txt-12 col-8 col-sm-8" style="padding:5px;display:flex"><p id="displayFolio" style="color:red;font-weight:700"><?php echo $resReq['folio']; ?></p>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="form-group row justify-content-start" >
                            <label class="txt-11 text-secondary col-4 col-sm-4">CLASIFICACIÓN</label>
                            <div class="txt-12 col-8 col-sm-8" style="border: 1px solid grey">
                                <table width="100%">
                                    <tr>
                                        
                                        <td align="left" valign="middle" width="10%"><label class="txt-11 text-secondary">GENERAL</label></td>
                                        <td align="left" valign="middle" width="40%"><input type="radio" <?php echo $resReq['clasificacion'] == 'general' ? 'checked' : ''; ?>  name="clasificacion" class="form-check-label" value="general"></td>
                                        <td align="left" valign="middle" width="10%"><label class="txt-11 text-secondary">SGC</label></td>
                                        <td align="left" valign="middle" width="40%"><input type="radio" <?php echo $resReq['clasificacion'] == 'sgc' ? 'checked' : ''; ?>  name="clasificacion" class="form-check-label" value="sgc"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    
                </div>
          

             <div class="col-12 col-sm-1" >
                <div class="form-group row" style="margin-left:15px">
      
                        <button type="button"  class="btn btn-info btn-sm" style="border-radius:0px;" name="imprimir" id="imprimir"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                    
                    
                     <button  class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar" id="guardar" type="button"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
                </div>
                    

            </div>

        </div>

        <div class="row">
            <div class="col-12" style="padding:2px">
                <div style="height: 65vh;background:white;border:2px solid grey;overflow:auto" class="scroll">
                
                <div class="table-responsive scroll">
                <table class=" table-bordered table-requisicion" width="100%" id="table" style="background-color: white;font-size:11px; border-collapse:collapse;overflow-x: auto;">
                        <thead style="position: sticky;top:0;z-index: 10;" class="table-info" >
                          <tr>
                            <th width="4%">ITEM</th>
                            <th width="5%">CANT.</th>
                            <th width="7%">UNIDAD</th>
                            <th width="8%">NO. PARTE </th>
                            <th width="45%">DESCRIPCIÓN</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="serv-ot">
                        <?php $conteo = 0; foreach ($oReq->GetDataAllServ($resReq['pkrequisicion']) as $value) { ?>
                          <tr style="max-height: 80px;" ondblclick='addServrequisicion(<?php echo json_encode($data); ?>)'>
                            <input type="hidden" name="pkserv[]" value="<?php echo base64_encode($value['pkservrequisicion']); ?>">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center" value="<?php echo $value['pda']; ?>" autocomplete="off" inputmode="numeric"></td>
                            <td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" class="form-control form-control-sm text-center" value="<?php echo $value['cantidad']; ?>"  autocomplete="off"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ 
                                            $seleUni = $unidad['pkunidad'] == $value['unidad'] ? 'selected' : '';
                                        ?>
                                        <option <?php echo $seleUni; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><input type="text" name="nparte[]"  class="form-control form-control-sm text-center" value="<?php echo $value['nparte']; ?>"  autocomplete="off"></td>
                            <td valign="top"><textarea name="descripcion[]" id="descripcion-<?php echo $conteo; ?>" class="form-control form-control-sm cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descripcion-<?php echo $conteo; ?>')" onblur="ocultarScroll('descripcion-<?php echo $conteo; ?>')"><?php echo $value['descripcion']; ?></textarea></td>
                            
                            

                        </tr>
                        <?php $conteo++; } if($conteo == 0){ ?>
                            <tr style="max-height: 80px;" ondblclick='addServrequisicion(<?php echo json_encode($data); ?>)' id="serv-0">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>
                            <td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><input type="text" name="nparte[]"  class="form-control form-control-sm text-center"  autocomplete="off"></td>
                            <td valign="top"><textarea name="descripcion[]" id="descNew-0" class="form-control form-control-sm cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>
                            
                            

                        </tr>
                        <?php } ?>
                            
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-12 col-sm-7 form-group ">
                       <center> <label for="observaciones" class="txt-11 text-secondary">REQUERIMIENTOS ESPECIALES / OBSERVACIONES</label></center>
                       <textarea class="form-control top-8" id="observaciones" name="observaciones" rows="3"><?php echo $resReq['observaciones'] ?></textarea>
                </div>
                <div class="col-12 col-sm-5" >
                   
                                <div class="form-group row" >
                                    <label for="solicita" class="txt-11 text-secondary col-12 col-sm-4">SOLICITA:</label>
                                            <select id="solicita" name="solicita" class="form-control form-control-sm listEmployee col-12 col-sm-8" >
                                                <option></option>
                                            </select> 
                                </div>
                                <div class="form-group row" >
                                            <label for="recibe" class="txt-11 text-secondary col-12 col-sm-4">RECIBE:</label>
                                            <select id="recibe" name="recibe" class="form-control form-control-sm listEmployee col-12 col-sm-8" >
                                                <option></option>
                                            </select> 
                                </div>
                                <div class="form-group row" >
                                            <label for="autoriza" class="txt-11 text-secondary col-12 col-sm-4">AUTORIZA:</label>
                                            <select id="autoriza" name="autoriza" class="form-control form-control-sm listEmployee col-12 col-sm-8" >
                                                <option></option>
                                            </select> 
                                </div>
                                <div class="form-group row" >
                                            <label for="lugarent" class="txt-11 text-secondary col-12 col-sm-4">LUGAR DE ENTREGA:</label>
                                            <input type="text" name="lugarent" id="lugarent" autocomplete="off" class="form-control form-control-sm col-9 col-sm-8" value="<?php echo $resReq['lugarent']; ?>"> 
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
?>
<script type="text/javascript" src="../dependencias/js/Compras/Requisicion.js"></script>
<script>
    $(async function () {
        $('[data-toggle="tooltip"]').tooltip();

        let sucursalSelect = document.getElementById('sucursal').value;
        await Sucursal(sucursalSelect);
        document.getElementById('listorden').value = "<?php echo base64_encode( $resReq['fkorden']); ?>";
        document.getElementById('solicita').value = "<?php echo base64_encode( $resReq['fkesolicita']); ?>";
        document.getElementById('recibe').value = "<?php echo base64_encode( $resReq['fkerecibe']); ?>";
        document.getElementById('autoriza').value = "<?php echo base64_encode( $resReq['fkeautoriza']); ?>";


        let area = document.querySelectorAll(".cajas-texto");
        area.forEach((elemento) => {
            elemento.style.height = Math.min(elemento.scrollHeight, 150) + "px";
          });

    });

//   document.getElementById('cotizacion').addEventListener("click",(event)=>{
  
//   event.stopPropagation();
//   opcionSeleccionada = document.getElementById('cotizacion').value;
//   document.getElementById('cotizacion').addEventListener("click",(event)=>{
//     let sucursal = opcionSeleccionada;
//     dataCotizacion(sucursal);
//   });
  
// });
</script>

