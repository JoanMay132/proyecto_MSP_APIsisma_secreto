<?php @session_start(); {$title = '- SUBCOTIZACION';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  
  spl_autoload_register(function($class){
    include_once "../controlador/".$class.".php";
});
 //Recibe el id del la subcotizacion
 $idCotizacion = (int) base64_decode($_GET['cot']);
 if(!filter_var($idCotizacion,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}
$popper = true;

$obsuc = new Sucursal();
$obunidad = new Unidad();
$obcli = new Cliente();
$cot = new Subcotizacion();
$oUsercli = new Usercli();
$oDepto = new Deptocli();

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
  }

    //Consultamos la cotizacion
    $resCot =  $cot->GetData($idCotizacion);

    //Consulta los usuarios de los clientes
    $resuser = $oUsercli->GetDataAll($resCot['fkcliente']);

    $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");

    $rol->getPermissionControl($_SESSION['controles'],Controls::cotizacion->value,$resCot['fksucursal']);//Se verifica las operaciones
    $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
    $viewCosto = !in_array(Operacion::costo->value,$rol->getOperacion()) ? 'no-cost' : '';
   ?>

<script>
    function popup (URL){ 
        window.open(URL,"","width=500,height=auto,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
    } 
    function servicios (URL){ 
        window.open(URL,"","width=800,height=800;scrollbars=yes,left=900,addressbar=0,menubar=0,toolbar=0");
    } 
    function Area (URL){ 
        window.open(URL,"","width=500,height=450;scrollbars=yes,left=500,top=130,addressbar=0,menubar=0,toolbar=0");
    } 
    function Presupuesto(URL)
{
    const width = screen.availWidth;
    const height = screen.availHeight;

    window[name] ? window[name].focus() : window.open(URL, "PRESUPUESTO", `width=${width},height=${height},scrollbars=yes,left=200,addressbar=0,menubar=0,toolbar=0}`);
  
}
  </script>

<style>
     body::-webkit-scrollbar{
    width: 0px; /* Ancho de la barra de desplazamiento */
    height: 8px;
    }
    body{
        background:#D6EAF8   !important;
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
    table td .form-control{  
        /* font-weight: bold; */
        color: #1C1C1C !important;
    }
    textarea{
        resize: none !important;
    }
 
</style>
  <div class="main-wrapper">
  <center><div class="loader"><h3>Cargando subcotización...</h3></div></center>
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">
    
    <form id="form-subcotizacion">
        <!-- INCIO DE LA PRIMERA FILA -->
        <input type="hidden" name="pksubcotizacion" value="<?php echo base64_encode($resCot['pksubcotizacion']); ?>">
        <input type="hidden" name="pkcotizacion" value="<?php echo base64_encode($resCot['fkcotizacion']); ?>">
        
        <div class="row" style="padding:2px;margin-top:-10px">
               <label class="txt-11 text-secondary col-12 col-sm-1">FOLIO</label>
               <div class="txt-12 col-12 col-sm-1" style="padding:5px;display:flex"><p id="displayFolio" style="color:red;"><?php echo $resCot['folio']; ?></p></div>
               <input type="hidden" id="folio" name="folio" value="<?php echo $resCot['folio'] ?>">

               <label for="sucursal" class="txt-11 text-secondary col-12 col-sm-1">SUCURSAL</label>
               <select  name="sucursal" id="sucursal"  class="form-control form-control-sm col-12 col-sm-2" style="height:20px;font-size:11px;padding:unset" >
               <option selected value="<?php echo base64_encode($resCot['fksucursal']); ?>" ><?php echo $resCot['nombre'] ?></option>
                </select>

                <label for="fecha" class="txt-11 text-secondary col-12 col-sm-1">FECHA</label>
                <input type="date" name="fecha" id="fecha" required class="form-control form-control-sm col-12 col-sm-2" value="<?php echo $resCot['fecha'] ?>" style="height:20px;font-size:11px" >
               
            
        </div>
        <!-- 
        FIN DE LA PRIMERA FILA    
        INICIO DE LA SEGUNDA FILA -->
        <div class="row" style="margin-top: 6px">
            <!-- Inicio de la primer columna -->
            <div class="col-12 col-sm-4">
                <div class="form-group row" style="padding:2px;margin-top:-14px">
                    <label for="listClientes" class="txt-11 text-secondary col-12 col-sm-3">CLIENTE:</label>
                    <select name="cliente" id="listClientes" required class="form-control txt-11 col-12 col-sm-9 " onchange="return usercustomer(this.value);" style="height:20px;font-size:11px;padding:0px">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                    <label for="listUsercustomer" class="txt-11 text-secondary col-12 col-sm-3">SOLICITO:</label>
                    <select name="solicito" class="form-control form-control-sm col-8 col-sm-8 listUsercustomer" data-id="solicito"  style="width:100%;height:20px;font-size:11px;padding:0px" id="listUser">
                            <option value=""></option>
                            <?php foreach ($resuser as $value) {
                                $selecUser = $value['pkusercli'] == $resCot['fkusercli'] ? 'selected' : '';
                               echo "<option ".$selecUser." value='".base64_encode($value['pkusercli'])."'>".$value['titulo'].' '.$value['nombre']."</option>";
                            } ?>
                    </select>
                    <div class="col-1 col-sm-1 text-center" style="padding:0">
                        <button type="button"  id="buttonUser" title="Agregar usuario" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px" class="fa fa-user-plus"></i></button>
                    </div>
                    
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-25px">
                    <label for="attn" class="txt-11 text-secondary col-12 col-sm-3">ATTN:</label>
                    <select name="attn" onchange="return viewDepto(this.value);" class="form-control form-control-sm col-8 col-sm-8 listUsercustomer"  style="width:100%;height:20px;font-size:11px;padding:0px" id="listUser">
                            <option value=""></option>
                    <?php foreach ($resuser as $value) {
                                 $selecAttn = $value['pkusercli'] == $resCot['fkattnusercli'] ? 'selected' : '';
                               echo "<option ".$selecAttn." value='".base64_encode($value['pkusercli'])."'>".$value['titulo'].' '.$value['nombre']."</option>";
                            } ?>
                    </select>
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                    <label for="titulo" class="txt-11 text-secondary col-12 col-sm-3">TITULO:</label>
                    <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($resCot['titulo']); ?>" class="form-control form-control-sm col-12 col-sm-9" style="height:20px;font-size:11px;padding:3px">
                </div>
 
            </div>  
            <!-- FIN DE LA PRIMER COLUMNA
                INICIO DE LA SEGUNDA COLUMNA -->
            <div class="col-12 col-sm-4">

                <div class="form-group row" style="padding:2px;">
                    <label for="cotizo" class="txt-11 text-secondary col-12 col-sm-4">COTIZO:</label>
                    <select name="cotizo" class="form-control form-control-sm col-8 col-sm-8 listEmployee"  style="width:100%;height:20px;font-size:11px;padding:0px" id="cotizo">
                  
                        
                    </select>
                    
                </div>
                <div class="form-group row" style="margin-top:-25px">
                    <label for="cotizo" class="txt-11 text-secondary col-12 col-sm-4">RESPONSABLE:</label>
                    <select name="responsable" class="form-control col-8 col-sm-8 listEmployee" id="responsable" style="width:100%;height:20px;font-size:11px;padding:0px">  
                    </select>               
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-25px">
                    <label for="vigencia" class="txt-11 text-secondary col-12 col-sm-4">VIGENCIA:</label>
                    <select name="vigencia" class="form-control form-control-sm col-8 col-sm-8"  style="width:100%;height:20px;font-size:11px;padding:0px" id="vigencia">
                            <option></option>
                            <option>1 DIA</option>
                            <?php for($i= 2 ; $i <= 30; $i++){
                                $selVigencia = $i.' DIAS' == $resCot['vigencia'] ? 'selected' : '';
                                echo "<option ".$selVigencia." >".$i." DIAS</option>";
                            } ?>
                    </select>
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                    <label for="ocompra" class="txt-11 text-secondary col-12 col-sm-4">ORDEN COMP:</label>
                    <input type="text" name="ocompra" id="ocompra" value="<?php echo $resCot['ocompra'] ?>" class="form-control form-control-sm col-12 col-sm-8 " style="height:20px;font-size:11px;padding:3px">
                </div>
 
            </div>
            <!-- FIN DE LA SEGUDA COLUMNA
                INICIO DE LA TERCER COLUMNA -->
                 <div class="col-12 col-sm-4">
                <div class="form-group row" style="padding:2px;margin-top:-13px">
                        <label for="fpago" class="txt-11 text-secondary col-12 col-sm-4">F. PAGO</label>
                            <select name="fpago"  class="form-control form-control-sm col-sm-4" style="height:20px;font-size:11px;padding:0px" id="fpago">
                            <?php
                                $sCred = $resCot['formpago'] == 'CREDITO' ? 'selected' : '';
                                $sCont= $resCot['formpago'] == 'CONTADO' ? 'selected' : '';
                            ?>
                                <option <?php echo $sCred ?> value="CREDITO">CREDITO</option>
                                <option <?php echo $sCont ?> value="CONTADO">CONTADO</option>
                            </select>
                        
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                        <label for="credito" class="txt-11 text-secondary col-12 col-sm-4">DIAS CREDITO</label>
                        <input type="text" name="credito" id="credito" value="<?php echo $resCot['dcredito']; ?>" class="form-control form-control-sm col-12 col-sm-8" style="height:20px;font-size:11px;padding:3px" >
                    </div>
                    <div class="form-group row" style="padding:2px;margin-top:-25px">
                        <label for="tentrega" class="txt-11 text-secondary col-12 col-sm-4">T. ENTREGA</label>
                        <input type="text" name="tentrega" id="tentrega" value="<?php echo $resCot['tiempoent']; ?>"  class="form-control form-control-sm col-12 col-sm-8 " style="height:20px;font-size:11px;padding:3px">
                    </div>
                
            </div>
           
            <!-- FIN DE LA TERCERA COLUMNA -->
            
           
            
        </div> 
        <!-- FIN DE LA SEGUNDA FILA -->
       
         <!--   INICIO DE LA CUARTA FILA-->
        <div class="row" style="margin-top:-25px">
            <!-- Inicio de la primer columna -->
            <div class="col-12 col-sm-4">
                <div class="form-group row" style="padding:2px;">
                    <label for="lab" class="txt-11 text-secondary col-12 col-sm-3">L.A.B:</label>
                    <input type="text" name="lab" id="lab" value="<?php echo $resCot['lab'] ?>" class="form-control form-control-sm col-12 col-sm-9" style="height:20px;font-size:11px;padding:3px">
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                    <label for="garantia" class="txt-11 text-secondary col-12 col-sm-3">GARANTIA:</label>
                    <input type="text" name="garantia" id="garantia" value="<?php echo $resCot['garantia'] ?>" class="form-control form-control-sm col-12 col-sm-9" style="height:20px;font-size:11px;padding:3px">

                    
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                <label for="costo" class="txt-11 text-secondary col-12 col-sm-3">COSTO:</label>
               <input type="text" name="fcosto"  id="costo" value="<?php echo $resCot['costo']; ?>" class="form-control form-control-sm col-12 col-sm-9" style="height:20px;font-size:11px;padding:3px">
                </div>
                
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                <label for="area" class="txt-11 text-secondary col-12 col-sm-3">AREA:</label>
               <select  name="area" id="area"  class="form-control form-control-sm col-12 col-sm-8" style="height:20px;font-size:11px;padding:0px" required>
                        <?php foreach ($oDepto->GetDataAll($resCot['fkcliente']) as $value) {
                                $selArea = $resCot['fkdeptocli'] == $value['pkdeptocli'] ? 'selected' : '';
                            echo "<option ". $selArea." value='".base64_encode($value['pkdeptocli'])."'>".$value['nombre']."</option>";
                        }
                        ?>
                </select>
                <div class="col-1 col-sm-1" style="padding:0">
                        <button type="button"  id="buttonArea" title="Agregar nueva area" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px;;" class="fa fa-list-alt"></i></button>
                    </div>
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-24px">
                    <label for="deptouser" class="txt-11 text-secondary col-12 col-sm-3">CARGO:</label>
                    <input type="text" name="cargo" id="deptouser" value="<?php echo $resCot['cargo'] ?>" class="form-control form-control-sm col-9 col-sm-9 " style="height:20px;font-size:11px;padding:3px">
                </div>
            </div>
            <!-- Fin de la primer columna
                Inicio de la segunda columna -->
            <div class="col-12 col-sm-4">
                <!-- <div class="form-group row" style="padding:2px;">
                    <label for="datnormativos" class="txt-11 text-secondary col-12 col-sm-4">DATOS NORMATIVOS:</label>
                    <textarea name="datnormativos" id="datnormativos"  class="form-control form-control-sm col-12 col-sm-8 txt-11" style="padding:3px;height:35px"><?php echo $resCot['dnormativos'] ?></textarea>
                </div> -->
                <div class="form-group row" style="padding:2px;margin-top:-26px">
                    <label for="fabricacion" class="txt-11 text-secondary col-12 col-sm-4">ESTAND. DE FABRICACIÓN:</label>
                    <textarea id="fabricacion" name="fabricacion" class="form-control form-control-sm col-12 col-sm-8 txt-11 scrollHidden" style="padding:3px;height:35px" onfocus="mostrarScroll('fabricacion')" onblur="ocultarScroll('fabricacion')"><?php echo $resCot['efabricacion']; ?></textarea>
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-26px">
                    <label for="pcalidad" class="txt-11 text-secondary col-12 col-sm-4">PROCESOS DE CALIDAD:</label>
                    <textarea id="pcalidad" name="pcalidad" class="form-control form-control-sm col-12 col-sm-8 txt-11 scrollHidden" style="padding:3px;height:35px" onfocus="mostrarScroll('pcalidad')" onblur="ocultarScroll('pcalidad')">API Q1, ISO 9001 ULTIMA EDICION</textarea>

                    
                </div>
            </div>
            <div class="col-12 col-sm-4" style="margin-top:-19px">
                <!-- <div class="form-group row" style="padding:2px;">
                    <label for="dattecnicos" class="txt-11 text-secondary col-12 col-sm-4">DATOS TECNICOS:</label>
                    <textarea id="dattecnicos" name="dattecnicos" class="form-control form-control-sm col-12 col-sm-8 txt-11" style="padding:3px;height:35px" onfocus="mostrarScroll('dattecnicos')" onblur="ocultarScroll('dattecnicos')" ><?php echo $resCot['dattecnicos']; ?></textarea>
                 
                </div> -->
                <div class="form-group row" style="padding:2px;margin-top:-26px">
                    <label for="doclegal" class="txt-11 text-secondary col-12 col-sm-4">DOCUMENTACIÓN LEGAL:</label>
                    <textarea id="doclegal" name="doclegal" class="form-control form-control-sm col-12 col-sm-8 txt-11" style="padding:3px;height:35px" onfocus="mostrarScroll('doclegal')" onblur="ocultarScroll('doclegal')"><?php echo $resCot['doclegal']; ?></textarea>

                    
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-26px">
                    <label for="factura1" class="txt-11 text-secondary col-12 col-sm-4">FACTURA:</label>
                    <input type="text" name="factura1" id="factura1" value="<?php echo $resCot['factura']; ?>" class="form-control form-control-sm col-12 col-sm-8 " style="height:20px;font-size:11px;padding:3px" >
                </div>
                
                <div class="form-group row" style="padding:2px;margin-top:-26px">
                    <label for="estado" class="txt-11 text-secondary col-sm-4">ESTADO</label>
                    <select  name="estado" class="form-control form-control-sm col-sm-8" style="height:20px;font-size:11px;padding:3px" id="estado">
                            
                            <?php $arrayEstado = array("AUTORIZACION PENDIENTE","NO AUTORIZADA","AUTORIZADA SIN OC","AUTORIZADA CON OC","ENTREGADA SIN OC","ENTREGADA CON OC"); 
                                    foreach ($arrayEstado as  $value) {
                                        $selEst = $resCot['estado'] == $value ? 'selected' : '';
                                        echo "<option ".$selEst." value='".$value."'>".$value."</option>";
                                    }
                            ?>
                                
                    </select>
                </div>
            </div>
        </div>
        <!-- FIN DE LA CUARTA FILA -->
          


        <!-- FIN DE DE LA TERCERA FILA -->
         <!-- INICO DE LA FILA Y TABLA DE SERVICIOS -->
        <div class="row" style="margin-top: -22px;">
            <div class="col-12" style="padding:2px">
                <div style="overflow:auto;height: 42vh;background:white;border:2px solid grey" class="scroll table-responsive">
                  
                    <table class="table-stripped table-bordered table-cotizacion" width="110%" id="table" style="background-color: white;font-size:11px;">
                        <thead style="position: sticky;top:0;z-index: 10;" class="table-info" >
                          <tr>
                            <th width="5%">PDA</th>
                            <th width="5%">CANT.</th>
                            <th width="6%">UNIDAD</th>
                            <th width="40%">DESCRIPCIÓN</th>
                            <th width="15%">TIPO TRABAJO</th>
                            <th width="7%">PRECIO</th>
                            <th width="7%">SUBTOTAL</th>
                            <th width="9%">CLAVE</th>
                            <th width="7%">ITEM</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="serv-cotizacion">
                            <?php $contador = 0;

                                foreach ($cot->GetDataAllServ($idCotizacion) as $res) { ?> 

                        <tr style="max-height: 80px;" ondblclick='addServcot(<?php echo json_encode($data); ?>,"<?php echo $viewCosto; ?>",<?php echo json_encode($tipo); ?>)' id="serv-0">
                            <input type="hidden" name="fkcatserv[]" id="fkcatserv-<?php echo $contador; ?>">
                            <input type="hidden" name="pkservcotizacion[]" value="<?php echo base64_encode($res['pksubservcot']); ?>">
                            <input type="hidden" name="contenidoReg[]" value="<?php echo $res['contenido']; ?>">
                            <td valign="top"><input name="pdaReg[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm"  autocomplete="off" value="<?php echo $res['pda'] ?>"></td>

                            <td valign="top"><input type="number" name="cantReg[]" data-pre="cantidad" id="cantidad-<?php echo $contador; ?>" onblur="Subtotal(this); totales(); CalculaContenido();" min="0.00" step="0.01" class="form-control form-control-sm" autocomplete="off" value="<?php echo $res['cant'] ?>"></td>

                            <td valign="top">
                                <select name="unidadReg[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){
                                             $selUn = $unidad['pkunidad'] == $res['fkunidad'] ? 'selected': '';
                                        ?>
                                        <option <?php echo $selUn; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>

                            <td><textarea id="descripcion-<?php echo $contador; ?>" name="descripcionReg[]" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:auto;"  onclick="menu(this,'true'); return false;" data-servicio='servicios?row=<?php echo $contador; ?>' oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descripcion-<?php echo $contador; ?>')" onblur="ocultarScroll('descripcion-<?php echo $contador; ?>')"><?php echo $res['descripcion']; ?></textarea></td>

                            <td valign="top" >
                                <select name="ttrabajoReg[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                                        <option value=""></option>
                                        <?php   
                                                foreach ($tipo as $value) {
                                                    $selTipo = $res['tipotrabajo'] == $value ? 'selected' : '';
                                                    echo "<option ".$selTipo." value='".$value."'>".$value."</option>";
                                                }
                                        ?>
                                    
                                   
                                </select>
                            </td>

                            <td valign="top"><input type="text" id="costo-<?php echo $contador; ?>" onchange="moneda(this);"  onblur="Subtotal(this); totales(); CalculaContenido();"  name="costoReg[]"  class="form-control form-control-sm <?php echo $viewCosto; ?>" autocomplete="off" data-pre="costo" value="$<?php  echo number_format($res["preciounit"], 2, '.', ','); ?>"></td>

                            <td valign="top"><input readonly name="subtotalReg[]" type="text" id="subtotal-<?php echo $contador;?>" class="form-control form-control-sm subtotal <?php echo $viewCosto; ?>" autocomplete="off" data-pre="subtotal" value="$<?php  echo number_format($res["subtotal"], 2, '.', ','); ?>"></td>

                            <td valign="top"><textarea id="" name="claveReg[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" oninput="autoResize(this);" spellcheck="false"><?php echo $res["clave"]; ?></textarea></td>

                            <td valign="top"><input id="item-<?php echo $contador; ?>"  name="itemReg[]" type="text"  class="form-control form-control-sm" autocomplete="off" value=" <?php echo $res["item"]; ?>"></td>
                        </tr>
                              <?php $contador++;  } //Fin de Foreach

                              if($contador == 0){
                            ?>
                        
                        <!-- INICO DE LOS NUEVOS REGISTROS -->
                          <tr style="max-height: 80px;" ondblclick='addServcot(<?php echo json_encode($data); ?>,"<?php echo $viewCosto; ?>",<?php echo json_encode($tipo); ?>)' id="serv-0">
                            <input type="hidden" name="fkcatserv[]" id="fkcatservNew-0">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm"  autocomplete="off"></td>
                            <td valign="top"><input type="number" name="cant[]" id="cantidadNew-0" onblur="Subtotal(this); totales();" min="0.00" step="0.01" class="form-control form-control-sm" data-pre="cantidad" autocomplete="off"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){
                                        ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><textarea id="descNew-0" name="descripcion[]" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;" data-servicio='servicios?row=0&new=true'  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>

                            <td valign="top" >
                                <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                                    <option value=""></option>
                                    <?php
                                    foreach ($tipo as $value) {
                                                    
                                                    echo "<option value='".$value."'>".$value."</option>";
                                                } ?>
                                   
                                   
                                </select>
                            </td>
                            <td valign="top"><input type="text" id="costoNew-0" onchange="moneda(this);"  onblur="Subtotal(this); totales();"  name="costo[]"  class="form-control form-control-sm <?php echo $viewCosto; ?>" data-pre="costo" autocomplete="off"></td>

                            <td valign="top"><input name="subtotal[]" type="text" id="subtotalNew-0" data-pre="subtotal" class="form-control form-control-sm subtotal <?php echo $viewCosto; ?>" autocomplete="off"></td>

                            <td valign="top"><textarea id="" name="clave[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" oninput="autoResize(this);" spellcheck="false"></textarea></td>

                            <td valign="top"><input id="itemNew-0"  name="item[]" type="text"  class="form-control form-control-sm" autocomplete="off"></td>
                        </tr> <?php } ?>
                        <!-- fin de los nuevos registros -->
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- FIN DE SERVICIOS -->

        <div class="row">


            <div class="col-12 col-sm-7 offset-2" style="padding: unset;">
                <div class="row" >
                    <div class="col-12 col-sm-4">
                        <div class="form-group row">
                            <label for="factura2" class="txt-11 text-secondary col-12 col-sm-5">FACTURA</label>
                            <input type="text" autocomplete="off" name="factura2" value="<?php echo $resCot['factura2']; ?>" id="factura2" class="form-control form-control-sm col-12 col-sm-7" style="height:20px;font-size:11px;padding:3px">
                        </div>
                        <div class="form-group row" style="margin-top:-20px">
                            <label for="ffactura" class="txt-11 text-secondary col-12 col-sm-7">FECHA FACTURA</label>
                            <input type="date" autocomplete="none" name="ffactura" value="<?php echo $resCot['fechafactura']; ?>" id="ffactura" class="form-control form-control-sm col-12 col-sm-5" style="height:20px;font-size:11px;padding:3px">
                        </div><hr>
                    </div>
                    <div class="col-12 col-sm-5" style="margin-top: -5px;">
                        
                            <label for="observaciones" class="txt-11 text-secondary ">OBSERVACIONES</label>
                            <textarea class="form-control form-control-sm col-12 scrollHidden" id="observaciones" name="observaciones" style="padding:3px;margin-top:-10px" rows="3"><?php echo $resCot['observacion']; ?></textarea><br>

                            <div class="row">

                            <?php if($modifica){ ?>
                                <div class="col-6" style="padding:0px">
                                    <button class="btn btn-sm btn-outline-success" id="printsub" style="white-space:normal;word-wrap:break-word" ><span class="fa fa-print"></span> IMP. COTIZACION SIN IVA</button>
                                </div>
                                
                                <div class="col-6" style="padding:0px">
                                    <button class="btn btn-sm btn-outline-warning" id="printsubIva" style="word-wrap:break-word"><span class="fa fa-print"></span> IMP. COTIZACION</button>
                                </div>
                            <?php }else{ ?>
                                <div class="col-6" style="padding:0px">
                                    <button type="button"  class="btn btn-sm btn-outline-success" onclick="return Print('<?php echo base64_encode($resCot['pksubcotizacion']); ?>',false,'subcotizacion','print_subcotizacion');" style="white-space:normal;word-wrap:break-word" ><span class="fa fa-print"></span> IMP. COTIZACION SIN IVA</button>
                                </div>
                                
                                <div class="col-6" style="padding:0px">
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="return Print('<?php echo base64_encode($resCot['pksubcotizacion']); ?>',true,'subcotizacion','print_subcotizacion');"style="word-wrap:break-word"><span class="fa fa-print"></span> IMP. COTIZACION</button>
                                </div>
                            <?php }?>
                                    
                                    
                            </div>
                            <div class="row">
    
                                
                                    
                                    
                            </div>
                    </div>
                    <div class="col-12 col-sm-2" style="padding:0px;margin-top: -5px;">
                        <div class="form-group">
                                <label for="cnacional" class="txt-11 text-secondary">CONTENIDO NACIONAL</label><br>
                                <input type="text" autocomplete="off" name="cnacional" id="cnacional" class="form-control form-control-sm <?php echo $viewCosto; ?>" style="height:20px;font-size:11px;padding:3px;margin-top:-10px" value="$<?php echo number_format($resCot['contenido'],2,".",","); ?>">

                                <label for="pcns" class="txt-11 text-secondary">PCNS</label><br>
                                <input type="number" step="0.001" autocomplete="off" name="pcns" id="pcns" class="form-control form-control-sm" style="height:20px;font-size:11px;padding:3px;margin-top:-10px" value="0">

                                <label for="pcns" class="txt-11 text-secondary">MONEDA:</label><br>
                                <select name="tmoneda" id="tagmoneda" onchange="Cambio();"  class="form-control txt-11 " style="height:20px;font-size:11px;padding:0px;margin-top:-10px">
                                                <?php $selNa = $resCot['moneda'] === 'NACIONAL' ? 'selected' : ''; 
                                                      $selDo = $resCot['moneda'] === 'DOLAR' ? 'selected' : '';?>
                                    <option <?php echo $selNa; ?> value="NACIONAL">NACIONAL</option>
                                    <option <?php echo $selDo; ?> value="DOLAR">DOLARES USA</option>
                                </select>

                                <label for="cambio" id="tagCambio" class="txt-11 text-secondary dolar" style="display:none">TIPO DE CAMBIO:</label>
                                <input type="number" onchange="totales();" autocomplete="off" name="tcambio" id="cambio" value="<?php echo $resCot['tipocambio']; ?>" class="form-control form-control-sm dolar" style="height:20px;font-size:11px;padding:3px;margin-top:-15px;display:none">
                                
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="col-12 col-sm-3" >
                <div class="cont-border" >
                    <div class="row">
                            <div class="col-12 col-sm-5"><p onclick="totales()" class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">SUBTOTAL:</p></div>
                            <div class="col-12 col-sm-5 price text-right float-right" style="background:white;margin-left:20px"><p id="subtotal" style="margin-top:3px;" class="txt-11 <?php echo $viewCosto; ?>">$0.00</p></div>
                            <input type="hidden" name="inputSubtotal" id="inputSubtotal">
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-3"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">DESCTO:</p></div>
                        <input type="number" autocomplete="off" name="descto" step="0.01" id="descto" class="form-control form-control-sm col-12 col-sm-2 " style="height:20px;font-size:10px;padding:3px;" value="<?php echo $resCot['descto']; ?>" onchange="totales();">
                        <div class="col-12 col-sm-5 price text-right float-right" style="background:white;margin-left:20px"><p id="tagDescto" style="margin-top:3px;" class="txt-11 <?php echo $viewCosto; ?>">$0.00</p></div>
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">SUBTOTAL:</p></div>
                            <div class="col-12 col-sm-5 price text-right" style="background:white;margin-left:20px"><p id="subtotaldesc" style="margin-top:3px;" class="txt-11 <?php echo $viewCosto; ?>">$0.00</p></div>
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">IVA:</p></div>
                            <div class="col-12 col-sm-5 price text-right" style="background:white;margin-left:20px"><p id="tagIva" style="margin-top:3px;" class="txt-11 <?php echo $viewCosto; ?>">$0.00</p></div>
                            <input type="hidden" name="iva" value="<?php echo $resCot['iva']; ?>" id="iva">
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">TOTAL:</p></div>
                            <div class="col-12 col-sm-5 price text-right" style="background:white;margin-left:20px"><p id="tagTotal" style="margin-top:3px;" class="txt-11 <?php echo $viewCosto; ?>">$0.00</p></div>
                            <input type="hidden" name="inputTotal" id="inputTotal">
                    </div>
                    <div class="row">
                            <div class="col-12 col-sm-5"><p class="txt-11 dolar" style="font-weight:bold;color:darkblue;display:none">DOLARES:</p></div>
                            <div class="col-12 col-sm-5 price text-right dolar" style="background:white;margin-left:20px;display:none"><p id="tdolar" style="margin-top:3px;" class="txt-11 <?php echo $viewCosto; ?>">$0.00</p></div>
                    </div>
                </div>

            </div>
        </div>
        <!-- FIN DE FOOTER COTIZACION -->
        
        </form>
    </main>

    <?php if($modifica){ ?>
    <div id="menu" class="context-menu">
        <ul>
            <li><a id="presupuesto"><i class="fa fa-dollar fa-lg" style="color: green;margin-right:10px"></i>PRESUPUESTAR</a></li>
            <li><a id="servicio" ><i class="fa fa-plus-square-o fa-lg" style="color:blue;margin-right:10px"></i>AGREGAR SERVICIO</a></li>
            <li><a id="eliminar" ><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR REGISTRO</a></li>
        </ul>
    </div>
    <?php } ?>
    
<?php
  include_once '../dependencias/php/footer.php';

  $dato = array(
    "revision" =>base64_encode( @$resCot["fkrevpreeliminar"]),
    "cliente" => base64_encode(@$resCot["fkcliente"])
  );


 
?>

<script type="text/javascript" src="../dependencias/js/Trazabilidad/Cotizacion.js"></script>
<script type="text/javascript" src="../dependencias/js/Trazabilidad/Presupuesto.js"></script>

<script>
   $(async function () {
        $('[data-toggle="tooltip"]').tooltip();

        var men = $("#menu");
        men.hide();
        
        //Carga la sucursal
        let sucursalSelect = document.getElementById('sucursal').value;
        await Sucursal(sucursalSelect,<?php echo json_encode($dato) ?>,"<?php echo base64_encode($resCot['fkecotizo']); ?>","<?php echo base64_encode($resCot['fkeresponsable']); ?>");

        setTimeout( () => {
            totales();
            Cambio();
            CalculaContenido();
        },500);

        let area = document.querySelectorAll(".cajas-texto");
    area.forEach((elemento) => {
        elemento.style.height = Math.min(elemento.scrollHeight, 150) + "px";
      })
        
    });

    window.addEventListener('beforeunload', function(event) {
       
           let ventana =  window.opener;

           if(ventana.location.href.includes("ecotizacion")){
            ventana.$('#displaySubcotizacion').load(ventana.location.href + ' .subcotizacion');
            ventana.CalculaSubcotizacion();//actualizas el div
           }
           event.returnValue = ''; 
    });
        /*
        Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Seleccione sucursal',
                    showConfirmButton: true,
                    width:'auto',
                    //timer: 1500,
                  });
        */
</script>

