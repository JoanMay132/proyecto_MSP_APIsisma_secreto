<?php 
  include '../dependencias/php/head.php';
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
 
$popper = true;

   ?>


  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:5px;margin-right:0;margin-bottom:0px">
        <div class="row">
            <!-- PRIMERA COLUMNA -->
            <div class="col-12 col-sm-8" >
                <p class="h6 text-info text-center"><strong>ANALISIS DE COSTOS UNITARIOS</strong></p>
                <div class="row" >
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11 " ><strong>CLIENTE:</strong> </label>
                    </div>
                    <div class="col-11" style="padding:unset;">
                        <select class="form-control form-control-sm input-form"></select>
                    </div>
                </div>
                <div class="row" style="padding: unset; margin-top:-10px">
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11"><strong>SERVICIO:</strong> </label>
                    </div>
                    <div class="col-8" style="padding:unset;">
                        <textarea  class="form-control form-control-sm input-form" ></textarea>   
                    </div>
                    <div class="col-1" style="padding:unset;">
                        <label class="txt-11 "><strong>FECHA:</strong> </label>
                    </div>
                    <div class="col-2" style="padding:unset;">
                        <input type="date" class="form-control form-control-sm input-form" >  
                    </div>
                </div>
                <div class="row" style="margin-top:1px"">
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11" ><strong>SOLICITA:</strong> </label>
                    </div>
                    <div class="col-8" style="padding:unset;">
                        <input type="text" class="form-control form-control-sm input-form">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" style="padding:unset;">
                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" data-toggle="modal" data-target="#exampleModal" >
                                <th width="60%" id="border-dark-b">A) MATERIALES</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr class="text-right">
                                    <td colspan="4" ><strong>IMPORTE DE MATERIAL</strong></td>
                                    <td><p class="border-dark input-form"><strong>$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" data-toggle="modal" data-target="#ModalObra" >
                                <th width="60%" id="border-dark-b" >B) MANO DE OBRA</th>
                                <th width="10%"  id="border-dark-b" >CANTIDAD</th>
                                <th width="10%" id="border-dark-b" >JORNADA</th>
                                <th width="10%" id="border-dark-b" >COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b" >IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr >
                                    <td class="text-right" ><strong>CANT. HORA</strong></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;">
                                    </td>
                                    <td colspan="2" class="text-right"><strong>SUMA:</strong></td>
                                    <td class="text-center"><p class="no-bg text-center border-dark input-form">0.00</p></td>
                                </tr>
                                <tr class="text-right">
                                    <td><strong>TIEMPO EXTRAORDINARIO</strong></td>
                                    <td><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;"></td>
                                    <td>
                                        <select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px">
                                            <option>HORA</option>
                                            <option>KG</option>
                                            <option>PULGADA</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;"></td>
                                    <td><p class="no-bg text-center border-dark input-form">$0.00</p></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="4" ><strong>IMPORTE MANO DE OBRA</strong></td>
                                    <td><strong><p class="no-bg text-center border-dark input-form">$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table txt-12 table-sm table-success table-striped table-presupuesto">
                            <thead class="text-center">
                                <th width="80%" id="border-dark-b">B1) HERRAMIENTA MENOR %(B)</th>
                                <th width="10%" id="border-dark-b"><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;"></th>
                                <th width="10%" id="border-dark-b"><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;"></th>
                            </thead>
                            <thead class="text-center">
                                <th width="80%" id="border-dark-b" >B2) EQUIPO DE SEGURIDAD PERSONAL %(B)</th>
                                <th width="10%" id="border-dark-b"><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;"></th>
                                <th width="10%" id="border-dark-b"><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora" min="0" value="0.0" step="0.1" style="height: 20px;"></th>
                            </thead>
                        </table>

                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" data-toggle="modal" data-target="#ModalMaquinaria" >
                                <th width="60%" id="border-dark-b">C) MAQUINARIA Y EQUIPO</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr class="text-right">
                                    <td colspan="4" ><strong>IMPORTE DE MAQUINARIA</strong></td>
                                    <td><p class="border-dark input-form"><strong>$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table   class="table txt-12 table-sm table-success table-striped table-presupuesto">
                            <thead class="text-center" data-toggle="modal" data-target="#ModalAdicional">
                                <th width="60%" id="border-dark-b">D) SERVICIOS ADICIONALES</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <tr class="text-right">
                                    <td colspan="4" ><strong>IMPORTE SERV. ADICIONALES</strong></td>
                                    <td><p class="border-dark input-form"><strong>$0.00</p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- SECCIÓN DE TOTALES -->
                        <div class="row" style="padding:0px">
                            <div class="col-4">
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td class="border-dark"><strong>Contenido Nacional</strong></td>
                                        <td class="border-dark" ><p>$0.00</p></td>
                                    </tr>
                                    <tr>
                                        <td class="border-dark"><strong>PCNS</strong></td>
                                        <td class="border-dark"><p>0.00</p></td>
                                    </tr>
                                </table>
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>CANTIDAD DE PIEZAS (MUL)</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center " id="indirectos"  value="0"  style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>COSTO POR PIEZA</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center " id="indirectos"  value="$0.00"  style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>

                                </table>
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>CANTIDAD DE PIEZAS (DIV)</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center " id="indirectos"  value="0"  style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>COSTO UNITARIO</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center " id="indirectos"  value="$0.00"  style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-8">
                                <table class="table txt-12 table-sm table-primary table-striped text-right table-presupuesto">
                                    <thead >
                                        <th colspan="2" id="border-dark-b">COSTOS DIRECTOS (A+B+B1+B2+C+D):</th>
                                        <th id="border-dark-b" width="20%"><strong><p>$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" style="padding:unset">INDIRECTOS (COSTOS ADMINISTRATIVOS / GENERALES):</th>
                                        <th id="border-dark-b" ><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="indirectos"  value="30%"  style="width: 30px;padding:0px;margin:auto"></th>
                                        <th colspan="2" id="border-dark-b" ><p>$0.00</p></th>
                                    </thead>
                                    <thead  >
                                        <th colspan="2" id="border-dark-b" ><strong>SUMA:</strong></th>
                                        <th id="border-dark-b" width="20%" ><strong><p>$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" >FINANCIAMIENTO:</th>
                                        <th id="border-dark-b" ><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="financiamiento"  value="5%"  style="width: 30px;padding:0px;margin:auto"></th>
                                        <th colspan="2" id="border-dark-b" s><p>$0.00</p></th>
                                    </thead>
                                    <thead >
                                        <th colspan="2" id="border-dark-b" ><strong>SUBTOTAL:</strong> </th>
                                        <th id="border-dark-b" width="20%"  ><strong><p>$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" >(*) UTILIDAD BRUTA:</th>
                                        <th id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="financiamiento"  value="15%"  style="width: 30px;padding:0px;margin:auto"></th>
                                        <th colspan="2"  id="border-dark-b" ><p>$0.00</p></th>
                                    </thead>
                                </table>

                                <table   class="table txt-12 table-sm table-primary table-striped table-presupuesto">
                                    <thead class="text-center" >
                                        <th width="80%" id="border-dark-b"><h4>TOTAL PRECIO UNITARIO</h4></th>
                                        <th width="20%" id="border-dark-b"><h4>$0.00</h4></th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div style="width:auto;height:auto;padding: 3px;" class="table-primary txt-12"><strong>DESCRIPCIÓN DETALLADA DEL SERVICIO</strong></div>
                                <textarea class="form-control form-control-sm input-form txt-12" rows="3" ></textarea>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- SEGUNDA COLUMNA -->
            <div class="col-12 col-sm-4">
                <div class="row" style="padding:5px">
                    <table class="table-presupuesto" style="width:100%;"  >
                        <tr>
                            <td style="width:20%"><p class="txt-12 "><strong>FOLIO:</strong></p></td>
                            <td ><p class="txt-12 border-dark text-center" style="width: 120px; background:white;padding:3px"  >0001/23</p></td>
                            <td ><p class="txt-12 "><strong>PARTIDA:</strong></p></td>
                            <td><p class="txt-12 border-dark text-center" style="width: 100px;background:white;padding:3px" >0.00</p></td>
                        </tr>
                        <tr>
                            <td ><p class="txt-12 "><strong>SUCURSAL:</strong></p></td>
                            <td><select class="form-control-sm input-form" style="width: 120px">
                                <option>VILLAHERMOSA</option>
                            </select></td>
                            <td><p class="txt-12 "><strong>PRE:</strong></p></td>
                            <td><select class="form-control-sm input-form" style="width: 100px;" >
                                <option></option>
                            </select></td>
                        </tr>
                        <tr>
                            <td ><p class="txt-12 "><strong>COTIZACION:</strong></p></td>
                            <td><select class=" form-control-sm input-form" style="width: 120px">
                                <option></option>
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
                            <?php for($i = 1; $i < 5; $i++){ ?>
                            <tr>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
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
                            <?php for($i = 1; $i < 5; $i++){ ?>
                            <tr>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
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
                            <?php for($i = 1; $i < 2; $i++){ ?>
                            <tr>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" class="form-control form-control-sm input-form text-center"></td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div><hr>
                <div class="row">
                    <div class="col-5"><button class="btn btn-outline-info btn-sm"><span class="fa fa-print" style="font-size:18px"></span><br>IMPRIMIR Y GUARDAR</button></div>
                    <div class="col-5"><button class="btn btn-outline-info btn-sm"><span class="fa fa-floppy-o" style="font-size:18px;"></span><br>GUARDAR</button></div>
                </div>
            </div>
            <!-- FIN DE LA SEGUNDA COLUMNA -->
        </div>
  
    </main>
  
   
    
<?
  include_once ('Modales/Materiales.php');
  include_once ('Modales/MObra.php');
  include_once ('Modales/Maquinaria.php');
  include_once ('Modales/Adicionales.php');

  include_once '../dependencias/php/footer.php';
?>

<script type="text/javascript" src="../dependencias/js/Trazabilidad/Analisis.js"></script>

<script>
  $( function() {
    $( "#draggable" ).draggable();
  } );
  $( function() {
    $('[data-toggle="tooltip"]').tooltip();
  } );
  </script>