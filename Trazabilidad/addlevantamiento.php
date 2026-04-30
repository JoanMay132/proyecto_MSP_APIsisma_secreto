<?php 
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

   ?>

<script>
    function popup (URL){ 
        window.open(URL,"","width=500,height=auto,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
    } 
    function servicios (URL){ 
        window.open(URL,"","width=800,height=800;scrollbars=yes,left=900,addressbar=0,menubar=0,toolbar=0");
    } 
  </script>

  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">
    
    <form id="form-levantamiento" onsubmit="return addLevantamiento();">
        <div class="row" style="margin-top: 0px">
            <div class="col-12 col-sm-6">
                <div class="form-group row" style="padding:2px;margin-top:-10px">
                    <label for="listClientes" class="txt-12 text-secondary col-12 col-sm-3">Cliente</label>
                    <select name="cliente" id="listClientes" class="form-control txt-12 col-12 col-sm-9 " onchange="return usercustomer(this.value);" style="height:25px;font-size:11px;padding:0px">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-20px">
                    <label for="listUsercustomer" class="txt-12 text-secondary col-12 col-sm-3">Solicito</label>
                    <select name="solicito" onchange="return viewDepto(this.value);" class="form-control form-control-sm col-8 col-sm-8 listUsercustomer"  style="width:100%;height:25px;font-size:11px;padding:0px" id="listUser">
                  
                        
                    </select>
                    <div class="col-1 col-sm-1 text-center" style="padding:0">
                        <button type="button"  id="buttonUser" title="Agregar usuario" data-toggle="tooltip" class="btn btn-outline-secondary"  style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px" class="fa fa-user-plus"></i></button>
                    </div>
                    
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-20px">
                    <label for="deptouser" class="txt-12 text-secondary col-12 col-sm-3">Departamento</label>
                    <input type="text" name="depto" id="deptouser" class="form-control form-control-sm col-12 col-sm-9 " style="height:25px;font-size:11px;padding:3px">
                </div>
 
            </div>
            <div class="col-12 col-sm-4">
            <div class="form-group row" style="padding:2px;margin-top:-10px">
                    <label for="folio" class="txt-12 text-secondary col-12 col-sm-3">Folio: </label>
                    <div class="txt-12 col-12 col-sm-3" style="padding:5px;display:flex"><p id="displayFolio" style="color:red;">VACIO</p>
                        
                    </div>
                   
                        <label class="txt-12 text-secondary col-sm-2">Rev.</label>
                        <select onchange="return dataRevision(this.value)" class="form-control form-control-sm col-sm-4" style="height:25px;font-size:11px" id="listRevision">
                           
                        </select>
                    
            </div>
            <div class="form-group row" style="padding:2px;margin-top:-20px">
                    <label for="fecha" class="txt-12 text-secondary col-12 col-sm-3">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm col-12 col-sm-9 " style="height:25px;font-size:11px" >
                </div>
                <div class="form-group row" style="padding:2px;margin-top:-20px">
                    <label for="sucursal" class="txt-12 text-secondary col-12 col-sm-3">Sucursal</label>
                    <select onchange="return Sucursal(this.value);" name="sucursal" id="sucursal"  class="form-control form-control-sm col-12 col-sm-9" style="height:25px;font-size:11px" >
                        <option value=""></option>
                        <?php foreach($obsuc->GetDataAll() as $sucursal){ ?>
                        <option value="<?php  echo base64_encode($sucursal['pksucursal']); ?>"><?php echo $sucursal['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                
            </div>
            <div class="col-12 col-sm-2" >
                <div class="form-group float-right" style="padding:2px;margin-top:-10px">
                    <button class="btn btn-primary btn-sm" name="guardar" id="guardar">Guardar/<br>Imprimir</button>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:-15px;">
            <div class="col-12">
                <div style="overflow:auto;height: 50vh;" class="scroll">
                  
                    <table class="table-stripped table-bordered table-levantamiento" width="100%" id="table" style="background-color: white;font-size:11px;">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;" >
                          <tr>
                            <th width="5%">PDA</th>
                            <th width="5%">CANT.</th>
                            <th width="10">UNIDAD</th>
                            <th width="69%">DESCRIPCIÓN</th>
                            <th width="10%">COSTO</th>
                            <th width="1%"></th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table table-hover" id="serv-levantamiento">
                        
                          <tr style="max-height: 80px;" ondblclick='addServlev(<?php echo json_encode($data); ?>)' id="serv-0">
                            <input type="hidden" name="fkcatserv[]" id="fkcatservNew-0">
                            <td><input name="pda[]" type="number" min="1.0" step="0.01" class="form-control form-control-sm"  autocomplete="off"></td>
                            <td><input name="cant[]" type="number" min="1" class="form-control form-control-sm" autocomplete="off"></td>
                            <td>
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ ?>
                                        <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><textarea oninput="autoResize(this,0);" spellcheck="false" id="descNew-0" name="descripcion[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" ></textarea></td>
                            <td><input onchange="moneda(this);" id="costoNew-0"  name="costo[]" type="text"  class="form-control form-control-sm" autocomplete="off"></td>
                            <td>
                                    <button type="button" onclick="javascript:servicios('servicios?row=0&new=true')" class="btn-info" title="Agregar servicio" id="accion-0" style="width:100%;height:15px;"></button>
                                    <button type="button" onclick="return eliminarFila(this);" class="btn-danger" data-serv="0" title="Eliminar" id="accion-0" style="width:100%;height:15px;"></button>
                            </td>
                        </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-7">
                <label class="txt-12 text-secondary">RECURSOS REQUERIDOS / REQUERIMIENTOS ESPECIALES</label>
                <textarea class="form-control txt-12" rows="4" ></textarea>
                <div class="row">
                    <label class="txt-12 text-secondary col-12 col-sm-5">ENTREGO POR LA COMPAÑIA</label>
                    <select  name="venta" class="form-control form-control-sm col-12 col-sm-5 listUsercustomer" style="height:25px;font-size:11px">
                            <option></option>
                        </select> 
                </div>
                <div class="row">
                    <label class="txt-12 text-secondary col-12 col-sm-5">RECIBIO POR MSP</label>
                    <select id="recibio" name="venta" class="form-control form-control-sm col-12 col-sm-5 listEmployee" style="height:25px;font-size:11px">
                            <option></option>
                        </select> 
                </div>
            </div>
            <div class="col-sm-4">
                
            </div>
        </div>

        <div class="row" >
            <div class="col-12 col-sm-3"  >
                <div class="form-group text-center" >
                        
                       
                </div>
            </div>

        </div>
        </form>
    </main>
    
<?
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Trazabilidad/Levantamiento.js"></script>
<script type="text/javascript" src="../dependencias/js/Moneda.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
        })

        Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Seleccione sucursal',
                    showConfirmButton: true,
                    width:'auto',
                    //timer: 1500,
                  });

</script>

