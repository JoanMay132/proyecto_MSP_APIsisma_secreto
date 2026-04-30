<?php @session_start(); {$title = '- ALTA ENTREGA';} //TITULO DE LA PAGINA
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
  $rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::entregas->value);
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

    table .form-control:focus{
        border: 1px solid #80bdff;
    }

    table .form-control{
        height: 30px;
    }


</style>


  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">
    
    <form id="form-entrega" enctype="multipart/form-data">
        <div class="row">
            <div class="col-12 col-sm-5" >
                <div class="form-group row" >
                    <label for="listClientes" class="txt-11 text-secondary col-3 col-sm-3">CLIENTE</label>
                    <select name="cliente" required id="listClientes" class="form-control txt-12 col-9 col-sm-9" onchange="return usercustomer(this.value);" >
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
                    <select onchange="return Sucursal(this.value);" name="sucursal" id="sucursal"  class="form-control form-control-sm col-9 col-sm-9" >
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
                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm col-9 col-sm-9" required>
                </div>
                <div class="form-group row">
                    <label for="listorden" class="txt-11 text-secondary col-3 col-sm-3">O.T.:</label>
                    <select  name="orden" id="listorden" onchange="dataOrden(this.value);"  class="form-control form-control-sm col-3 col-sm-3" >
                        <option value=""></option>
                    </select>
                    <label for="cotizacion" class="txt-11 text-secondary col-3 col-sm-3">COTIZACION</label>
                    <select  name="cotizacion" id="cotizacion"  class="form-control form-control-sm col-3 col-sm-3" >
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-3" >
                <div class="form-group row" style="margin-left:15px">
      
                        <button class="btn btn-info btn-sm" style="border-radius:0px;" name="imprimir" id="imprimir"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                    
                    
                     <button class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar" id="guardar"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
                </div>
                <div class="row form-inline" style="font-size:12px;margin-top:8px !important">
                    <div class="col-7"><span>Imp. con evidencia:</span></div>
                    <div class="col-2 custom-control custom-radio">
                        <input type="radio" id="radioSi"  name="printEvi" class="custom-control-input">
                        <label class="custom-control-label" for="radioSi" >Si</label>
                    </div>
                    <div class="col-2 custom-control custom-radio">
                        <input type="radio" id="radioNo" name="printEvi" checked class="custom-control-input">
                        <label class="custom-control-label" for="radioNo">No</label>
                    </div>
                    <!-- <div class="col-3"><input class="form-control form-control-sm" type="radio" name="printEvi">Si</div>
                    <div class="col-3"><input class="form-control form-control-sm"  type="radio" name="printEvi">No</div> -->
                </div>
                    

            </div>

        </div>

        <div class="row">
            <div class="col-12" style="padding:2px">
                <div style="height: 65vh;background:white;border:2px solid grey;overflow:auto;" class="scroll table-responsive">
                <table class="table-bordered table-ot" width="100%" id="table" style="background-color: white;font-size:11px; border-collapse:collapse;">
                        <thead style="position: sticky;top:0;z-index: 10;" class="table-info" >
                          <tr>
                            <th width="5%">PDA</th>
                            <th width="5%">CANT.</th>
                            <th width="6%">UNIDAD</th>
                            <th width="40%">DESCRIPCIÓN</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="serv-ot">
                          <tr style="max-height: 80px;" >
                            <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control text-center"  autocomplete="off" inputmode="numeric"></td>
                            <td valign="top"><input type="number" name="cant[]"  min="0.00" class="form-control  text-center"  autocomplete="off"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control  text-center">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><textarea name="descripcion[]" class="form-control cajas-texto" autocomplete="off" style="resize:none;padding:3px"  onclick="menu(this); return false;" data-servicio='servicios?row=0&new=true' oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descNew-0')" onblur="ocultarScroll('descNew-0')"></textarea></td>

                        </tr>
                            
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-12 col-sm-8 ">
                       <center> <label class="txt-11 " for="observaciones">REQUERIMIENTOS ESPECIALES / OBSERVACIONES</label></center>
                       <textarea class="form-control top-8" rows="3" name="observaciones"></textarea>

                </div>
                <div class="col-12 col-sm-4">  
                            <div class="row"  >
                                 <label for="entrego" class="txt-11 col-6">SUBIR EVIDENCIA:</label>
                                        <input type="file" name="evidencia" class="form-control col-5">
                            </div> 
                            <div class="row"  >
                                 <label for="entrego" class="txt-11 col-6">ENTREGO POR MSP:</label>
                                 <select id="entrego" name="entrego" class="form-control listEmployee col-5" >
                                            <option></option>
                                        </select>
                            </div>
                            <div class="row" >
                                 <label for="recicibio" class="txt-11 col-6">RECIBIO POR EL CLIENTE:</label>
                                 <select id="recibio" name="recibio" class="form-control col-5 listUsercustomer" >
                                            <option></option>
                                        </select>
                            </div>
                           
                </div>

        </div>

        
        </form>
    </main>
    <!--  
    <div id="menu" class="context-menu">
        <ul>
            <li><a id="presupuesto"><i class="fa fa-dollar fa-lg" style="color: green;margin-right:10px"></i>PRESUPUESTAR</a></li>
            <li><a id="servicio" ><i class="fa fa-plus-square-o fa-lg" style="color:blue;margin-right:10px"></i>AGREGAR SERVICIO</a></li>
            <li><a id="eliminar" ><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR REGISTRO</a></li>
        </ul>
    </div> -->
    
<?php
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Trazabilidad/Entrega.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    function popup (URL){ 
        window.open(URL,"","width=500,height=700,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
    }
</script>

