<?php @session_start(); {$title = '- ANALISIS DE COSTOS';}
  include '../dependencias/php/head.php';

   //Recibe el id del presupuesto
 $idPresupuesto = (int) base64_decode($_GET['edit']);
 
 if(!filter_var($idPresupuesto,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}
$popper = true;
 
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});

$obunidad = new Unidad();
$oSuc  = new Sucursal();
$oPre  = new Presupuesto();
$oMaquin = new Premaquinaria();
$oMobra = new Premobra();
$oMaterial = new Prematerial();
$oServ = new Preservicio();
$oForm  = new Formulas();

foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" => $value['pkunidad'], "nombre" => $value['nombre']);
}

//Consulta el presupuesto
$resul = $oPre->GetData($idPresupuesto);
$sucursal = $resul['fksucursal'];

#region Permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::analisiscosto->value,$resul['fksucursal']);//Se verifica las operaciones
    $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
    $viewCosto = !in_array(Operacion::costo->value,$rol->getOperacion()) ? 'no-cost' : '';
#endregion
   ?>
<style>
    table.table-presupuesto tr td .input-form{
        font-size: 13px !important;
    }
</style>

  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:5px;margin-right:0;margin-bottom:0px">
    <form id="form-presupuesto" onsubmit="return addPresupuesto('<?php echo $_GET['edit']; ?>');">
        <input type="hidden" name="presupuesto" value="<?php echo base64_encode($resul['pkpresupuesto']); ?>">
        <div class="row">
            <!-- PRIMERA COLUMNA -->
            <input type="hidden" name="servicio" id="servicio" value="<?php echo base64_encode($resul['fkservcot']);?>">
            <div class="col-12 col-sm-8" >
                <p class="h6 text-info text-center"><strong>ANALISIS DE COSTOS UNITARIOS</strong></p>
                <div class="row" >
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11 " ><strong>CLIENTE:</strong> </label>
                    </div>
                    <div class="col-11" style="padding:unset;">
                        <select class="form-control form-control-sm input-form" name="cliente" id="listClientes" required>
                           
                        </select>
                    </div>
                </div>
                <div class="row" style="padding: unset; margin-top:-10px">
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11"><strong>SERVICIO:</strong> </label>
                    </div>
                    <div class="col-8" style="padding:unset;">
                        <textarea  class="form-control form-control-sm input-form" required name="inputServicio" id="inputServicio" required><?php echo $resul['servicio']; ?></textarea>   
                    </div>
                    <div class="col-1" style="padding:unset;">
                        <label class="txt-11 "><strong>FECHA:</strong> </label>
                    </div>
                    <div class="col-2" style="padding:unset;">
                        <input type="date" class="form-control form-control-sm input-form" id="fecha" name="fecha" required value="<?php echo $resul['fecha']; ?>">  
                    </div>
                </div>
                <div class="row" style="margin-top:1px"">
                    <div class="col-1" style="padding:unset;">
                        <label  class="txt-11" ><strong>SOLICITA:</strong> </label>
                    </div>
                    <div class="col-8" style="padding:unset;">
                        <input type="text" class="form-control form-control-sm input-form" name="solicita" id="solicita" value="<?php echo $resul['solicita'] ?>">
                    </div>
                    <!-- <div class="offset-1 col-2" style="padding:unset;">
                        <a href="javascript:Presupuesto('cargapresupuesto?suc=<?php echo base64_encode($resul['fksucursal']); ?>','cargapresupuesto')" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i>Carga presupuestos</a>  
                    </div> -->
                </div>
                
                <div class="row">
                    <div class="col-sm-12" style="padding:unset;">
                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" ondblclick="OpenWindows('materiales?suc=<?php echo base64_encode($sucursal); ?>&budget=true','MATERIALES');">
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b">A) MATERIALES</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <?php $importeMaterial = 0; 
                                foreach($oMaterial->GetDataAll($resul['pkpresupuesto']) as $rowMaterial){
                                    
                                    $importeMaterial += $rowMaterial['importe'];?>
                            <tr> 
                                <td><?php if($modifica){ ?><button type="button" onclick="return deleteServ(this,'<?php echo base64_encode($rowMaterial['pkprematerial']); ?>','materiales');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button><?php }?></td>
                                <input type="hidden" name="prematerial[]" value="<?php echo base64_encode($rowMaterial['pkprematerial']); ?>">
                                <input type="hidden" name="cn[]" class="cnacional" value="<?php echo $rowMaterial['contenido']; ?>">
                                <td><input type="text" name="nombre[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="<?php echo $rowMaterial['nombre']; ?>"></td>
                                <td><input type="number" name="cantidad[]" onchange="Importe(this);"   class="form-control form-control-sm no-bg input-form border-dark text-center" min="0.00" value="<?php echo $rowMaterial['cantidad']; ?>" step="0.01" style="height: 20px;" ></td>
                                <td>
                                    <select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidad[]">
                                        <?php foreach($data as $uni){
                                                $selU = $uni['pkunidad'] == $rowMaterial['unidad'] ? 'selected' : '';
                                            ?>
                                            <option <?php echo $selU; ?> value="<?php echo $uni['pkunidad'];?>"><?php echo $uni['nombre'];?></option>
                                       <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" name="costo[]" onchange="Importe(this); moneda(this);" value="$<?php echo number_format($rowMaterial['costounit'], 2, '.', ','); ?>" class="form-control form-control-sm text-center no-bg input-form border-dark <?php echo $viewCosto; ?>" style="height: 20px;"></td>
                                <td><input type="text" name="importe[]" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalMaterial <?php echo $viewCosto; ?>" style="height: 20px;" readonly value="$<?php echo $rowMaterial['importe']; ?>"></td>
                                                
                            </tr>
                            <?php  } ?>
                                <tr class="text-right" id="row-importeMaterial">
                                    <td colspan="5" ><strong>IMPORTE DE MATERIAL</strong></td>
                                    <td><strong><p class="border-dark input-form importeTotal <?php echo $viewCosto; ?>" id="importeTotalMaterial">$<?php echo number_format($importeMaterial, 2, '.', ',');  ?></p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" ondblclick="OpenWindows('mobra?suc=<?php echo base64_encode($sucursal); ?>&budget=true','MANO DE OBRA');"  >
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b" >B) MANO DE OBRA</th>
                                <th width="10%"  id="border-dark-b" >CANTIDAD</th>
                                <th width="10%" id="border-dark-b" >JORNADA</th>
                                <th width="10%" id="border-dark-b" >COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b" >IMPORTE</th>
                            </thead>
                            <tbody>
                            <tr>
                                <?php 
                                $totalMO = 0; $totalHra = 0;
                                foreach($oMobra->GetDataAll($resul['pkpresupuesto']) as $rowMO){ 
                                        $totalMO += $rowMO['importe']; //Clcula el importe por pieza
                                        $totalHra +=  $rowMO['cantidad']; //Calcula la cantidad de horas
                                    ?>
                                <td><?php if($modifica){ ?><button type="button" onclick="return deleteServ(this,'<?php echo base64_encode($rowMO['pkpremobra']); ?>','manoobra');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button><?php } ?></td>
                                <input type="hidden" name="premobra[]" value="<?php echo base64_encode($rowMO['pkpremobra']); ?>">
                                <input type="hidden" name="cnObra[]" class="cnacional" value="<?php echo $rowMO['contenido']; ?>">
                                <td><input type="text" name="descripcionObra[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="<?php echo $rowMO['nombre']; ?>"></td>
                                <td><input type="number" name="cantidadObra[]" onchange="ImporteObra(this);" class="form-control form-control-sm no-bg input-form border-dark text-center totalHora" min="0.00" value="<?php echo $rowMO['cantidad']; ?>" step="0.01" style="height: 20px;" ></td>
                                <td>
                                    <select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadObra[]">';
                                        <?php foreach($data as $uni){
                                            $selU = $uni['pkunidad'] == $rowMO['unidad'] ? 'selected' : '';
                                        ?>
                                        <option <?php echo $selU; ?> value="<?php echo $uni['pkunidad'];?>"><?php echo $uni['nombre'];?></option>
                                   <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" name="costoObra[]" onchange="ImporteObra(this); moneda(this);" value="$<?php echo $rowMO['costounit']; ?>" class="form-control form-control-sm text-center no-bg input-form border-dark <?php echo $viewCosto; ?>" style="height: 20px;"></td>
                                <td><input type="text" name="importeObra[]" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalObra <?php echo $viewCosto; ?>" style="height: 20px;" readonly value="$<?php echo $rowMO['importe']; ?>"></td>                                                
                            </tr>
                            <?php } 
                                $importeExtra = ($resul['textra'] * $resul['costoextra']);
                                $importeMO = ($totalMO + $importeExtra);  
                            ?>
                                <tr id="row-totalHora">
                                    <td class="text-right" ></td>
                                    <td class="text-right" ><strong>CANT. HORA</strong></td>
                                    <td>
                                        <input type="number" readonly class="form-control form-control-sm no-bg text-center border-dark input-form" id="canthora"  min="0" value="<?php echo $totalHra; ?>" step="0.01" style="height: 20px;">
                                    </td>
                                    <td colspan="2" class="text-right"><strong>SUMA:</strong></td>
                                    <td class="text-center"><p id="sumaTotal" class="no-bg text-center border-dark input-form <?php echo $viewCosto; ?>">$<?php echo number_format($totalMO, 2, '.', ','); ?></p></td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-right" ></td>
                                    <td><strong>TIEMPO EXTRAORDINARIO</strong></td>
                                    <td><input type="number" class="form-control form-control-sm no-bg text-center border-dark input-form" id="tExtra" min="0.00" value="<?php echo $resul['textra']; ?>" step="0.01" style="height: 20px;"  onchange="tiempoExtra(); ImporteHtaEq();" name="textra"></td>
                                    <td>
                                        <select class="form-control form-control-sm no-bg text-center border-dark input-form" name="utiempo" style="height:20px;padding:0px">
                                            <option value="HORA">HORA</option>
                                            <option value="ACTIVIDAD">ACTIVIDAD</option>
                                            <option value="PZA">PZA</option>
                                            <option value="SERV">SERV</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm no-bg text-center border-dark input-form" id="costoExtra" min="0" value="$<?php echo $resul['costoextra']; ?>" onchange="moneda(this); tiempoExtra(); ImporteHtaEq();" step="0.01" style="height: 20px;" name="costoextra"></td>
                                    <td><p class="no-bg text-center border-dark input-form <?php echo $viewCosto; ?>" id="totalExtra">$<?php echo number_format($importeExtra, 2, '.', ','); ?></p></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="5" ><strong>IMPORTE MANO DE OBRA</strong></td>
                                    <td><strong><p class="no-bg text-center border-dark input-form importeTotal <?php echo $viewCosto; ?>" id="importeTotalObra">$<?php echo number_format($importeMO, 2, '.', ','); ?></p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table txt-12 table-sm table-success table-striped table-presupuesto">
                            <thead class="text-center">
                                <th width="80%" id="border-dark-b">B1) HERRAMIENTA MENOR %(B)</th>
                                <th width="10%" id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center border-dark input-form porcentaje" id="inputHtamenor" name="htamenor" min="0" value="<?php echo $resul['htamenor']; ?>%" step="0.1" style="height: 20px;" onchange="ImporteHtaEq();"></th>
                                <th width="10%" id="border-dark-b"><p class="input-form importeTotal <?php echo $viewCosto; ?>" id="totalHtamenor">$0.00</p></th>
                            </thead>
                            <thead class="text-center">
                                <th width="80%" id="border-dark-b" >B2) EQUIPO DE SEGURIDAD PERSONAL %(B)</th>
                                <th width="10%" id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center border-dark input-form porcentaje" id="inputEquipo" min="0" value="<?php echo $resul['segpersonal']; ?>%" step="0.1" style="height: 20px;" onchange="ImporteHtaEq();" name="segpersonal"></th>
                                <th width="10%" id="border-dark-b"><p class="input-form importeTotal <?php echo $viewCosto; ?>" id="totalEquipo">$0.00</p></th>
                            </thead>
                        </table>

                        <table  class="table txt-12 table-sm table-success table-striped table-presupuesto"  >
                            <thead class="text-center" ondblclick="OpenWindows('maquinaria?suc=<?php echo base64_encode($sucursal); ?>&budget=true','MAQUINARIA');" >
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b">C) MAQUINARIA Y EQUIPO</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <?php $importeMaq = 0;
                                foreach($oMaquin->GetDataAll($resul['pkpresupuesto']) as $rowMaq){
                                        $importeMaq += $rowMaq['importe'];
                                    ?>
                            <tr>
                                <td><?php if($modifica){ ?><button type="button" onclick="return deleteServ(this,'<?php echo base64_encode($rowMaq['pkpremaquinaria']); ?>','maquinaria');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button><?php } ?></td>
                                <input type="hidden" name="premaquinaria[]" value="<?php echo base64_encode($rowMaq['pkpremaquinaria']); ?>">
                                <input type="hidden" name="cnMaquin[]" class="cnacional" value="<?php echo $rowMaq['contenido']; ?>">
                                <td><input type="text" name="descripcionMaquin[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="<?php echo $rowMaq['nombre']; ?>"></td>
                                <td><input type="number" name="cantidadMaquin[]" onchange="ImporteMaquinaria(this);" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0.00" value="<?php echo $rowMaq['cantidad']; ?>" step="0.01" style="height: 20px;" ></td>
                                <td>
                                    <select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadMaquin[]">
                                    <?php foreach($data as $uni){
                                            $selU = $uni['pkunidad'] == $rowMaq['unidad'] ? 'selected' : '';
                                        ?>
                                        <option <?php echo $selU; ?> value="<?php echo $uni['pkunidad'];?>"><?php echo $uni['nombre'];?></option>
                                   <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" name="costoMaquin[]" onchange="ImporteMaquinaria(this); moneda(this);" value="$<?php echo $rowMaq['costounit']; ?>" class="form-control form-control-sm text-center no-bg input-form border-dark <?php echo $viewCosto; ?>" style="height: 20px;"></td>
                                <td><input type="text" name="importeMaquin[]" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalMaquin <?php echo $viewCosto; ?>" style="height: 20px;" readonly value="$<?php echo $rowMaq['importe']; ?>"></td>
                                                
                            </tr>
                            <?php } ?>
                                <tr class="text-right" id="row-importeMaquin">
                                    <td colspan="5" ><strong>IMPORTE DE MAQUINARIA</strong></td>
                                    <td><strong><p id="importeTotalMaquin" class="border-dark input-form importeTotal <?php echo $viewCosto; ?>">$<?php echo number_format($importeMaq, 2, '.', ','); ?></p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <table   class="table txt-12 table-sm table-success table-striped table-presupuesto">
                            <thead class="text-center" ondblclick="OpenWindows('adicionales?suc=<?php echo base64_encode($sucursal); ?>&budget=true','MAQUINARIA');">
                                <th width="3%" id="border-dark-b"></th>
                                <th width="60%" id="border-dark-b">D) SERVICIOS ADICIONALES</th>
                                <th width="10%" id="border-dark-b">CANTIDAD</th>
                                <th width="10%" id="border-dark-b">UNIDAD</th>
                                <th width="10%" id="border-dark-b">COSTO UNIT.</th>
                                <th width="10%" id="border-dark-b">IMPORTE</th>
                            </thead>
                            <tbody>
                                <?php $importeAd = 0;
                                foreach($oServ->GetDataAll($resul['pkpresupuesto']) as $rowAd){ 
                                       $importeAd += $rowAd['importe']; 
                                    ?>
                            <tr>
                                <input type="hidden" name="preservicio[]" value="<?php echo base64_encode($rowAd['pkpreservicio']); ?>">
                                <input type="hidden" name="cnAdicional[]" class="cnacional" value="<?php echo $rowAd['contenido']; ?>">
                                <td><?php if($modifica){ ?><button type="button" onclick="return deleteServ(this,'<?php echo base64_encode($rowAd['pkpreservicio']); ?>','servicios');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button><?php } ?></td>
                                <td><input type="text" name="descripcionAdicional[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="<?php echo $rowAd['nombre']; ?>"></td>
                                <td><input type="number" name="cantidadAdicional[]" onchange="ImporteAdicional(this);" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0.00" value="<?php echo $rowAd['cantidad']; ?>" step="0.01" style="height: 20px;" ></td>
                                <td>
                                    <select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadAdicional[]">
                                    <?php foreach($data as $uni){
                                            $selU = $uni['pkunidad'] == $rowAd['unidad'] ? 'selected' : '';
                                        ?>
                                        <option <?php echo $selU; ?> value="<?php echo $uni['pkunidad'];?>"><?php echo $uni['nombre'];?></option>
                                   <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" name="costoAdicional[]" onchange="ImporteAdicional(this); moneda(this);" value="$<?php echo $rowAd['costounit']; ?>" class="form-control form-control-sm text-center no-bg input-form border-dark <?php echo $viewCosto; ?>" style="height: 20px;"></td>
                                <td><input type="text" name="importeAdicional[]" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalAdicional <?php echo $viewCosto; ?>" value="$<?php echo $rowAd['importe']; ?>" style="height: 20px;" readonly></td>
                                                
                            </tr>
                            <?php } ?>
                                <tr class="text-right" id="row-importeAdicional">
                                    <td colspan="5" ><strong>IMPORTE SERV. ADICIONALES</strong></td>
                                    <td><strong><p class="border-dark input-form importeTotal <?php echo $viewCosto; ?>" id="importeTotalAdicional">$<?php echo number_format($importeAd, 2, '.', ','); ?></p></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- SECCIÓN DE TOTALES -->
                        <div class="row" style="padding:0px">
                            <div class="col-5">
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%" class="border-dark"><strong>Contenido Nacional</strong></td>
                                        <td class="border-dark"><input type="text" readonly id="cnacional" class="form-control form-control-sm text-center input-form <?php echo $viewCosto; ?>" value="$0.00" style="margin:auto;padding:0px;height:auto;font-size:12px" name="cnacional"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-dark"><strong>PCNS</strong></td>
                                        <td class="border-dark" style="background:white"><p id="pcnc" >0.00</p></td>
                                    </tr>
                                </table>
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>CANTIDAD DE PIEZAS (MUL)</strong></td>
                                        <td width="23%" class="border-dark" ><input type="number" onchange="Operaciones();" pattern="[0-9]*" class="form-control form-control-sm text-center " id="iMul"  value="<?php echo (int)$resul['cantmult']; ?>"  style="margin:auto;padding:0px;height:auto" name="canmult"></td>
                                    </tr>
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>COSTO POR PIEZA</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center <?php echo $viewCosto; ?>" id="cpieza" readonly value="$0.00"  style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>

                                </table>
                                <table class="table txt-12 table-sm table-primary table-striped text-center table-presupuesto">
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>CANTIDAD DE PIEZAS (DIV)</strong></td>
                                        <td width="23%" class="border-dark" ><input type="number" onchange="Operaciones();" class="form-control form-control-sm text-center " id="iDiv"  value="<?php echo (int)$resul['cantdiv']; ?>" style="margin:auto;padding:0px;height:auto" name="cantdiv"></td>
                                    </tr>
                                    <tr>
                                        <td width="77%"  class="border-dark"><strong>COSTO UNITARIO</strong></td>
                                        <td width="23%" class="border-dark" ><input type="text" class="form-control form-control-sm text-center <?php echo $viewCosto; ?>"   value="$0.00" readonly id="cdiv" style="margin:auto;padding:0px;height:auto"></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-7">
                                <table class="table txt-12 table-sm table-primary table-striped text-right table-presupuesto">
                                    <thead >
                                        <th colspan="2" id="border-dark-b">COSTOS DIRECTOS (A+B+B1+B2+C+D):</th>
                                        <th id="border-dark-b" width="20%"><strong><p id="cDirectos" class="<?php echo $viewCosto; ?>">$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" style="padding:unset">INDIRECTOS (COSTOS ADMINISTRATIVOS / GENERALES):</th>
                                        <th id="border-dark-b" ><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" name="indirectos" value="<?php echo (int) $resul['indirectos']; ?>%" id="iindirectos" onchange="ImporteTotal();"  style="width: 30px;padding:0px;margin:auto" ></th>
                                        <th colspan="2" class="border-dark-b"><p id="cindirectos" class="<?php echo $viewCosto; ?>">$0.00</p></th>
                                    </thead>
                                    <thead  >
                                        <th colspan="2" id="border-dark-b" ><strong>SUMA:</strong></th>
                                        <th id="border-dark-b" width="20%" ><strong><p id="suma" class="<?php echo $viewCosto; ?>">$0.00</p></strong></th>
                                    </thead>
                                    <thead >
                                        <th id="border-dark-b" >FINANCIAMIENTO:</th>
                                        <th id="border-dark-b" ><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="ifinanciamiento"  value="<?php echo (int) $resul['financiamiento']; ?>%" onchange="ImporteTotal();" style="width: 30px;padding:0px;margin:auto" name="financiamiento"></th>
                                        <th colspan="2" id="border-dark-b" ><p id="financiamiento" class="<?php echo $viewCosto; ?>">$0.00</p></th>
                                    </thead>
                                    <thead >
                                        <th colspan="2" id="border-dark-b" ><strong>SUBTOTAL:</strong> </th>
                                        <th id="border-dark-b" width="20%"  ><strong><p id="subtotal" class="<?php echo $viewCosto; ?>">$0.00</p></strong></th>
                                    </thead>
                                    <thead  >
                                        <th id="border-dark-b" >(*) UTILIDAD BRUTA:</th>
                                        <th id="border-dark-b"><input type="text" class="form-control form-control-sm no-bg text-center input-form porcentaje" id="iutilidad" onchange="ImporteTotal();" value="<?php echo (int) $resul['utilidad']; ?>%" id="" style="width: 30px;padding:0px;margin:auto" name="utilidad" ></th>
                                        <th colspan="2"  id="border-dark-b" ><p id="utilidad" class="<?php echo $viewCosto; ?>">$0.00</p></th>
                                    </thead>
                                </table>

                                <table   class="table txt-12 table-sm table-primary table-striped table-presupuesto">
                                    <thead class="text-center" >
                                        <th width="80%" id="border-dark-b" ><h4>TOTAL PRECIO UNITARIO</h4></th>
                                        <th width="20%" id="border-dark-b" ><h4 id="totalAll" class="<?php echo $viewCosto; ?>">$0.00</h4></th>
                                        <input type="hidden" name="totalUnitario" id="totalUnitario">
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div style="width:auto;height:auto;padding: 3px;" class="table-primary txt-12"><strong>DESCRIPCIÓN DETALLADA DEL SERVICIO</strong></div>
                                <textarea class="form-control form-control-sm input-form txt-12" rows="3" name="descripcion" ><?php echo $resul['descripcion']; ?></textarea>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
                        <!-- SEGUNDA COLUMNA -->
            <div class="col-12 col-sm-4">
                <div class="row" style="padding:5px">
                    <table style="width:100%;"  >
                        <tr>
                            <td style="width:20%"><p class="txt-12 "><strong>FOLIO:</strong></p>
                            <input type="hidden" name="folio" value="<?php echo $resul['folio']; ?>" id="inputFolio">
                        </td>
                            <td ><p class="txt-12 border-dark text-center" style="width: 120px; background:white;padding:3px" id="displayFolio"><?php echo $resul['folio']; ?></p></td>
                            <td ><p class="txt-12 "><strong>PARTIDA:</strong></p></td>
                            <td><input type="text" name="pda" readonly class="form-control-sm input-form text-center" value="<?php echo $resul['pda']; ?>" style="width: 100px;background:white;" id="pda" ></td>
                        </tr>
                        <tr>
                            <td ><p class="txt-12 "><strong>SUCURSAL:</strong></p></td>
                            <td><select class="form-control-sm input-form" style="width: 120px" name="sucursal" id="sucursal" required>
                                    <option value="<?php echo base64_encode($resul['fksucursal']); ?>"><?php echo $resul['nombre']; ?></option>
                            </select></td>
                            <!-- <td><p class="txt-12 "><strong>PRE:</strong></p></td>
                            <td><select class="form-control-sm input-form" style="width: 100px;" >
                                <option></option>
                            </select></td> -->
                            <td colspan="2"><a href="javascript:Presupuesto('cargapredefinido?suc=<?php echo base64_encode($resul['fksucursal']);; ?>','cargapredefinido')"  class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Carga Predefinidos</a></td>
                        </tr>
                        <tr>
                            <td ><p class="txt-12 "><strong>COTIZACION:</strong></p></td>
                            <td><select class=" form-control-sm input-form" style="width: 120px" id="listCotizacion" name="cotizacion">
                               
                            </select></td>
                            <td colspan="2"><a href="javascript:Presupuesto('cargapresupuesto?suc=<?php echo base64_encode($resul['fksucursal']); ?>','cargapresupuesto')" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i>Carga presupuestos</a></td>
                            
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
                                <td><input type="number" name="espesor" onchange="formulaM2();" value="<?php echo $value['espesor']; ?>" step="000.001" id="F-espesor" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" name="factor" onchange="formulaM2();" value="<?php echo $value['factor']; ?>" step="000.001" id="F-factor" class="form-control form-control-sm input-form text-center"></td>
                                <td><input type="number" name="km2"  id="F-km" value="<?php echo $value['km2']; ?>" class="form-control form-control-sm input-form text-center" step="000.001"></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                    </table>
                </div><hr>
                <div class="row">
                    <div class="col-5"><button type="button" class="btn btn-outline-info btn-sm" id="btnPrint"><span class="fa fa-print" style="font-size:18px"></span><br>IMPRIMIR</button></div>
                    <?php if($modifica){ ?>
                    <div class="col-5"><button class="btn btn-outline-info btn-sm" id="btnSave"><span class="fa fa-floppy-o" style="font-size:18px;"></span><br>GUARDAR</button></div>
                    <?php } ?>
                </div>
            </div>
            <!-- FIN DE LA SEGUNDA COLUMNA -->
        </div>
  <!-- FIN DEL PRIMER DIV -->
    </form>
</main>
<center><div class="loader"><h3>Cargando Presupuesto...</h3></div></center>
   
    
<?php
  include_once '../dependencias/php/footer.php';
?>

<script type="text/javascript" src="../dependencias/js/Presupuesto/Analisis.js"></script>

<script>
  $( function() {
    $(".loader").fadeOut("slow");
    $( "#draggable" ).draggable();
    $('[data-toggle="tooltip"]').tooltip();
    
    
  } );
$(document).ready(function () {
	Carga("<?php echo base64_encode($resul['fksucursal']); ?>","<?php echo base64_encode($resul['fkcotizacion']); ?>","<?php echo base64_encode($resul['fkcliente']); ?>");
});

function Presupuesto(URL, name = ""){ 
        window[name] ? window[name].focus() :  window.open(URL,name,"width=1100,height=450,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0" ); return false;
    }
  </script>