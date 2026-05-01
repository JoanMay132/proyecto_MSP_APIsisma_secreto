<?php @session_start(); $title = '- ANALISIS DE COSTOS';
  include '../dependencias/php/head.php';
 
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
$obunidad = new Unidad();
$oSuc  = new Sucursal();
$oForm  = new Formulas();
foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" => $value['pkunidad'], "nombre" => $value['nombre']);
}
$popper = true;
$sucursal = (int) base64_decode($_SESSION['sucursal']) ; //De prueba

//Verificamos que se este cargando un servicio de la cotizacion
if(isset($_GET['suc'])){
    $cargaSuc = $_GET['suc'];
}
$rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::analisiscosto->value);
   ?>

<style>
    table.table-presupuesto tr td .input-form{
        font-size: 13px !important;
    }
</style>


  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:5px;margin-right:0;margin-bottom:0px">
    <form id="form-presupuesto" onsubmit="return addPredefinido();">
        <div class="row">
            <!-- PRIMERA COLUMNA -->
            <input type="hidden" name="servicio" id="servicio">
            <div class="col-12 col-sm-8" >
                <p class="h6 text-info text-center"><strong>ANALISIS DE COSTOS UNITARIOS (PREDEFINIDOS) </strong></p><br>
                <!-- <div class="row" >
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11 " ><strong>CLIENTE:</strong> </label>
                    </div>
                    <div class="col-11" style="padding:unset;">
                        <select class="form-control form-control-sm input-form" name="cliente" id="listClientes" required></select>
                    </div>
                </div> -->
                <div class="row" style="padding: unset; margin-top:-10px">
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11"><strong>SERVICIO:</strong> </label>
                    </div>
                    <div class="col-8" style="padding:unset;">
                        <textarea  class="form-control form-control-sm input-form" required name="inputServicio" id="inputServicio" required></textarea>   
                    </div>
                    <!-- <div class="col-1" style="padding:unset;">
                        <label class="txt-11 "><strong>FECHA:</strong> </label>
                    </div>
                    <div class="col-2" style="padding:unset;">
                        <input type="date" class="form-control form-control-sm input-form" id="fecha" name="fecha" required>  
                    </div> -->
                </div><br>
                <!-- <div class="row" style="margin-top:1px"">
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11" ><strong>SOLICITA:</strong> </label>
                    </div>
                    <div class="col-8" style="padding:unset;">
                        <input type="text" class="form-control form-control-sm input-form" name="solicita" id="solicita">
                    </div>

                    <div class="offset-1 col-2" style="padding:unset;">
                        <a href="javascript:Presupuesto('cargapresupuesto?suc=<?php echo $cargaSuc; ?>','cargapresupuesto')" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i>Carga presupuestos</a>  
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-sm-12" style="padding:unset;">
                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" ondblclick="OpenWindows('materiales?suc=<?php echo $cargaSuc; ?>&budget=true','MATERIALES');">
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b">A) MATERIALES</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody >
                                <tr class="text-right" id="row-importeMaterial">
                                    <td colspan="5" ><strong>IMPORTE DE MATERIAL</strong></td>
                                    <td><strong><p class="border-dark input-form importeTotal" id="importeTotalMaterial">$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" ondblclick="OpenWindows('mobra?suc=<?php echo $cargaSuc; ?>&budget=true','MANO DE OBRA');"  >
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b" >B) MANO DE OBRA</th>
                                <th width="10%"  id="border-dark-b" >CANTIDAD</th>
                                <th width="10%" id="border-dark-b" >JORNADA</th>
                                <th width="10%" id="border-dark-b" >COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b" >IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr id="row-totalHora">
                                    
                                    <td class="text-right" colspan="2" ><strong>CANT. HORA</strong></td>
                                    <td>
                                        <input type="number" readonly class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora"  min="0" value="0.0" step="0.1" style="height: 20px;">
                                    </td>
                                    <td colspan="2" class="text-right"><strong>SUMA:</strong></td>
                                    <td class="text-center"><p id="sumaTotal" class="no-bg text-center border-dark input-form">0.00</p></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="2"><strong>TIEMPO EXTRAORDINARIO</strong></td>
                                    <td><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="tExtra" min="0" value="0.0" step="0.1" style="height: 20px;"  onchange="tiempoExtra(); ImporteHtaEq();" name="textra"></td>
                                    <td>
                                        <select class="form-control form-control-sm no-bg text-center border-dark input-form" name="utiempo" style="height:20px;padding:0px">
                                            <option value="HORA">HORA</option>
                                            <option value="PZA">PZA</option>
                                            <option value="SERV">SERV</option>
                                            <option value="ACTIVIDAD">ACTIVIDAD</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm no-bg text-center border-dark input-form" id="costoExtra" min="0" value="$210.00" onchange="moneda(this); tiempoExtra(); ImporteHtaEq();" step="0.1" style="height: 20px;" name="costoextra"></td>
                                    <td><p class="no-bg text-center border-dark input-form" id="totalExtra">$0.00</p></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="5" ><strong>IMPORTE MANO DE OBRA</strong></td>
                                    <td><strong><p class="no-bg text-center border-dark input-form importeTotal" id="importeTotalObra">$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table txt-12 table-sm table-success table-striped table-presupuesto">
                            <thead class="text-center">
                                <th width="80%" id="border-dark-b">B1) HERRAMIENTA MENOR %(B)</th>
                                <th width="10%" id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center border-dark input-form porcentaje" id="inputHtamenor" name="htamenor" min="0" value="3%" step="0.1" style="height: 20px;" onchange="ImporteHtaEq();"></th>
                                <th width="10%" id="border-dark-b"><p class="input-form importeTotal" id="totalHtamenor">$0.00</p></th>
                            </thead>
                            <thead class="text-center">
                                <th width="80%" id="border-dark-b" >B2) EQUIPO DE SEGURIDAD PERSONAL %(B)</th>
                                <th width="10%" id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center border-dark input-form porcentaje" id="inputEquipo" min="0" value="5%" step="0.1" style="height: 20px;" onchange="ImporteHtaEq();" name="segpersonal"></th>
                                <th width="10%" id="border-dark-b"><p class="input-form importeTotal" id="totalEquipo">$0.00</p></th>
                            </thead>
                        </table>

                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" ondblclick="OpenWindows('maquinaria?suc=<?php echo $cargaSuc; ?>&budget=true','MAQUINARIA');" >
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b">C) MAQUINARIA Y EQUIPO</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr class="text-right" id="row-importeMaquin">
                                    <td colspan="5" ><strong>IMPORTE DE MAQUINARIA</strong></td>
                                    <td><strong><p id="importeTotalMaquin" class="border-dark input-form importeTotal">$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table   class="table txt-12 table-sm table-success table-striped table-presupuesto">
                            <thead class="text-center" ondblclick="OpenWindows('adicionales?suc=<?php echo $cargaSuc; ?>&budget=true','MAQUINARIA');">
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b">D) SERVICIOS ADICIONALES</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr class="text-right" id="row-importeAdicional">
                                    <td colspan="5" ><strong>IMPORTE SERV. ADICIONALES</strong></td>
                                    <td><strong><p class="border-dark input-form importeTotal" id="importeTotalAdicional">$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- SECCIÓN DE TOTALES -->
                        <div class="row" style="padding:0px">
                            <div class="col-5">
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%" class="border-dark"><strong>Contenido Nacional</strong></td>
                                        <td class="border-dark"><input type="text" readonly id="cnacional" class="form-control form-control-sm text-center input-form" value="$0.00" style="margin:auto;padding:0px;height:auto;font-size:12px" name="cnacional"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-dark"><strong>PCNS</strong></td>
                                        <td class="border-dark" style="background:white"><p id="pcnc" >0.00</p></td>
                                    </tr>
                                </table>
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>CANTIDAD DE PIEZAS (MUL)</strong></td>
                                        <td width="23%" class="border-dark" ><input type="number" onchange="Operaciones();" pattern="[0-9]*" class="form-control form-control-sm text-center " id="iMul"  value="0"  style="margin:auto;padding:0px;height:auto" name="canmult"></td>
                                    </tr>
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>COSTO POR PIEZA</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center" id="cpieza" readonly value="$0.00"  style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>

                                </table>
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>CANTIDAD DE PIEZAS (DIV)</strong></td>
                                        <td width="23%" class="border-dark" ><input type="number" onchange="Operaciones();" class="form-control form-control-sm text-center " id="iDiv"  value="0" style="margin:auto;padding:0px;height:auto" name="cantdiv"></td>
                                    </tr>
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>COSTO UNITARIO</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center "   value="$0.00" readonly id="cdiv" style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-7">
                                <table class="table txt-12 table-sm table-primary table-striped text-right table-presupuesto">
                                    <thead >
                                        <th colspan="2" id="border-dark-b">COSTOS DIRECTOS (A+B+B1+B2+C+D):</th>
                                        <th id="border-dark-b" width="20%"><strong><p id="cDirectos">$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" style="padding:unset">INDIRECTOS (COSTOS ADMINISTRATIVOS / GENERALES):</th>
                                        <th id="border-dark-b" ><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" name="indirectos" value="30%" id="iindirectos" onchange="ImporteTotal();"  style="width: 30px;padding:0px;margin:auto" ></th>
                                        <th colspan="2" class="border-dark-b"><p id="cindirectos">$0.00</p></th>
                                    </thead>
                                    <thead  >
                                        <th colspan="2" id="border-dark-b" ><strong>SUMA:</strong></th>
                                        <th id="border-dark-b" width="20%" ><strong><p id="suma">$0.00</p></strong></th>
                                    </thead>
                                    <thead >
                                        <th id="border-dark-b" >FINANCIAMIENTO:</th>
                                        <th id="border-dark-b" ><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="ifinanciamiento"  value="5%" onchange="ImporteTotal();" style="width: 30px;padding:0px;margin:auto" name="financiamiento"></th>
                                        <th colspan="2" id="border-dark-b" ><p id="financiamiento">$0.00</p></th>
                                    </thead>
                                    <thead >
                                        <th colspan="2" id="border-dark-b" ><strong>SUBTOTAL:</strong> </th>
                                        <th id="border-dark-b" width="20%"  ><strong><p id="subtotal">$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" >(*) UTILIDAD BRUTA:</th>
                                        <th id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="iutilidad" onchange="ImporteTotal();" name="utilidad"  value="15%" id="" style="width: 30px;padding:0px;margin:auto"></th>
                                        <th colspan="2"  id="border-dark-b" ><p id="utilidad">$0.00</p></th>
                                    </thead>
                                </table>

                                <table   class="table txt-12 table-sm table-primary table-striped table-presupuesto">
                                    <thead class="text-center" >
                                        <th width="80%" id="border-dark-b" ><h4>TOTAL PRECIO UNITARIO</h4></th>
                                        <th width="20%" id="border-dark-b" ><h4 id="totalAll">$0.00</h4></th>
                                        <input type="hidden" name="totalUnitario" id="totalUnitario">
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div style="width:auto;height:auto;padding: 3px;" class="table-primary txt-12"><strong>DESCRIPCIÓN DETALLADA DEL SERVICIO</strong></div>
                                <textarea class="form-control form-control-sm input-form txt-12" rows="3" name="descripcion" ></textarea>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- SEGUNDA COLUMNA -->
            <div class="col-12 col-sm-4">
                <div class="row" style="padding:5px">
                    <table style="width:100%;"  >
                        <!-- <tr>
                            <td style="width:20%"><p class="txt-12 "><strong>FOLIO:</strong></p>
                            <input type="hidden" name="folio" value="0" id="inputFolio">
                        </td>
                            <td ><p class="txt-12 border-dark text-center" style="width: 120px; background:white;padding:3px" id="displayFolio">0</p></td>
                            <td ><p class="txt-12 "><strong>PARTIDA:</strong></p></td>
                            <td><input type="text" name="pda" readonly class="form-control-sm input-form text-center" style="width: 100px;background:white;" id="pda" ></td>
                        </tr> -->
                        <tr>
                            <td ><p class="txt-12 "><strong>SUCURSAL:</strong></p></td>
                            <td><select class="form-control-sm input-form" style="width: 120px" name="sucursal" id="sucursal" required>
                                <option value=""></option>
                                <?php foreach ($oSuc->GetDataAll() as $value) {
                                    if(in_array($value['pksucursal'],$rol->getBranch())){
                                   $sele = $value['pksucursal'] == base64_decode($cargaSuc) ? 'selected' : '';
                                    echo '<option '.$sele.' value="'.base64_encode($value["pksucursal"]).'" >'.$value["nombre"].'</option>';
                                } } ?>
                                </select>
                            </td>
                            <!-- <td><p class="txt-12 "><strong>PRE:</strong></p></td>
                            <td><select class="form-control-sm input-form" style="width: 100px;" >
                                <option></option>
                            </select></td> -->
                        </tr>
                        <tr>
                            <td ><p class="txt-12 "><strong>COTIZACION:</strong></p></td>
                            <td><select class=" form-control-sm input-form" style="width: 120px" id="listCotizacion" name="cotizacion" onchange="dataCotizacion2(this.value);">
                               
                            </select></td>
                            
                        </tr>
                    </table>
                </div>
                <div class="row" style="padding:10px">
                    <table class="table-presupuesto table-striped table-bordered">
                    <thead class="txt-12 text-center table-primary">
                            <th colspan="4">PESO DE MATERIALES REDONDOS</th>
                        </thead>
                        <thead class="txt-12 text-center table-primary">
                            <th>O.D</th>
                            <th>I.D</th>
                            <th>LONG.</th>
                            <th>PESO</th>
                        </thead>
                        <tbody>
                        <?php foreach ($oForm->getFPMaterial($sucursal) as  $value) { ?>
                            <tr>
                                <input type="hidden" name="pkpesomat[]" value="<?php echo base64_encode($value['pkpesomat']); ?>">
                                <td><input type="number" name="od[]" onchange="formulaPMaterial(this)" value="<?php echo $value['od']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                <td><input type="number" name="idmat[]" onchange="formulaPMaterial(this)" value="<?php echo $value['idmat']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                <td><input type="number" name="longmaterial[]" onchange="formulaPMaterial(this)" value="<?php echo $value['longmaterial']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                <td><input type="number" name="pmaterial[]" onchange="formulaPMaterial(this)" value="<?php echo $value['pmaterial']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table><br>

                    <table class="table-presupuesto table-striped table-bordered">
                    <thead class="txt-12 text-center table-primary">
                            <th colspan="5">PESO DE PLACAS AC, ACERO AL CARBON / INOXIDABLE</th>
                        </thead>
                        <thead class="txt-12 text-center table-primary">
                            <th>ANCHO</th>
                            <th>LONG.</th>
                            <th>PLACA DE</th>
                            <th>KG. X M2</th>
                            <th>PESO</th>
                        </thead>
                        <tbody>
                            <?php foreach ($oForm->getFormulaPeso($sucursal) as  $value) { ?>

                                <tr>
                                    <input type="hidden" name="pkpeso[]" value="<?php echo base64_encode($value['pkpeso']); ?>">
                                    <td><input type="number" name="ancho[]" onchange="formulaPeso(this);" value="<?php echo $value['ancho']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                    <td><input type="number" name="long[]" onchange="formulaPeso(this);" value="<?php echo $value['longitud']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                    <td><input type="text" name="placa[]" onchange="formulaPeso(this);" value="<?php echo htmlentities($value['placa'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm input-form text-center" ></td>
                                    <td><input type="number" name="kilom2[]" onchange="formulaPeso(this);" value="<?php echo $value['kilom2']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                    <td><input type="number" name="peso[]" onchange="formulaPeso(this);" value="<?php echo $value['peso']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table><br>

                    <table class="table-presupuesto table-striped table-bordered">
                        <thead class="txt-12 text-center table-primary">
                            <th colspan="3">FORMULA PARA CALCULAR M2 DE LAS PLACAS</th>
                        </thead>
                        <thead class="txt-12 text-center table-primary">
                            <th>ESPESOR</th>
                            <th>FACTOR DENSIDAD </th>
                            <th>KILOS X M2</th>
                        </thead>
                        <tbody>
                           <?php foreach ($oForm->getFormulaM2($sucursal) as $value) { ?>
                            <tr>
                                <input type="hidden" name="pkformulam2" value="<?php echo base64_encode($value['pkformulam2']); ?>">
                                <td><input type="number" name="espesor" onchange="formulaM2();" value="<?php echo $value['espesor']; ?>" id="F-espesor" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" name="factor" onchange="formulaM2();" value="<?php echo $value['factor']; ?>" id="F-factor" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" name="km2" id="F-km" value="<?php echo $value['km2']; ?>" class="form-control form-control-sm input-form text-center"></td>
                            </tr>
                          <?php } ?>
                           
                           
                        </tbody>
                    </table>
                </div><hr>
                <div class="row">
                    <div class="col-5"><button type="button" class="btn btn-outline-info btn-sm" id="btnPrint"><span class="fa fa-print" style="font-size:18px"></span><br>IMPRIMIR Y GUARDAR</button></div>
                    <div class="col-5"><button class="btn btn-outline-info btn-sm" id="btnSave"><span class="fa fa-floppy-o" style="font-size:18px;"></span><br>GUARDAR</button></div>
                </div>
            </div>
            <!-- FIN DE LA SEGUNDA COLUMNA -->
        </div>
  <!-- FIN DEL PRIMER DIV -->
    </form>
</main>
  
   
    
<?php
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Presupuesto/Analisis.js"></script>

<script>
  $( function() {
    $( "#draggable" ).draggable();
  } );
  $( function() {
    $('[data-toggle="tooltip"]').tooltip();
  } );
  function Presupuesto(URL, name = ""){ 
        window[name] ? window[name].focus() :  window.open(URL,name,"width=1100,height=450,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0" ); return false;
    }
  </script>

  <?php 
    if(isset($_GET['carga']) && $_GET['carga']== 'true'){ 

        ?>
           
           <script>
                    <?php echo 'Carga("'.$_GET['suc'].'","'.$_GET['cot'].'","'.$_GET['cli'].'","'.$_GET['fila'].'");'; ?>
           </script>
   <?php } 
   if(isset($_GET['suc'])){ 

    ?>
       
       <script>
                <?php echo 'Sucursal("'.$_GET['suc'].'");'; ?>
       </script>
<?php }
  ?>

<!-- Adding alert to prevent accidental navigation away from the page -->
<script>
document.addEventListener("click", () => {
    window.userInteracted = true;
});

window.addEventListener("beforeunload", function (event) {
    if (window.userInteracted) {
        event.preventDefault();
        event.returnValue = "";
    }
});
</script>
