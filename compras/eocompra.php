<?php  @session_start(); {$title = '- ALTA ORDEN COMPRA';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  include_once '../class/Direntrega.php'; //Dirección de entrega
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});

$idCompra= (int) base64_decode($_GET['edit']);
if(!filter_var($idCompra,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}
 
$popper = true;

 $obsuc = new Sucursal();
 $obunidad = new Unidad();
 $obcli = new Cliente();
 $oCompra = new Ocompra();

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }
//Consultamos la orden de compra
$resCompra=  $oCompra->GetData($idCompra);


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
  <center><div class="loader"><h3>Cargando Orden de Compra...</h3></div></center>
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:5px 15px;margin-left:0;margin-right:0;margin-bottom:0px;">
    
    <form id="form-ocompra">
    <div class="row">
            <!-- Inicio de la primer columna -->
            <input type="hidden" name="ocompra" value="<?php echo base64_encode($resCompra['pkocompra']); ?>">
            <div class="col-12 col-sm-4">
                 <div class="form-group row">
                            <label for="folio" class="txt-11 text-secondary col-12 col-sm-2">FOLIO:</label>
                            <div class="txt-12 col-8 col-sm-3" style="padding:0px;display:flex"><p id="displayFolio" style="color:red"><?php echo $resCompra['folio']; ?></p>
                               
                            </div>
                           
                            <label for="sucursal" class="txt-11 text-secondary col-12 col-sm-3">SUCURSAL</label>
                            <select  name="sucursal" id="sucursal"  class="form-control form-control-sm col-12 col-sm-4" >
                                <option selected value="<?php echo base64_encode(string: $resCompra['fksucursal']); ?>" ><?php echo $resCompra['nombre'] ?></option>
                            </select>
                    
                 </div>
                 <div class="form-group row">
                    <label for="fechaorden" class="txt-11 text-secondary col-12 col-sm-5">FECHA ORD:</label>
                    <input type="date" name="fechaorden" id="fechaorden" value="<?php echo $resCompra['fechaorden']; ?>" required class="form-control col-12 col-sm-7" >
                </div>
                <div class="form-group row">
                    <label for="fechaentrega" class="txt-11 text-secondary col-12 col-sm-5">FECHA ENTREGA:</label>
                    <input type="date" name="fechaentrega" id="fechaentrega" value="<?php echo $resCompra['fechaent']; ?>" class="form-control col-12 col-sm-7" >
                </div>
                <div class="form-group row">
                    <label for="nrequisicion" class="txt-11 text-secondary col-12 col-sm-5">NO. REQUISICIÓN:</label>
                    <select name="nrequisicion" id="nrequisicion" required  class="form-control col-12 col-sm-7 listRequisicion" >
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
                        <?php 
                            $selMN = $resCompra['moneda'] == 'M.N.' ? 'selected' : '';
                            $selUSD = $resCompra['moneda'] == 'USD' ? 'selected' : '';
                            $selEU = $resCompra['moneda'] == 'EUROS' ? 'selected' : '';
                        ?>
                        <option <?php echo $selMN; ?> value="M.N.">M.N.</option>
                        <option <?php echo $selUSD; ?> value="USD">USD</option>
                        <option <?php echo $selEU; ?> value="EUROS">EUROS</option>
                    </select>
                     
                </div>
                <div class="form-group row">
                    <label for="condpago" class="txt-11 text-secondary col-12 col-sm-5">COND. DE PAGO:</label>
                    <select name="condpago" class="form-control col-12 col-sm-7 listUsercustomer" id="condpago">
                        <?php $selCont = $resCompra['condpago'] == 'CONTADO' ? 'selected' : '';
                              $selCred = $resCompra['condpago'] == 'CREDITO' ? 'selected' : ''; ?>
                        <option <?php echo $selCont; ?> value="CONTADO">CONTADO</option>
                        <option <?php echo $selCred; ?> value="CREDITO">CREDITO</option>
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
                    <input type="text" name="rfc" id="rfc" value="<?php echo $resCompra['rfc']; ?>" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="direccion" class="txt-11 text-secondary col-12 col-sm-4">DIRECCIÓN:</label>
                    <textarea class="form-control col-12 col-sm-8" autocomplete="off" id="direccion" name="direccion" rows="2"><?php echo $resCompra['direccion']; ?></textarea>
                </div>
                <div class="form-group row" >
                    <label for="contacto" class="txt-11 text-secondary col-12 col-sm-4">CONTACTO:</label>
                    <input type="text" name="contacto" id="contacto" autocomplete="off" value="<?php echo $resCompra['contacto']; ?>" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="telefono" class="txt-11 text-secondary col-12 col-sm-4">TELEFONO:</label>
                    <input type="text" name="telefono" id="telefono" autocomplete="off" value="<?php echo $resCompra['telefono']; ?>" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="correo" class="txt-11 text-secondary col-12 col-sm-4">CORREO:</label>
                    <input type="text" name="correo" id="correo" autocomplete="off" value="<?php echo $resCompra['correo']; ?>" class="form-control col-12 col-sm-8 ">
                </div>
                <div class="form-group row" >
                    <label for="nproveedor" class="txt-11 text-secondary col-12 col-sm-4">NO. PROVEE:</label>
                    <input type="text" name="nproveedor" id="nproveedor" autocomplete="off" value="<?php echo $resCompra['nproveedor']; ?>" class="form-control col-12 col-sm-8 ">
                </div>
 
            </div>
            <!-- FIN DE LA SEGUDA COLUMNA
                INICIO DE LA TERCER COLUMNA -->
            <div class="col-12 col-sm-4">
                    <div class="form-group row" >
                            <label for="direntrega" class="txt-11 text-secondary col-12 col-sm-12">DIRECCIÓN DE ENTREGA:</label>
                            <select name="direntrega" class="form-control" required autocomplete="off" id="direntrega" style="height: 50px;text-align: justify;white-space:wrap;">  
                                <option></option>
                                <?php foreach ($direntrega as $key => $value) { 
                                        $selDir = $value == $resCompra['direntrega'] ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $value; ?>"  <?php echo $selDir; ?>><?php echo $value; ?></option>
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
                            <input type="text" name="telefono2" id="telefono2" value="<?php echo $resCompra['telefono2']; ?>" autocomplete="off" class="form-control col-12 col-sm-8 " >
                        </div>
                        <div class="form-group row">
                            <label for="email" class="txt-11 text-secondary col-12 col-sm-4">E-MAIL</label>
                            <input type="text" name="email" id="email" autocomplete="off" value="<?php echo $resCompra['email']; ?>" class="form-control col-12 col-sm-8 " >
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
                    <?php $contador = 0; foreach($oCompra->GetDataAllServ($resCompra['pkocompra']) as $value){ ?>
                          <tr style="max-height: 80px;" ondblclick='addServOcompra(<?php echo json_encode($data); ?>)' id="serv-<?php echo $contador; ?>">
                            <input type="hidden" name="pkserv[]" value="<?php echo base64_encode($value['pkservocompra']); ?>">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" value="<?php echo $value['pda']; ?>" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){
                                            $selUni = $unidad['pkunidad'] == $value['fkunidad'] ? 'selected':'';
                                        ?>
                                        <option <?php echo $selUni; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" value="<?php echo $value['cant']; ?>" onblur="Subtotal(this); totales();" data-pre="cantidad" class="form-control form-control-sm text-center"  autocomplete="off"></td>                            
                            <td valign="top"><input type="text" name="punit[]" data-pre='costo' value="$<?php echo number_format($value['preciounit'],2,'.',','); ?>" onblur="Subtotal(this); totales();" onchange="window.moneda(this);"  class="form-control form-control-sm text-center"  autocomplete="off"></td>
                            <td valign="top"><input type="text" name="importe[]" data-pre='subtotal' value="$<?php echo number_format($value['subtotal'],2,'.',','); ?>"  onchange="window.moneda(this);" class="form-control form-control-sm text-center subtotal"  autocomplete="off"></td>
                            <td valign="top"><textarea name="descripcion[]" id="descripcion-<?php echo $contador; ?>" class="form-control form-control-sm cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descripcion-<?php echo $contador; ?>')" onblur="ocultarScroll('descripcion-<?php echo $contador; ?>')"><?php echo $value['descripcion']; ?></textarea></td>
                        </tr>
                    <?php $contador++; } if($contador === 0){ ?>
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

            <div class="col-12 col-sm-4">
       
                   
                        
                            <label for="observaciones" class="txt-11 text-secondary ">OBSERVACIONES</label>
                            <textarea class="form-control  col-12 " id="observaciones" name="observaciones" style="margin-top:-10px" rows="4"><?php echo $resCompra['observaciones']; ?></textarea><br>
                           
                    
            </div>

            <div class="col-12 col-sm-4" style="padding:0px;">
                <div class="form-group row">
                        <label for="credito" class="txt-11 text-secondary col-12 col-sm-5">DIAS DE CREDITO:</label>
                        <input type="text" name="credito" id="credito" autocomplete="off" value="<?php echo $resCompra['diascredito']; ?>" class="form-control col-12 col-sm-7" >
                </div>
                <div class="form-group row">
                        <label for="solicita" class="txt-11 text-secondary col-12 col-sm-5">SOLICITA:</label>
                        <select name="solicita" class="form-control col-12 col-sm-7 listEmployee" id="solicita"> </select>  
                </div>
                <div class="form-group row">
                        <label for="autoriza" class="txt-11 text-secondary col-12 col-sm-5">AUTORIZA:</label>
                        <select name="autoriza" class="form-control col-12 col-sm-7 listEmployee" id="autoriza"> </select>
                </div>
                <div class="form-group row">
                        <label for="estado" class="txt-11 text-secondary col-12 col-sm-5">ESTADO:</label>
                        <select name="estado" class="form-control col-12 col-sm-7" id="estado">
                            <?php $selVig = $resCompra['estado']== 'VIGENTE' ? 'selected' : '';
                                  $selCan = $resCompra['estado']== 'CANCELADO' ? 'selected' : ''; ?>
                            <option <?php echo $selVig; ?> value="VIGENTE">VIGENTE</option>
                            <option <?php echo $selCan; ?> value="CANCELADO">CANCELADO</option>
                        </select>
                </div>
                <div class="form-group row" style="margin-left:15px">
        
                    <button type="button" class="btn btn-info btn-sm" style="border-radius:0px;height:5vh" name="imprimir" id="imprimir"><i class="fa fa-print fa-lg"></i>Imprimir</button>
                
                
                    <button type="button" class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar" id="guardar"><i class="fa fa-save fa-lg"></i>Guardar</button>
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
                        <input type="number" autocomplete="off" name="descto" step="0.01" id="descto" class="form-control form-control-sm col-12 col-sm-2 " style="height:20px;font-size:10px;padding:3px;" value="<?php echo $resCompra['descto']; ?>" onchange="totales();">
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
   
    <div id="menu" class="context-menu" style="display: none;">
        <ul>
            <li><a id="eliminar" ><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR REGISTRO</a></li>
        </ul>
    </div>
    
<?php
  include_once '../dependencias/php/footer.php';

  //Datos para pasar la info por jscript
                                    
?>
<script type="text/javascript" src="../dependencias/js/Compras/Ocompra.js"></script>
<script>
    $(async function () {
        $('[data-toggle="tooltip"]').tooltip();

        let sucursalSelect = document.getElementById('sucursal').value;
        await Sucursal(sucursalSelect);

        document.getElementById('proveedor').value = "<?php echo base64_encode($resCompra['fkproveedor']); ?>";
        document.getElementById('nrequisicion').value =  "<?php echo base64_encode($resCompra['fkrequisicion']); ?>";
        document.getElementById('listorden').value = "<?php echo base64_encode($resCompra['fkorden']); ?>";
        document.getElementById('comprador').value = "<?php echo base64_encode($resCompra['fkecomprador']); ?>";
        document.getElementById('solicita').value = "<?php echo base64_encode($resCompra['fkesolicita']); ?>";
        document.getElementById('autoriza').value = "<?php echo base64_encode($resCompra['fkeautoriza']); ?>";

        let area = document.querySelectorAll(".cajas-texto");
        area.forEach((elemento) => {
            elemento.style.height = Math.min(elemento.scrollHeight, 150) + "px";
          });
       
    });
    
    document.addEventListener("DOMContentLoaded", function() {
    // Código a ejecutar después de que se haya cargado el HTML de la página
    totales();
});
        


</script>

