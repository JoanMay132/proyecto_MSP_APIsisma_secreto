<?php @session_start(); {$title = '- ALTA COTIZACIÓN';} //TITULO DE LA PAGINA
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
  $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");

  $rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::cotizacion->value);
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
    
    /*
    table .form-control:focus{
        border: 1px solid #80bdff;
    }*/

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
    td:focus-within {
        border: 1.7px solid #b9ddea ;
    }
</style>

<script>
    function popup (URL){ 
        window.open(URL,"","width=600,height=700,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
    } 
    function servicios (URL){ 
        window.open(URL,"","width=800,height=800;scrollbars=yes,left=900,addressbar=0,menubar=0,toolbar=0");
    } 
    function Area (URL){ 
        window.open(URL,"","width=500,height=600;scrollbars=yes,left=500,top=130,addressbar=0,menubar=0,toolbar=0");
    } 
  </script>


  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">
    
    <form id="form-cotizacion">
        <!-- INCIO DE LA PRIMERA FILA -->
        <div class="row">
               <label class="txt-11 text-secondary col-12 col-sm-1">FOLIO</label>
               <div class="txt-11 col-12 col-sm-1" style="padding:5px;display:flex"><p id="displayFolio" style="color:red;">VACIO</p></div>

               <input type="hidden" id="folio" name="folio" value="">

               <label for="sucursal" class="txt-11 text-secondary col-12 col-sm-1">SUCURSAL</label>
               <select onchange="return Sucursal(this.value);"  name="sucursal" id="sucursal"  class="form-control col-12 col-sm-2" required >
                        <option value=""></option>
                        <?php foreach($obsuc->GetDataAll() as $sucursal){ 
                                if(in_array($sucursal['pksucursal'],$rol->getBranch())){
                            ?>
                        <option value="<?php  echo base64_encode($sucursal['pksucursal']); ?>"><?php echo $sucursal['nombre']; ?></option>
                        <?php } } ?>
                </select>

                <label for="fecha" class="txt-11 text-secondary col-12 col-sm-1">FECHA</label>
                <input type="date" name="fecha" id="fecha" required class="form-control col-12 col-sm-2"  >
                <label for="listRevision" class="txt-11 text-secondary col-sm-1">REVISIÓN</label>
                <select onchange="return dataRevision(this.value)" name="revision" class="form-control col-sm-2" id="listRevision"></select>

                
               
            
        </div>
        <!-- 
        FIN DE LA PRIMERA FILA    
        INICIO DE LA SEGUNDA FILA -->
        <div class="row">
            <!-- Inicio de la primer columna -->
            <div class="col-12 col-sm-4">
                <div class="form-group row">
                    <label for="listClientes" class="txt-11 text-secondary col-12 col-sm-3">CLIENTE:</label>
                    <select name="cliente" id="listClientes" required class="form-control col-12 col-sm-9 " onchange="return usercustomer(this.value);" >
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row">
                    <label for="listUsercustomer" class="txt-11 text-secondary col-12 col-sm-3">SOLICITO:</label>
                    <select name="solicito" class="form-control col-8 col-sm-8 listUsercustomer"   id="listUser">
                    </select>
                    <div class="col-1 col-sm-1 text-center" style="padding:0">
                        <button type="button"  id="buttonUser" title="Agregar usuario" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px" class="fa fa-user-plus"></i></button>
                    </div>
                    
                </div>
                <div class="form-group row">
                    <label for="attn" class="txt-11 text-secondary col-12 col-sm-3">ATTN:</label>
                    <select name="attn" onchange="return viewDepto(this.value);" class="form-control col-12 col-sm-9 listUsercustomer" id="attn">
                    </select>
                </div>
                <div class="form-group row">
                    <label for="titulo" class="txt-11 text-secondary col-12 col-sm-3">TITULO:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control col-12 col-sm-9" >
                </div>
 
            </div>  
            <!-- FIN DE LA PRIMER COLUMNA
                INICIO DE LA SEGUNDA COLUMNA -->
            <div class="col-12 col-sm-4">
                <div class="form-group row">
                    <label for="cotizo" class="txt-11 text-secondary col-12 col-sm-4">COTIZO:</label>
                    <select name="cotizo" class="form-control col-8 col-sm-8 listEmployee" id="cotizo">  
                    </select>               
                </div>
                <div class="form-group row">
                    <label for="cotizo" class="txt-11 text-secondary col-12 col-sm-4">RESPONSABLE:</label>
                    <select name="responsable" class="form-control col-8 col-sm-8 listEmployee" id="responsable" >  
                    </select>               
                </div>
                <div class="form-group row" >
                    <label for="vigencia" class="txt-11 text-secondary col-12 col-sm-4">VIGENCIA:</label>
                    <select name="vigencia" class="form-control col-8 col-sm-8" id="vigencia">
                            <option></option>
                            <option value="1 DIA">1 DIA</option>
                            <?php for($i= 2 ; $i <= 30; $i++){
                                echo "<option value='".$i." DIAS'>".$i." DIAS</option>";
                            } ?>
                    </select>
                </div>
                <div class="form-group row" >
                    <label for="ocompra" class="txt-11 text-secondary col-12 col-sm-4">ORDEN COMP:</label>
                    <input type="text" name="ocompra" id="ocompra" class="form-control col-12 col-sm-8 ">
                </div>
 
            </div>
            <!-- FIN DE LA SEGUDA COLUMNA
                INICIO DE LA TERCER COLUMNA -->
                 <div class="col-12 col-sm-4">
                    <div class="form-group row" >
                            <label for="fpago" class="txt-11 text-secondary col-12 col-sm-4">F. PAGO</label>
                                <select name="fpago"  class="form-control col-sm-4" id="fpago">
                                    <option value="CREDITO">CREDITO</option>
                                    <option value="CONTADO">CONTADO</option>
                                </select>
                            
                    </div>
                    <div class="form-group row">
                            <label for="credito" class="txt-11 text-secondary col-12 col-sm-4">DIAS CREDITO</label>
                            <input type="text" name="credito" id="credito" class="form-control col-12 col-sm-8" >
                        </div>
                        <div class="form-group row">
                            <label for="tentrega" class="txt-11 text-secondary col-12 col-sm-4">T. ENTREGA</label>
                            <input type="text" name="tentrega" id="tentrega"  class="form-control col-12 col-sm-8 " >
                        </div>   
                </div>
            <!-- FIN DE LA TERCERA COLUMNA -->
        </div> 
        <!-- FIN DE LA SEGUNDA FILA -->
       
         <!--   INICIO DE LA CUARTA FILA-->
        <div class="row" >
            <!-- Inicio de la primer columna -->
            <div class="col-12 col-sm-4">
                <div class="form-group row">
                    <label for="lab" class="txt-11 text-secondary col-12 col-sm-3">L.A.B:</label>
                    <input type="text" name="lab" id="lab" class="form-control col-12 col-sm-9" >
                </div>
                <div class="form-group row" >
                    <label for="garantia" class="txt-11 text-secondary col-12 col-sm-3">GARANTIA:</label>
                    <input type="text" name="garantia" id="garantia" class="form-control col-12 col-sm-9" >

                    
                </div>
                <div class="form-group row" >
                    <label for="costo" class="txt-11 text-secondary col-12 col-sm-3">COSTO:</label>
                    <input type="text" name="fcosto" id="costo" class="form-control col-12 col-sm-9" >
                </div>
                
                <div class="form-group row">
                    <label for="area" class="txt-11 text-secondary col-12 col-sm-3">AREA:</label>
                    <select  name="area" id="area" required class="form-control col-12 col-sm-8" >
                    </select>
                    <div class="col-1 col-sm-1" style="padding:0">
                        <button type="button"  id="buttonArea" title="Agregar nueva area" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px;;" class="fa fa-list-alt"></i></button>
                    </div>
                </div>
                <div class="form-group row" >
                    <label for="deptouser" class="txt-11 text-secondary col-12 col-sm-3">CARGO:</label>
                    <input type="text" name="cargo" id="deptouser" class="form-control col-12 col-sm-9 ">
                </div>
                
                
            </div>
            <!-- Fin de la primer columna
                Inicio de la segunda columna -->
            <div class="col-12 col-sm-4">
                <!-- <div class="form-group row" >
                    <label for="datnormativos" class="txt-11 text-secondary col-12 col-sm-4">DATOS NORMATIVOS:</label>
                    <textarea name="datnormativos" id="datnormativos" class="form-control col-12 col-sm-8 scrollHidden" style="height:35px"></textarea>
                </div> -->
                <div class="form-group row">
                    <label for="fabricacion" class="txt-11 text-secondary col-12 col-sm-4">ESTANDARES DE FABRICACIÓN:</label>
                    <textarea id="fabricacion" name="fabricacion" class="form-control col-12 col-sm-8 scrollHidden" style="height:35px;" onfocus="mostrarScroll('fabricacion')" onblur="ocultarScroll('fabricacion')">API SPEC 7.1 / API SPEC 7.2 ULTIMA EDICION</textarea>
                </div>
                <div class="form-group row" >
                    <label for="pcalidad" class="txt-11 text-secondary col-12 col-sm-4">PROCESOS DE CALIDAD:</label>
                    <textarea id="pcalidad" name="pcalidad" class="form-control col-12 col-sm-8 scrollHidden" style="height:35px" onfocus="mostrarScroll('pcalidad')" onblur="ocultarScroll('pcalidad')">API Q1, ISO 9001 ULTIMA EDICION</textarea>
                </div>
            </div>
            <div class="col-12 col-sm-4"style="margin-top:-19px" >
                <!-- <div class="form-group row"  >
                    <label for="dattecnicos" class="txt-11 text-secondary col-12 col-sm-4">DATOS TECNICOS:</label>
                    <textarea id="dattecnicos" name="dattecnicos" class="form-control col-12 col-sm-8 scrollHidden" style="height:35px" onfocus="mostrarScroll('dattecnicos')" onblur="ocultarScroll('dattecnicos')" ></textarea>
                 
                </div> -->
                <div class="form-group row">
                    <label for="doclegal" class="txt-11 text-secondary col-12 col-sm-4">DOCUMENTACIÓN LEGAL:</label>
                    <textarea id="doclegal" name="doclegal" class="form-control col-12 col-sm-8 scrollHidden" style="height:35px" onfocus="mostrarScroll('doclegal')" onblur="ocultarScroll('doclegal')"></textarea>
                </div>
                
                <div class="form-group row">
                    <label for="factura1" class="txt-11 text-secondary col-12 col-sm-4">FACTURA:</label>
                    <input type="text" name="factura1" id="factura1" class="form-control col-12 col-sm-8 ">
                </div>
                
                <div class="form-group row">
                    <label for="estado" class="txt-11 text-secondary col-sm-4">ESTADO</label>
                    <select  name="estado" class="form-control col-sm-8"  id="estado">
                                <option value="AUTORIZACION PENDIENTE" >AUTORIZACION PENDIENTE</option>
                                <option value="NO AUTORIZADA">NO AUTORIZADA</option>
                                <option value="AUTORIZADA SIN OC<">AUTORIZADA SIN OC</option>
                                <option value="AUTORIZADA CON OC">AUTORIZADA CON OC</option>
                                <option value="ENTREGADA SIN OC" >ENTREGADA SIN OC</option>
                                <option value="ENTREGADA CON OC">ENTREGADA CON OC</option>
                    </select>
                </div>
                
                
            </div>
        </div>
        <!-- FIN DE LA CUARTA FILA -->
          
              
         <!-- INICO DE LA FILA Y TABLA DE SERVICIOS -->
        <div class="row" >
            <div class="col-12" style="padding:2px">
                <div style="overflow:auto;height: 41vh;background:white;border:2px solid grey" class="scroll table-responsive">
                  
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
                        
                          <tr style="max-height: 80px;" ondblclick='addServcot(<?php echo json_encode($data); ?>,undefined,<?php echo json_encode($tipo); ?>)' id="serv-0">
                            <input type="hidden" name="fkcatserv[]" id="fkcatservNew-0">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm"  autocomplete="off"></td>
                            <td valign="top"><input type="number" name="cant[]" min="0.00" step="0.01" id="cantidadNew-0" onblur="Subtotal(this); totales();" class="form-control form-control-sm" data-pre="cantidad" autocomplete="off"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><textarea id="descNew-0" name="descripcion[]" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;" data-servicio='servicios?row=0&new=true' oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>
                            <td valign="top" >
                                <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                                        <option value=""></option>
                                        <?php   
                                                foreach ($tipo as $value) {
                                                    $selTipo = $res['tipotrabajo'] == $value ? 'selected' : '';
                                                    echo "<option ".$selTipo." value='".$value."'>".$value."</option>";
                                                }
                                        ?>
                                   
                                </select>
                            </td>
                            <td valign="top"><input type="text" id="costoNew-0" onchange="moneda(this);"  onblur="Subtotal(this); totales();"  name="costo[]"  class="form-control form-control-sm <?php echo $viewCosto; ?>" autocomplete="off" data-pre="costo"></td>

                            <td valign="top"><input name="subtotal[]" type="text" id="subtotalNew-0" data-pre="subtotal" readonly class="form-control form-control-sm subtotal <?php echo $viewCosto; ?>" autocomplete="off"></td>

                            <td valign="top"><textarea id="" name="clave[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" oninput="autoResize(this);" spellcheck="false"></textarea></td>

                            <td valign="top"><input id="itemNew-0"  name="item[]" type="text"  class="form-control form-control-sm" autocomplete="off"></td>
                        </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- FIN DE SERVICIOS -->

        <div class="row">
            <div class="col-12 col-sm-2">
                <div class="cont-border subcotizacion" style="overflow:auto;max-height: 120px;">
                    <div class="form-inline">
                        <button  type="button"  id="buttonSubcot" title="Agregar subcotizacion" class="btn btn-dark"  style="width:14px;height:14px;position:relative;padding:0px;border-radius:0px;font-size:10px;color:white"><span style="position:absolute;left:0px;right:0px;bottom:0px;top:0px;margin:0 auto;font-size:13px;color:white;" class="fa fa-plus"></span></button>
                        <input type="text" ondblclick="addSubcotizacion();" name="sub[]" class="form-control form-control-sm txt-11" style="height:20px;font-size:11px;padding:2px;width:50px;">
                        <div class="price text-right" style="width:65px;background:white"><p  style="margin-top:3px" class="txt-11">$0.00</p></div>
                    </div>

                </div><br>
                <div class="form-inline float-right">
                        
                        <div class="text-right"><p class="txt-11" style="font-weight:bold;color:darkblue">TOTAL:</p></div>
                        <div class="price text-right" style="background:white"><p style="margin-top: 3px;" class="txt-11">$0.00</p></div>
                    </div>
                <div class="form-inline float-right">
                    
                    <div class="text-right"><p class="txt-11" style="font-weight:bold;color:darkblue">DIFERENCIA:</p></div>
                    <div class="price text-right" style="background:white"><p style="margin-top: 3px;" class="txt-11">$0.00</p></div>
                </div>
                
            </div>

            <div class="col-12 col-sm-7" style="padding: unset;">
                <div class="row" >
                    <div class="col-12 col-sm-4">
                        <div class="form-group row">
                            <label for="factura2" class="txt-11 text-secondary col-12 col-sm-5">FACTURA</label>
                            <input type="text" autocomplete="off" name="factura2" id="factura2" class="form-control col-12 col-sm-7">
                        </div>
                        <div class="form-group row" >
                            <label for="ffactura" class="txt-11 text-secondary col-12 col-sm-7">FECHA FACTURA</label>
                            <input type="date" autocomplete="none" name="ffactura" id="ffactura" class="form-control col-12 col-sm-5">
                        </div><hr>
                    </div>
                    <div class="col-12 col-sm-5" style="margin-top: -5px;">
                        
                            <label for="observaciones" class="txt-11 text-secondary ">OBSERVACIONES</label>
                            <textarea class="form-control col-12 scrollHidden" id="observaciones" name="observaciones" onfocus="mostrarScroll(this.id)" onblur="ocultarScroll(this.id)" style="margin-top:-10px" rows="3"></textarea><br>
                            <div class="row">
                                <div class="col-6" style="padding:0px">
                                        <button  class="btn btn-sm btn-outline-success" id="guardar" style="white-space:normal;word-wrap:break-word" ><span class="fa fa-print"></span> IMP. COTIZACION SIN IVA</button>
                                    </div>
                                    
                                    <div class="col-6" style="padding:0px">
                                        <button class="btn btn-sm btn-outline-warning" id="printIva" style="word-wrap:break-word"><span class="fa fa-print"></span> IMP. COTIZACION</button>
                                    </div>
                            </div>
                    </div>
                    <div class="col-12 col-sm-2" style="padding:0px;margin-top: -5px;">
                        <div class="form-group">
                                <label for="cnacional" class="txt-11 text-secondary">CONTENIDO NACIONAL</label><br>
                                <input type="text" autocomplete="off" name="cnacional" id="cnacional" class="form-control" style="margin-top:-10px"  value="$0.00">

                                <label for="pcns" class="txt-11 text-secondary">PCNS</label><br>
                                <input type="number" step="000.0001" autocomplete="off" name="pcns" id="pcns" class="form-control" style="margin-top:-10px"  value="0">

                                <label for="pcns" class="txt-11 text-secondary">MONEDA:</label><br>
                                <select name="tmoneda" id="tagmoneda" onchange="Cambio();" required  class="form-control" style="margin-top:-10px">
                                    <option value="NACIONAL">NACIONAL</option>
                                    <option value="DOLAR">DOLARES USA</option>
                                </select>

                                <label for="cambio" id="tagCambio" class="txt-11 text-secondary dolar" style="display:none">TIPO DE CAMBIO:</label>
                                <input type="number" step="0.0001"  onchange="totales();" autocomplete="off" name="tcambio" id="cambio" class="form-control dolar apiCambio" style="display:none">
                                
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-3" >
                <div class="cont-border" >
                    <div class="row">
                            <div class="col-12 col-sm-5"><p onclick="totales()" class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">SUBTOTAL:</p></div>
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
                            <div class="col-12 col-sm-5"><p class="txt-11" style="font-weight:bold;color:darkblue;margin-top:4px">IVA:</p></div>
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
        <!-- FIN DE FOOTER COTIZACION -->
        
        </form>
    </main>

    <div id="menu" class="context-menu">
        <ul>
        <li><a id="presupuesto"><i class="fa fa-dollar fa-lg" style="color: green;margin-right:10px"></i>PRESUPUESTAR</a></li>
            <li><a id="servicio" ><i class="fa fa-plus-square-o fa-lg" style="color:blue;margin-right:10px"></i>AGREGAR SERVICIO</a></li>
            <li><a id="eliminar" ><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR REGISTRO</a></li>
        </ul>
    </div>
    
<?php
  include_once '../dependencias/php/footer.php';
?>

<script type="text/javascript" src="../dependencias/js/Trazabilidad/Cotizacion.js?v=1.0.4"></script>
<script type="text/javascript" src="../dependencias/js/Trazabilidad/Presupuesto.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        var men = $("#menu");
        men.hide();
        
        });

        $(document).ready(function () {
            if(window.opener){
                window.opener.close();
            }
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
