<?php @session_start(); {$title = '- ALTA ORDEN';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
 
$popper = true;

 $obsuc = new Sucursal();
 $obunidad = new Unidad();
 $obcli = new Cliente();

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }


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

<script>
    function popup (URL){ 
        window.open(URL,"","width=500,height=700,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
    } 
    function servicios (URL){ 
        window.open(URL,"","width=800,height=800;scrollbars=yes,left=900,addressbar=0,menubar=0,toolbar=0");
    } 
  </script>

  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:1px 15px;margin-left:0;margin-right:0;margin-bottom:0px;">
    
    <form id="form-requisicion">

        <div class="row" style="width: 100%;" >
            
                <div class="col-sm-5">
                    <label for="proyecto" class="txt-11 text-secondary">PROYECTO:</label>
                    <textarea class="form-control top-8" id="proyecto" name="proyecto" rows="4"></textarea>
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
                            <input type="date" required name="fecha" required id="fecha" class="form-control form-control-sm col-12 col-sm-9 " value="">
                        </div>
                        <div class="form-group row" >
                            <label for="estado" class="txt-11 text-secondary col-12 col-sm-3">ESTADO</label>
                            <select   name="estado" id="estado"  class="form-control form-control-sm col-12 col-sm-9" >
                                <option value="En proceso">En proceso</option>
                                <option value="Terminado">Terminado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6" >
                        <div class="form-group row" >
                            <label for="sucursal" class="txt-11 text-secondary col-12 col-sm-4">SUCURSAL</label>
                            <select onchange="return Sucursal(this.value);"  name="sucursal" id="sucursal"  class="form-control form-control-sm col-12 col-sm-8" >
                                <option value=""></option>
                                <?php foreach($obsuc->GetDataAll() as $sucursal){ 
                                        //if(in_array($sucursal['pksucursal'],$rol->getBranch())){
                                    ?>
                                <option value="<?php  echo base64_encode($sucursal['pksucursal']); ?>"><?php echo $sucursal['nombre']; ?></option>
                                <?php } /*}*/ ?>
                            </select>
                        </div>
                        
                        <div class="form-group row">
                            <label for="folio" class="txt-11 text-secondary col-4 col-sm-4">FOLIO</label>
                            <div class="txt-12 col-8 col-sm-8" style="padding:5px;display:flex"><p id="displayFolio" style="color:blue">SIN ASIGNAR</p>
                                <button type="button" onclick="return setFolio();"  id="btnfolio" title="Asignar folio" data-toggle="tooltip" class="btn btn-success"  style="width:100%;height:20px;position:relative;padding:0;margin-left:5px"><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;" class="fa fa-check-circle fa-sm"></i></button>
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
                                        <td align="left" valign="middle" width="40%"><input type="radio" name="clasificacion" checked class="form-check-label" value="general"></td>
                                        <td align="left" valign="middle" width="10%"><label class="txt-11 text-secondary">SGC</label></td>
                                        <td align="left" valign="middle" width="40%"><input type="radio" name="clasificacion" class="form-check-label" value="sgc"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    
                </div>
          

             <div class="col-12 col-sm-1" >
                <div class="form-group row" style="margin-left:15px">
      
                        <button type="button"  class="btn btn-info btn-sm" style="border-radius:0px;" name="imprimir" id="imprimir"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                    
                    
                     <button type="button" class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar" id="guardar"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
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
                        
                          <tr style="max-height: 80px;" ondblclick='addServrequisicion(<?php echo json_encode($data); ?>)' id="serv-0">
                            <td valign="top"><input name="pda[]" type="number" min="1.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>
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
                            <td valign="top"><textarea name="descripcion[]" id="descNew-0" class="form-control form-control-sm" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>
                            
                            

                        </tr>
                            
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-12 col-sm-7 form-group ">
                       <center> <label for="observaciones" class="txt-11 text-secondary">REQUERIMIENTOS ESPECIALES / OBSERVACIONES</label></center>
                       <textarea class="form-control top-8" id="observaciones" name="observaciones" rows="3">**********AGREGAR NUMERO DE OC EN LAFACTURA**********</textarea>
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
                                            <input type="text" name="lugarent" id="lugarent" autocomplete="off" class="form-control form-control-sm col-9 col-sm-8"> 
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
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
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

