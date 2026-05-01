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
  $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");

  $rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::ot->value);
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
    td:focus-within {
        border: 1.7px solid #b9ddea ;
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
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">
    
    <form id="form-orden">
        <div class="row">
            <div class="col-12 col-sm-5" >
                <div class="form-group row" >
                    <label for="listClientes" class="txt-11 text-secondary col-3 col-sm-3">CLIENTE</label>
                    <select name="cliente" id="listClientes" class="form-control col-9 col-sm-9" onchange="return usercustomer(this.value);" >
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row">
                    <label for="listUsercustomer" class="txt-11 text-secondary col-3 col-sm-3">SOLICITO</label>
                    <select name="solicito" id="listUsercustomer" onchange="return viewDepto(this.value);" class="form-control form-control-sm col-8 col-sm-8 listUsercustomer">
                        <option></option>
                        
                    </select>
                    <div class="col-1 col-sm-1 text-center" style="padding:0">
                        <button type="button"  id="buttonUser" title="Agregar usuario" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px" class="fa fa-user-plus"></i></button>
                    </div>
                    
                </div>
                <div class="form-group row" >
                    <label for="deptouser" class="txt-11 text-secondary col-3 col-sm-3">DEPARTAMENTO</label>
                    <input type="text" name="depto" id="deptouser" class="form-control form-control-sm col-9 col-sm-9">
                </div>
            </div>
            <div class="col-12 col-sm-4" >
                <div class="form-group row" >
                    <label for="sucursal" class="txt-11 text-secondary col-3 col-sm-3">SUCURSAL</label>
                    <select onchange="return Sucursal(this.value);"  name="sucursal" id="sucursal"  class="form-control form-control-sm col-9 col-sm-9" >
                        <option value=""></option>
                        <?php foreach($obsuc->GetDataAll() as $sucursal){ 
                                if(in_array($sucursal['pksucursal'],$rol->getBranch())){
                            ?>
                        <option value="<?php  echo base64_encode($sucursal['pksucursal']); ?>"><?php echo $sucursal['nombre']; ?></option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="form-group row" >
                    <label for="fecha" class="txt-11 text-secondary col-3 col-sm-3">FECHA</label>
                    <input type="date" required name="fecha" required id="fecha" class="form-control form-control-sm col-9 col-sm-9 " value="">
                </div>
                <div class="form-group row">
                    <label for="folio" class="txt-11 text-secondary col-3 col-sm-3">FOLIO</label>
                    <div class="txt-12 col-3 col-sm-3" style="padding:5px;display:flex"><p id="displayFolio" style="color:blue">SIN ASIGNAR</p>
                        <button type="button" onclick="return setFolio();"  id="btnfolio" title="Asignar folio" data-toggle="tooltip" class="btn btn-success"  style="width:100%;height:20px;position:relative;padding:0;margin-left:5px"><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;" class="fa fa-check-circle fa-sm"></i></button>
                    </div>
                    <label for="cotizacion" class="txt-11 text-secondary col-3 col-sm-3">COTIZACION</label>
                    <select  name="cotizacion" id="cotizacion" class="form-control form-control-sm col-3 col-sm-3" >
                        <option value=""></option>
                        
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-3" >
                <div class="form-group row" style="margin-left:15px">
      
                        <button type="submit" class="btn btn-info btn-sm" style="border-radius:0px;" name="imprimir" id="imprimir"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                    
                    
                     <button type="submit" class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar" id="guardar"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
                </div>
                    

            </div>

        </div>

        <div class="row">
            <div class="col-12" style="padding:2px">
                <div style="height: 60vh;background:white;border:2px solid grey;overflow:auto" class="scroll table-responsive">
                <table class="table-bordered table-ot" width="100%" id="table" style="background-color: white;font-size:11px; border-collapse:collapse;">
                        <thead style="position: sticky;top:0;z-index: 10;" class="table-info" >
                          <tr>
                            <th width="5%">PDA</th>
                            <th width="5%">CANT.</th>
                            <th width="6%">UNIDAD</th>
                            <th width="45%">DESCRIPCIÓN</th>
                            <th width="15%">TIPO TRABAJO</th>
                            <th width="9%">DIBUJO</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="serv-ot">                  
                          <tr style="max-height: 80px;" ondblclick='addServot(<?php echo json_encode($data); ?>,<?php echo json_encode($tipo); ?>)' id="serv-0">
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>
                            <td valign="top"><input type="number" name="cant[]"  min="0" step="1" class="form-control form-control-sm text-center"  autocomplete="off"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm text-center">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><textarea name="descripcion[]" id="descNew-0" class="form-control form-control-sm cajas-texto" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>
                            <td valign="top" >
                                <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                                        <option value=""></option>
                                        <?php   
                                                foreach ($tipo as $value) {
                                                   // $selTipo = $res['tipotrabajo'] == $value ? 'selected' : '';
                                                    echo "<option value='".$value."'>".$value."</option>";
                                                }
                                        ?>
                                   
                                </select>
                            </td>
                            <td valign="top"><textarea id="" name="dibujo[]" class="form-control" autocomplete="off" oninput="autoResize(this);" spellcheck="false" style="resize:none;height:30px;"></textarea></td>

                        </tr>
                            
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <!--  
                <div class="col-4 col-sm-2">
                    
                    <div  style="overflow:auto;max-height: 120px;" class="cont-border scroll suborden-add">
                        <p class="txt-11 text-secondary text-center" style="margin-bottom:10px">SUB ORDENES</p>
                            <div class="form-inline">
                                <button  type="button"  id="buttonSubcot" title="Agregar suborden de trabajo" class="btn btn-dark"  style="width:13px;height:13px;position:relative;padding:0px;border:none;border-radius:0px"><span style="position:absolute;left:0px;right:0px;bottom:0px;top:0px;margin:0 auto;font-size:13px;color:white;" class="fa fa-plus"></span></button>
                                <input type="text" ondblclick="addSuborden();" name="sub[]" class="form-control form-control-sm txt-11" style="height:20px;font-size:11px;padding:3px;width:80px;">
                            
                        </div>

                    </div>
                </div> -->
                <div class=" offset-1 col-8 col-sm-6 form-group ">
                       <center> <label for="observaciones" class="txt-11 text-secondary">REQUERIMIENTOS ESPECIALES / OBSERVACIONES</label></center>
                       <textarea class="form-control top-8" id="observaciones" name="observaciones" rows="3"></textarea>
                       <div class="row " >
                            <div class="col-12 col-sm-6 form-group" >
                                <div class="text-center" >
                                        <label for="auxiliar" class="txt-11 text-secondary">AUXILIAR DE PRODUCCIÓN</label>
                                        <select id="auxiliar" name="auxiliar" class="form-control form-control-sm listEmployee top-8" >
                                            <option></option>
                                        </select> 
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 form-group" >
                                <div class="text-center" >
                                        <label for="enterado" class="txt-11 text-secondary">ENTERADO</label>
                                        <select id="enterado" name="enterado"  class="form-control form-control-sm listEmployee top-8">
                                            <option></option>
                                        </select> 
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4" >
                    <div class="row form-group">
                            <label for="diaentrega" class="txt-11 text-secondary col-5">DIAS PROGRAMADOS DE ENTREGA</label>
                            <input type="number" id="diaentrega" name="diaentrega" class="form-control form-control-sm col-3 text-center">
                    </div><br>
                    <div class="row form-group" style="border:1px solid grey;margin-left:5px;margin-right:5px;">
                                                
                                     <label for="urgente" class="txt-11 text-secondary col-2">URGENTE</label>
                                    <input type="radio" name="tipo" value="urgente" id="urgente" class="form-control form-control-sm col-3 top-3" >
                                    <label class="txt-11 text-secondary col-2" for="normal">NORMAL</label>
                                    <input type="radio" name="tipo" id="normal" value="normal" class="form-control form-control-sm col-3 top-3">
                                
                      
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

<script type="text/javascript" src="../dependencias/js/Trazabilidad/Orden.js?v=1.0.1"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

  document.getElementById('cotizacion').addEventListener("click",(event)=>{
  
  event.stopPropagation();
  opcionSeleccionada = document.getElementById('cotizacion').value;
  document.getElementById('cotizacion').addEventListener("click",(event)=>{
    let sucursal = opcionSeleccionada;
    dataCotizacion(sucursal);
  });
  
});
</script>

