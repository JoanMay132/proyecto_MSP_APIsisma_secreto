<?php @session_start(); {$title = '- ALTA ORDEN COMPRA';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  include_once '../class/Direntrega.php'; //Dirección de entrega
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
 
$popper = true;

 $obsuc = new Sucursal();
 $obunidad = new Unidad();
 $obcli = new Cliente();

 $idSuc = base64_decode($_GET['suc']);

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }

  //Obtenemos información del array de default
$index = null;
$nControl = 15;
$sucursal = $idSuc;
$resultado = array_filter($default, function($elemento) use ($nControl, $sucursal) {
    return $elemento['control'] == $nControl && $elemento['sucursal'] == $sucursal;
});
if(!empty($resultado)){
    $index = key($resultado);
}

//Permisos
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
    <main class="main users chart-page" id="skip-target" style="padding:5px 15px;margin-left:0;margin-right:0;margin-bottom:0px;">
    
    <form id="form-ocompra">
    <div class="row">
            <!-- Inicio de la primer columna -->
            
            <div class="col-12 col-sm-4">
                 <div class="form-group row">
                            <label for="folio" class="txt-11 text-secondary col-12 col-sm-2">FOLIO:</label>
                            <div class="txt-12 col-8 col-sm-3" style="padding:0px;display:flex"><p id="displayFolio" style="color:red">VACIO</p>
                                <button type="button" onclick="return setFolio();"  id="btnfolio" title="Asignar folio" data-toggle="tooltip" class="btn btn-success"  style="width:100%;height:15px;position:relative;padding:0;margin-left:5px"><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:13px" class="fa fa-check-circle fa-sm"></i></button>
                            </div>
                           
                            <label for="sucursal" class="txt-11 text-secondary col-12 col-sm-3">SUCURSAL</label>
                            <select onchange="return Sucursal(this.value);"  name="sucursal" id="sucursal"  class="form-control form-control-sm col-12 col-sm-4" >
                                <option value=""></option>
                                <?php foreach($obsuc->GetDataAll() as $sucursal){ 
                                        //if(in_array($sucursal['pksucursal'],$rol->getBranch())){
                                    ?>
                                <option value="<?php  echo base64_encode($sucursal['pksucursal']); ?>"><?php echo $sucursal['nombre']; ?></option>
                                <?php } /*} */?>
                            </select>
                    
                 </div>
                 <div class="form-group row">
                    <label for="fechaorden" class="txt-11 text-secondary col-12 col-sm-5">FECHA ORD:</label>
                    <input type="date" name="fechaorden" id="fechaorden" required class="form-control col-12 col-sm-7" >
                </div>
                <div class="form-group row">
                    <label for="fechaentrega" class="txt-11 text-secondary col-12 col-sm-5">FECHA ENTREGA:</label>
                    <input type="date" name="fechaentrega" id="fechaentrega" class="form-control col-12 col-sm-7" >
                </div>
                <div class="form-group row">
                    <label for="nrequisicion" class="txt-11 text-secondary col-12 col-sm-5">NO. REQUISICIÓN:</label>
                    <select name="nrequisicion" id="nrequisicion" required class="form-control col-12 col-sm-7 listRequisicion" >
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row">
                    <label for="orden" class="txt-11 text-secondary col-12 col-sm-5">ORD. DE TRABAJO:</label>
                    <select name="orden" id="listorden" class="form-control col-12 col-sm-7 ">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row">
                    <label for="moneda" class="txt-11 text-secondary col-12 col-sm-5">MONEDA:</label>
                    <select name="moneda" class="form-control col-8 col-sm-7 listUsercustomer"   id="moneda">
                        <option value="M.N.">M.N.</option>
                        <option value="USD">USD</option>
                        <option value="EUROS">EUROS</option>
                    </select>
                     
                </div>
                <div class="form-group row">
                    <label for="condpago" class="txt-11 text-secondary col-12 col-sm-5">COND. DE PAGO:</label>
                    <select name="condpago" class="form-control col-12 col-sm-7 listUsercustomer" id="condpago">
                        <option value="CONTADO">CONTADO</option>
                        <option value="CREDITO">CREDITO</option>
                    </select>
                </div>
                
 
            </div>  
            <!-- FIN DE LA PRIMER COLUMNA
                INICIO DE LA SEGUNDA COLUMNA -->
            <div class="col-12 col-sm-4">
                <div class="form-group row">
                    <label for="proveedor" class="txt-11 text-secondary col-12 col-sm-4">PROVEEDOR:</label>
                    <select name="proveedor" onchange="return Proveedor(this.value);" class="form-control col-8 col-sm-8 listProveedor" id="proveedor">  
                    </select>               
                </div>
                <div class="form-group row" >
                    <label for="rfc" class="txt-11 text-secondary col-12 col-sm-4">RFC:</label>
                    <input type="text" name="rfc" id="rfc" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="direccion" class="txt-11 text-secondary col-12 col-sm-4">DIRECCIÓN:</label>
                    <textarea class="form-control col-12 col-sm-8" autocomplete="off" id="direccion" name="direccion" rows="2"></textarea>
                </div>
                <div class="form-group row" >
                    <label for="contacto" class="txt-11 text-secondary col-12 col-sm-4">CONTACTO:</label>
                    <input type="text" name="contacto" id="contacto" autocomplete="off" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="telefono" class="txt-11 text-secondary col-12 col-sm-4">TELEFONO:</label>
                    <input type="text" name="telefono" id="telefono" autocomplete="off" class="form-control col-12 col-sm-8">
                </div>
                <div class="form-group row" >
                    <label for="correo" class="txt-11 text-secondary col-12 col-sm-4">CORREO:</label>
                    <input type="text" name="correo" id="correo" autocomplete="off" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="nproveedor" class="txt-11 text-secondary col-12 col-sm-4">NO. PROVEE:</label>
                    <input type="text" name="nproveedor" id="nproveedor" autocomplete="off" class="form-control col-12 col-sm-8 ">
                </div>
 
            </div>
            <!-- FIN DE LA SEGUDA COLUMNA
                INICIO DE LA TERCER COLUMNA -->
            <div class="col-12 col-sm-4">
                    <div class="form-group row" >
                            <label for="direntrega" class="txt-11 text-secondary col-12 col-sm-12">DIRECCIÓN DE ENTREGA:</label>
                            <select name="direntrega" class="form-control" required autocomplete="off" id="direntrega" style="height: 50px;text-align: justify;white-space:wrap;">  
                                <option></option>
                                <?php foreach ($direntrega as $key => $value) { ?>
                                    <option><?php echo $value; ?></option>
                              <?php  } ?>
                            </select>       
                    </div>
                    <div class="form-group row" >
                        <label for="comprador" class="txt-11 text-secondary col-12 col-sm-4">COMPRADOR:</label>
                                <select id="comprador" name="comprador" class="form-control form-control-sm listEmployee col-12 col-sm-8" >
                                    <option></option>
                                </select> 
                    </div>
                        <div class="form-group row">
                            <label for="telefono2" class="txt-11 text-secondary col-12 col-sm-4">TELEFONO</label>
                            <input type="text" name="telefono2" id="telefono2" autocomplete="off" class="form-control col-12 col-sm-8" value="<?php echo $default[$index]['telefono']; ?>">
                        </div>
                        <div class="form-group row">
                            <label for="email" class="txt-11 text-secondary col-12 col-sm-4">E-MAIL</label>
                            <input type="text" name="email" id="email" autocomplete="off" class="form-control col-12 col-sm-8" value="<?php echo $default[$index]['email']; ?>" >
                        </div>    
                       
                </div>
               
            <!-- FIN DE LA TERCERA COLUMNA -->
        </div> 


        <div class="row">
            <div class="col-12" style="padding:2px">
                <div style="height: 50vh;background:white;border:2px solid grey;overflow:auto" class="scroll">
                
                <div class="table-responsive scroll">
                <table class=" table-bordered table-ocompra" width="100%" id="table" style="background-color: white;font-size:11px; border-collapse:collapse;overflow-x: auto;">
                        <thead style="position: sticky;top:0;z-index: 10;" class="table-info" >
                          <tr>
                            <th width="4%">PDA.</th>
                            <th width="7%">UNIDAD</th>
                            <th width="5%">CANT.</th>
                            <th width="8%">P. UNIT </th>
                            <th width="8%">IMPORTE </th>
                            <th width="45%">DESCRIPCIÓN</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="serv-ocompra">
                        
                          <tr style="max-height: 80px;" ondblclick='addServOcompra(<?php echo json_encode($data); ?>)' id="serv-0">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" onblur="Subtotal(this); totales();" data-pre="cantidad" class="form-control form-control-sm text-center"  autocomplete="off"></td>                            
                            <td valign="top"><input type="text" name="punit[]" data-pre='costo' onblur="Subtotal(this); totales();" onchange="window.moneda(this);"  class="form-control form-control-sm text-center"  autocomplete="off"></td>
                            <td valign="top"><input type="text" name="importe[]" data-pre='subtotal'  onchange="window.moneda(this);" class="form-control form-control-sm text-center subtotal"  autocomplete="off"></td>
                            <td valign="top"><textarea name="descripcion[]" id="descNew-0" class="form-control form-control-sm cajas-texto" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>
                            
                            

                        </tr>
                            
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-sm-4">
       
                   
                        
                            <label for="observaciones" class="txt-11 text-secondary ">OBSERVACIONES</label>
                            <textarea class="form-control  col-12 " id="observaciones" name="observaciones" style="margin-top:-10px" rows="4">
* El proveedor deberá incluir los certificados de los materiales cuando aplique, de lo contrario no será recibido por almacén
*Si el proveedor no cumple con lo requerido y los tiempos de entrega acordados, M.S.P. se reserva el derecho de cancelar el pedido / servicio si así lo considera el usuario que lo solicita.
*El proveedor deberá verificar las especificaciones técnicas emitidas por control de calidad de M.S.P. cuando sea aplicable.

**********AGREGAR NUMERO DE OC EN LAFACTURA**********
                            </textarea><br>
                           
                    
            </div>

            <div class="col-12 col-sm-4" style="padding:0px;">
                <div class="form-group row">
                        <label for="credito" class="txt-11 text-secondary col-12 col-sm-5">DIAS DE CREDITO:</label>
                        <input type="text" name="credito" id="credito" autocomplete="off" class="form-control col-12 col-sm-7" >
                </div>
                <div class="form-group row">
                        <label for="solicita" class="txt-11 text-secondary col-12 col-sm-5">SOLICITA:</label>
                        <select name="solicita" class="form-control col-12 col-sm-7 listEmployee" id="solicita"> </select>  
                </div>
                <div class="form-group row">
                        <label for="autoriza" class="txt-11 text-secondary col-12 col-sm-5">AUTORIZA:</label>
                        <select name="autoriza" class="form-control col-12 col-sm-7 listEmployee" id="solicita"> </select>
                </div>
                <div class="form-group row">
                        <label for="estado" class="txt-11 text-secondary col-12 col-sm-5">ESTADO:</label>
                        <select name="estado" class="form-control col-12 col-sm-7" id="estado">
                            <option value="VIGENTE">VIGENTE</option>
                            <option value="CANCELADO">CANCELADO</option>
                        </select>
                </div>
                <div class="form-group row" style="margin-left:15px">
        
                    <button type="button"  class="btn btn-info btn-sm" style="border-radius:0px;height:5vh"  id="imprimir"><i class="fa fa-print fa-lg"></i>Imprimir</button>
                
                
                    <button type="button" class="btn btn-primary btn-sm" style="border-radius:0px;" id="guardar"><i class="fa fa-save fa-lg"></i>Guardar</button>
                </div> 
            </div>

            <div class="col-12 col-sm-4" >
                <div class="cont-border" >
                    <div class="row">
                            <div class="col-12 col-sm-5"><p onclick="totales()" class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">IMPORTE:</p></div>
                            <div class="col-12 col-sm-5 price text-right float-right" style="background:white;margin-left:20px"><p id="subtotal" style="margin-top:3px;" class="txt-11">$0.00</p></div>
                            <input type="hidden" name="inputSubtotal" id="inputSubtotal">
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-3"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">DESCTO:</p></div>
                        <input type="number" autocomplete="off" name="descto" step="0.01" id="descto" class="form-control form-control-sm col-12 col-sm-2 " style="height:20px;font-size:10px;padding:3px;" value="0.00" onchange="totales();">
                        <div class="col-12 col-sm-5 price text-right float-right" style="background:white;margin-left:20px"><p id="tagDescto" style="margin-top:3px;" class="txt-11">$0.00</p></div>
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">SUBTOTAL:</p></div>
                            <div class="col-12 col-sm-5 price text-right" style="background:white;margin-left:20px"><p id="subtotaldesc" style="margin-top:3px;" class="txt-11">$0.00</p></div>
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">IVA (16%):</p></div>
                            <div class="col-12 col-sm-5 price text-right" style="background:white;margin-left:20px"><p id="tagIva" style="margin-top:3px;" class="txt-11">$0.00</p></div>
                            <input type="hidden" name="iva" value="16" id="iva">
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">TOTAL:</p></div>
                            <div class="col-12 col-sm-5 price text-right" style="background:white;margin-left:20px"><p id="tagTotal" style="margin-top:3px;" class="txt-11">$0.00</p></div>
                            <input type="hidden" name="inputTotal" id="inputTotal">
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11 dolar" style="font-weight:bold;color:darkblue;display:none;">DOLARES:</p></div>
                            <div class="col-12 col-sm-5 price text-right dolar" style="background:white;margin-left:20px;display:none"><p id="tdolar" style="margin-top:3px;" class="txt-11">$0.00</p></div>
                    </div>
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
<script type="text/javascript" src="../dependencias/js/Compras/Ocompra.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    
    
    
                // document.getElementById('nrequisicion').addEventListener("change", async (event) => {
                //     const opcionSeleccionada = document.getElementById('nrequisicion').value;
                //     // Llama a la función directamente sin agregar otro listener.
                //     await dataRequisicion(opcionSeleccionada);
                // });
            
                const selectElement = document.getElementById('nrequisicion');

// Variable para rastrear la última opción seleccionada
let lastValue = selectElement.value;

// Manejar el evento 'change' para ejecutar la función
selectElement.addEventListener("change", async (event) => {
  const selectedValue = event.target.value;
  
  // Ejecutamos la función dataRequisicion solo si se ha seleccionado un valor válido
  if (selectedValue) {
    await dataRequisicion(selectedValue);
    lastValue = selectedValue; // Actualizamos lastValue solo si hay un valor válido
  }
});

// Manejar el evento 'mousedown' para permitir la selección repetida
selectElement.addEventListener("mousedown", (event) => {
  selectElement.value = ""; // Esto permite que se dispare el evento change
});



// // Manejar el clic en el documento para restablecer el valor si se hace clic fuera del select
 document.addEventListener("click", (event) => {
  if (event.target !== selectElement && !selectElement.value) {
    selectElement.value = lastValue; // Restauramos el valor anterior si no se selecciona nada
  }
});


</script>

