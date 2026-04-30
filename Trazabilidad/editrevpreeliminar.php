<?php @session_start();  {$title = '- REVISIÓN';}
include '../dependencias/php/head.php';
//Recibe el id del la revicion
$idRevicion = (int) base64_decode($_GET['edit']);
if(!filter_var($idRevicion,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;} //Valida la url
  
spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
 
$popper = true;
$obsuc = new Sucursal();
$obunidad = new Unidad();
$obcli = new Cliente();
$obUsercli = new Usercli();
$obrev = new Revpreeliminar();

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }

  $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");
  //Consultamos la rev preeliminar
  $resRev =  $obrev->GetData($idRevicion);

  $rol->getPermissionControl($_SESSION['controles'],Controls::revpreeliminar->value,$resRev['fksucursal']);//Se verifica las operaciones
  $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
  $viewCosto = !in_array(Operacion::costo->value,$rol->getOperacion()) ? 'no-cost' : '';
   ?>

<script>
function popup(URL) {
    window.open(URL, "", "width=500,height=750,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
}

function servicios(URL) {
    window.open(URL, "", "width=800,height=800;scrollbars=yes,left=900,addressbar=0,menubar=0,toolbar=0");
}
function Area(URL) {
        window.open(URL, "", "width=500,height=450;scrollbars=yes,left=500,top=130,addressbar=0,menubar=0,toolbar=0");
    }
</script>
<style>
body::-webkit-scrollbar {
    width: 0px;
    /* Ancho de la barra de desplazamiento */
    height: 8px;
}
td:focus-within {
 border: 1.7px solid #b9ddea;
}

.form-control {
    border-radius: unset;
    height: 20px;
    font-size: 11px;
    padding: 2px;
}

.form-group.row,
.form-group {
    padding: 0px !important;
    margin-top: unset !important;
    margin-bottom: -3px !important;
    /* border:1px solid black; */
}

.row {
    padding: unset !important;
    margin-top: unset !important;
    margin-bottom: unset !important;
}

.top-8 {
    margin-top: -8px;
}

.top-3 {
    margin-top: -3px;
}

input[type="radio"] {
    transform: scale(0.6) !important;
}
/*
    table .form-control:focus{
        border: 1px solid #80bdff;
    }*/
table .form-control {
    height: 30px;
}


</style>

<div class="main-wrapper">

    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin:0">

        <form id="form-revpreeliminar" onsubmit="return addRevpreeliminar();">
            <input type="hidden" name="revpreeliminar" value="<?php echo base64_encode($idRevicion); ?>">
            <div class="row" style="margin-top: 0px">
                <div class="col-12 col-sm-5">
                    <div class="form-group row">
                        <label for="listClientes" class="txt-12 text-secondary col-12 col-sm-3">Cliente</label>
                        <select name="cliente" id="listClientes" class="form-control col-12 col-sm-9"
                            onchange="return usercustomer(this.value);">
                            <option value=""></option>
                            <?php foreach ($obcli->GetDataAll($resRev['fksucursal']) as $resCliente){
                            $selecCli = $resCliente['pkcliente'] == $resRev['fkcliente'] ? 'selected' : '';
                            echo "<option ".$selecCli." value='".base64_encode($resCliente['pkcliente'])."'>".$resCliente['nombre']."</option>";
                        } ?>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="listUsercustomer" class="txt-12 text-secondary col-12 col-sm-3">Solicito</label>
                        <select name="solicito" id="listUsercustomer" onchange="return viewDepto(this.value);"
                            class="form-control col-8 col-sm-8 listUsercustomer ">
                            <option></option>
                        </select>
                        <div class="col-1 col-sm-1 text-center" style="padding:0">
                            <button type="button" id="buttonUser" title="Agregar usuario" data-toggle="tooltip"
                                class="btn btn-outline-secondary"
                                style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none"><i
                                    style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px"
                                    class="fa fa-user-plus"></i></button>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="deptouser" class="txt-12 text-secondary col-12 col-sm-3">Departamento</label>
                        <input value="<?php echo $resRev['depto'] ?>" type="text" name="depto" id="deptouser"
                            class="form-control col-12 col-sm-8 ">
                            <div class="col-1 col-sm-1" style="padding:0">
                                <button type="button" id="buttonArea" title="Agregar nueva area" data-toggle="tooltip" class="btn btn-outline-secondary" style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px;;" class="fa fa-list-alt"></i></button>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label for="proyecto" class="txt-12 text-secondary col-12 col-sm-3 ">Proyecto</label>
                        <input value="<?php echo htmlspecialchars($resRev['proyecto']); ?>" type="text" id="proyecto" name="proyecto"
                            class="form-control col-12 col-sm-9">

                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group row">
                        <label for="sucursal" class="txt-12 text-secondary col-12 col-sm-3">Sucursal</label>
                        <select onchange="return Sucursal(this.value);" name="sucursal" id="sucursal"
                            class="form-control col-12 col-sm-9">
                            <option value="<?php echo base64_encode($resRev['fksucursal']); ?>"><?php echo $resRev['nombre'] ?></option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="fecha" class="txt-12 text-secondary col-12 col-sm-3">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control col-12 col-sm-9"
                            value="<?php echo $resRev['fecha']; ?>">
                    </div>
                    <div class="form-group row">
                        <label for="folio" class="txt-12 text-secondary col-12 col-sm-3">Rev.</label>
                        <div class="txt-12 col-12 col-sm-9" style="padding:5px;display:flex;">
                            <p id="displayFolio" style="color:blue;border:1px solid grey"><?php echo $resRev['folio']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="form-group row" style="margin-left:15px">

                    <?php if($modifica){ ?>
                        <button type="submit" class="btn btn-info btn-sm" style="border-radius:0px;" name="imprimir"
                            id="imprimir"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                
                        <?php }else{ ?>
                            <button type="button" id="imprimir" class="btn btn-info btn-sm" style="border-radius:0px;" onclick="return onlyImpression('<?php echo $_GET['edit'] ?>');" name="imprimir"
                            ><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                       <?php } if($modifica){ //Si se tiene permiso de modificar, muestra el boton ?>
                        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar"
                            id="guardar"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
                            <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:-15px;">
                <div class="col-12" style="padding:2px">
                    <div style="overflow:auto;height: 43vh;background:white;border:2px solid grey" class="scroll">

                        <table class="table-stripped table-bordered table-revpreeliminar" width="100%" id="table"
                            style="background-color: white;font-size:11px;">
                            <thead style="position: sticky;top:0;z-index: 10;" class="table-info">
                                <tr>
                                    <th width="5%">PDA</th>
                                    <th width="5%">CANT.</th>
                                    <th width="9%">UNIDAD</th>
                                    <th width="">DESCRIPCIÓN</th>
                                    <th width="10%">COSTO</th>
                                    <th width="15%">TIPO TRABAJO</th>
                                </tr>
                            </thead>
                            <tbody class="text-secondary body-table table-hover">
                                <?php
                            $contador = 0;
                        foreach($obrev->GetDataAllServ($resRev['pkrevpreeliminar']) as $resServ){

                             ?>

                                <tr style="max-height: 150px;" ondblclick='addServpreeliminar(<?php echo json_encode($data);  ?>,"<?php echo $viewCosto; ?>",<?php echo json_encode($tipo);  ?>)' id="servReg-<?php echo $resServ['pkservrevicion']; ?>"
                                    class="row-serv" data-row=<?php echo $contador; ?>>
                                    <input type="hidden" name="pkservrevicionReg[]"
                                        value="<?php echo base64_encode($resServ['pkservrevicion']); ?>">
                                    <td valign="top"><input name="pdaReg[]" type="number" min="0.00" step="0.01"
                                            class="form-control form-control-sm" name="pda" autocomplete="off"
                                            value="<?php echo $resServ['pda']; ?>"></td>

                                    <td valign="top"><input name="cantReg[]" type="number" min="0.00" step="0.01"
                                            class="form-control form-control-sm" autocomplete="off"
                                            value="<?php echo $resServ['cantidad']; ?>"></td>

                                    <td valign="top">
                                        <select name="unidadReg[]" class="form-control form-control-sm">
                                            <option value=""></option>
                                            <?php foreach($data as $unidad){ 
                                            $selecUnidad = $unidad['pkunidad'] == $resServ['unidad'] ? 'selected' : '';
                                        ?>
                                            <option <?php echo $selecUnidad; ?>
                                                value=" <?php echo $unidad["pkunidad"]; ?>">
                                                <?php echo $unidad["nombre"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>

                                    <td valign="top">
                                        <textarea oninput="autoResize(this);" onclick="menu(this); return false;"
                                            onfocus="mostrarScroll('descripcion-<?php echo $contador;?>')"
                                            onblur="ocultarScroll('descripcion-<?php echo $contador;?>')"
                                            spellcheck="false" id="descripcion-<?php echo $contador;?>"
                                            name="descripcionReg[]" class="form-control cajas-texto scrollHidden" autocomplete="off"
                                            style="resize:none;max-height:150px"
                                            data-servicio='servicios?row=<?php echo $contador; ?>'
                                            data-servReg="<?php echo $resServ['pkservrevicion']; ?> "><?php echo $resServ['descripcion']; ?></textarea>
                                    </td>

                                    <td valign="top">
                                        <input onchange="moneda(this);" id="costo-<?php echo $contador; ?>"
                                            name="costoReg[]" type="text"
                                            value="$<?php echo number_format($resServ['costo'], 2, '.', ',');?>"
                                            class="form-control form-control-sm <?php echo $viewCosto ?>" autocomplete="off" >
                                        <input type="hidden" id="item-<?php echo $contador;?>" name="itemReg[]"
                                            value="<?php echo $resServ['item'];?>">
                                    </td>
                                    <td valign="top">
                                        <!-- <button type="button" onclick="javascript:servicios('servicios?row=<?php echo $contador; ?>')" class="btn-info" title="Agregar servicio" id="accion-<?php echo $contador;?>" style="width:100%;height:15px;"></button>
                                    <button type="button" onclick="return delServReg(this,'<?php echo base64_encode($resServ['pkservrevicion']) ?>');" class="btn-danger" data-servReg="<?php echo $resServ['pkservrevicion']; ?>" title="Eliminar" id="accion-0" style="width:100%;height:15px;"></button> -->
                                        <select name="ttrabajoReg[]" class="form-control "
                                            style="text-align: justify;white-space:wrap;padding:0px;">
                                            <option value=""></option>
                                            <?php   
                                                foreach ($tipo as $value) {
                                                    $selTipo = $resServ['tipotrabajo'] == $value ? 'selected' : '';
                                                    echo "<option ".$selTipo." value='".$value."'>".$value."</option>";
                                                }
                                        ?>

                                        </select>
                                    </td>
                                </tr>
                                <?php $contador++; }
                                    if($contador == 0){
                                ?>

                                <tr style="max-height: 80px;"
                                    ondblclick='addServpreeliminar(<?php echo json_encode($data); ?>,"<?php echo $viewCosto; ?>",<?php echo json_encode($tipo); ?>)' id="serv-0">
                                    <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01"
                                            class="form-control form-control-sm" autocomplete="off"></td>
                                    <td valign="top"><input name="cant[]" type="number" min="0.00" step="0.01"
                                            class="form-control form-control-sm" autocomplete="off"></td>
                                    <td valign="top">
                                        <select name="unidad[]" class="form-control form-control-sm">
                                            <option value=""></option>
                                            <?php foreach($data as $unidad){ ?>
                                            <option value=" <?php echo $unidad["pkunidad"]; ?>">
                                                <?php echo $unidad["nombre"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td valign="top"><textarea onclick="menu(this); return false;"
                                            oninput="autoResize(this);" onfocus="mostrarScroll('descNew-0')"
                                            onblur="ocultarScroll('descNew-0')" spellcheck="false" id="descNew-0"
                                            name="descripcion[]" class="form-control cajas-texto scrollHidden" autocomplete="off"
                                            style="resize:none;height:30px;" data-servicio='servicios?row=0&new=true'
                                            data-serv="0"></textarea></td>
                                    <td valign="top"><input onchange="moneda(this);" id="costoNew-0" name="costo[]"
                                            type="text" class="form-control <?php echo $viewCosto; ?>" autocomplete="off">
                                        <input type="hidden" name="item[]" id="itemNew-0">
                                    </td>
                                    <td valign="top">
                                        <select name="ttrabajo[]" class="form-control "
                                            style="text-align: justify;white-space:wrap;padding:0px;">
                                            <option value=""></option>
                                            <?php   
                                                foreach ($tipo as $value) {
                                                   // $selTipo = $res['tipotrabajo'] == $value ? 'selected' : '';
                                                    echo "<option value='".$value."'>".$value."</option>";
                                                }
                                        ?>

                                        </select>
                                    </td>
                                </tr>
                                     <?php } ?>               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group row">
                        <label for="reqinsp" class="txt-12 text-secondary col-12 col-sm-3">Req. de insp. y
                            documentación</label>
                        <textarea name="reqinsp" id="reqinsp" onblur="ocultarScroll(this.id);" onfocus="mostrarScroll(this.id);" onclick="menuTexto(this,'reqinsp-menu');return false;" class="form-control col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['reqinsdoc'] ?></textarea>

                    </div>
                    <div class="form-group row">
                        <label for="reqleg" class="txt-12 text-secondary col-12 col-sm-3">Req. legales y
                            reglamentarios</label>
                        <textarea name="reqlegales" id="reqleg" onblur="ocultarScroll(this.id);" onfocus="mostrarScroll(this.id);" class="form-control col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['reqlegales'] ?></textarea>

                    </div>
                    <div class="form-group row">
                        <label for="condpago" class="txt-12 text-secondary col-12 col-sm-3">Condiciones de pago</label>
                        <textarea id="condpago" name="condpago" onblur="ocultarScroll(this);" onfocus="mostrarScroll(this.id);" onclick="menuTexto(this,'condpago-menu');return false;" class="form-control col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['condpago'] ?></textarea>
                    </div>
                    <div class="form-group row">
                        <label for="desviacionexc"
                            class="txt-12 text-secondary col-12 col-sm-3">Desviación/<br>Excepciones</label>
                        <textarea id="desviacionexc" onblur="ocultarScroll(this.id);" onfocus="mostrarScroll(this.id);"  name="desviacionexc" class="form-control col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['desviacionexc'] ?></textarea>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group row">
                        <label for="reqent" class="txt-12 text-secondary col-12 col-sm-3">Requisitos de
                            entrega</label>
                        <textarea id="reqent" name="reqent" onblur="ocultarScroll(this.id);" onfocus="mostrarScroll(this.id);" onclick="menuTexto(this,'reqent-menu');return false;" class="form-control col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['reqent'] ?></textarea>

                    </div>
                    <div class="form-group row">
                        <label for="reqespserv" class="txt-12 text-secondary col-12 col-sm-3">Req. especiales del
                            servicio</label>
                        <textarea id="reqespserv" name="reqespserv" onblur="ocultarScroll(this.id);" onfocus="mostrarScroll(this.id);" onclick="menuTexto(this,'reqespserv-menu');return false;" class="form-control form-control-sm col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['reqespserv'] ?></textarea>
                    </div>
                    <div class="form-group row">
                        <label for="propcli" class="txt-12 text-secondary col-12 col-sm-3">Uso de propiedad del
                            cliente</label>
                        <textarea id="propcli" name="propcli" onblur="ocultarScroll(this.id);" onfocus="mostrarScroll(this.id);" onclick="menuTexto(this,'propcli-menu');return false;" class="form-control col-12 col-sm-9 scrollHidden"
                            style="height:60px;font-size:11px;resize:none"><?php echo $resRev['propcli'] ?></textarea>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="form-group text-center">
                        <label for="ventas" class="txt-12 text-secondary">Ventas</label>
                        <select id="ventas" name="venta" class="form-control listEmployee">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group text-center">
                        <label for="calidad" class="txt-12 text-secondary">Calidad</label>
                        <select id="calidad" name="calidad" class="form-control listEmployee">
                            <option></option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-12 col-sm-3">
                    <div class="form-group text-center">
                        <label for="manufactura" class="txt-12 text-secondary">Manufactura</label>
                        <select id="manufactura" name="manufactura" class="form-control listEmployee">
                            <option></option>
                        </select>
                    </div>
                </div> -->
                <div class="col-12 col-sm-4">
                    <div class="form-group text-center">
                        <label for="produccion" class="txt-12 text-secondary">Produccion</label>
                        <select id="produccion" name="produccion" class="form-control listEmployee">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <div id="menu" class="context-menu">
        <ul>
            <li><a id="servicio"><i class="fa fa-plus-square-o fa-lg" style="color:blue;margin-right:10px"></i>AGREGAR
                    SERVICIO</a></li>
              <?php if($modifica){ ?>  
            <li><a id="eliminar"><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR
                    REGISTRO</a></li>
                    <?php } ?>
        </ul>
    </div>
    <div id="reqinsp-menu" class="context-menu" style="display:none">
        <ul>
            <li><input type="checkbox" class="check-reqinsp"  onclick="Aggregation('reqinsp','check-reqinsp');" value="REPORTE DIMENCIONAL">REPORTE DIMENCIONAL</li>
            <li><input type="checkbox" class="check-reqinsp" onclick="Aggregation('reqinsp','check-reqinsp'); " value="REPORTE DE INSPECCION CON PARTICULAS MAGNETICAS FLUORESCENTES">REPORTE DE INSPECCION CON PARTICULAS MAGNETICAS FLUORESCENTES</li>
            <li><input type="checkbox" class="check-reqinsp" onclick="Aggregation('reqinsp','check-reqinsp'); " value="REPORTE DE INSPECCION CON LIQUIDOS PENETRANTES">REPORTE DE INSPECCION CON LIQUIDOS PENETRANTES</li>
            <li><input type="checkbox" class="check-reqinsp" onclick="Aggregation('reqinsp','check-reqinsp'); " value="CERTIFICADO DE MATERIAL">CERTIFICADO DE MATERIAL</li>
            <li><input type="checkbox" class="check-reqinsp" onclick="Aggregation('reqinsp','check-reqinsp'); " value="REPORTE DE REVESTIMIENTO">REPORTE DE REVESTIMIENTO</li>
            <li><input type="checkbox" class="check-reqinsp" onclick="Aggregation('reqinsp','check-reqinsp'); " value="REPORTE DE CONFORMIDAD">REPORTE DE CONFORMIDAD</li>
        </ul>
    </div>
    <div id="reqent-menu" class="context-menu" style="display:none">
        <ul>
            <li><input type="checkbox" class="check-reqent"  onclick="Aggregation('reqent','check-reqent');" value="NINGUNO">NINGUNO</li>
            <li><input type="checkbox" class="check-reqent" onclick="Aggregation('reqent','check-reqent'); " value="PLANTA DEL CLIENTE">PLANTA DEL CLIENTE</li>
            <li><input type="checkbox" class="check-reqent" onclick="Aggregation('reqent','check-reqent'); " value="BASE DEL PROVEEDOR">BASE DEL PROVEEDOR</li>
        </ul>
    </div>
    <div id="reqespserv-menu" class="context-menu" style="display:none">
        <ul>
            <li><input type="checkbox" class="check-reqespserv"  onclick="Aggregation('reqespserv','check-reqespserv');" value="NINGUNO">NINGUNO</li>
            <li><input type="checkbox" class="check-reqespserv" onclick="Aggregation('reqespserv','check-reqespserv'); " value="COLD ROLLING">COLD ROLLING</li>
            <li><input type="checkbox" class="check-reqespserv" onclick="Aggregation('reqespserv','check-reqespserv'); " value="SHOT PEENING">SHOT PEENING</li>
            <li><input type="checkbox" class="check-reqespserv" onclick="Aggregation('reqespserv','check-reqespserv'); " value="TIMBRADO DE NARIZ">TIMBRADO DE NARIZ</li>
        </ul>
    </div>
    <div id="propcli-menu" class="context-menu" style="display:none">
        <ul>
            <li><input type="checkbox" class="check-propcli"  onclick="Aggregation('propcli','check-propcli');" value="NINGUNO">NINGUNO</li>
            <li><input type="checkbox" class="check-propcli" onclick="Aggregation('propcli','check-propcli'); " value="TAPON CUBRE ROSCAS">TAPON CUBRE ROSCAS</li>
            <li><input type="checkbox" class="check-propcli" onclick="Aggregation('propcli','check-propcli'); " value="CAJA PROTECTORA">CAJA PROTECTORA</li>
        </ul>
    </div>

    <div id="condpago-menu" class="context-menu" style="display:none">
        <ul>
            <table>
                <tr>
                    <td>
                        <li><input type="checkbox" class="check-condpago"  onclick="Aggregation('condpago','check-condpago');" value="NINGUNO">NINGUNO</li>
                        <li><input type="checkbox" class="check-condpago" onclick="Aggregation('condpago','check-condpago'); " value="15 DIAS">15 DIAS</li>
                        <li><input type="checkbox" class="check-condpago" onclick="Aggregation('condpago','check-condpago'); " value="30 DIAS">30 DIAS</li>
                        <li><input type="checkbox" class="check-condpago" onclick="Aggregation('condpago','check-condpago'); " value="45 DIAS">45 DIAS</li>
                    </td>
                    <td>
                        <li><input type="checkbox" class="check-condpago" onclick="Aggregation('condpago','check-condpago'); " value="60 DIAS">60 DIAS</li>
                        <li><input type="checkbox" class="check-condpago" onclick="Aggregation('condpago','check-condpago'); " value="90 DIAS">90 DIAS</li>
                        <li><input type="checkbox" class="check-condpago" onclick="Aggregation('condpago','check-condpago'); " value="120 DIAS">120 DIAS</li>
                    </td>
                </tr>
            </table>
            
        </ul>
    </div>

    <?php
  include_once '../dependencias/php/footer.php';
?>
    <script type="text/javascript" src="../dependencias/js/Trazabilidad/Revpreeliminar.js?v=1.0.0"></script>
    <script type="text/javascript" src="../dependencias/js/Moneda.js"></script>
    <script>
    $(function() {

        listaEmpleado('<?php echo base64_encode($resRev["fksucursal"])?>',
            '<?php echo base64_encode($resRev["fkeventas"])?>',
            '<?php echo base64_encode($resRev["fkecalidad"])?>',
            '<?php echo base64_encode($resRev["fkeproduccion"])?>'
        );

        Loadusercustomer('<?php echo base64_encode($resRev['fkcliente']) ?>',
            '<?php echo base64_encode($resRev['fkusercli']) ?>');

    });

    Activo();

    let area = document.querySelectorAll(".cajas-texto")

    window.addEventListener("DOMContentLoaded", () => {
        area.forEach((elemento) => {
            elemento.style.height = `${elemento.scrollHeight}px`
        })
    })
    </script>