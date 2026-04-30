<?php @session_start(); {$title = "NUEVO EMPLEADO"; } 
  include '../dependencias/php/head.php';
  include_once '../controlador/Estado.php';
  include_once '../controlador/Puesto.php';
  include_once '../controlador/Sucursal.php';

  #region Sesión
  if(!isset($_COOKIE['sesion_usuario']) && !isset($_SESSION['id_usuario'])){
    header('Location:../');
  }
  #endregion

$sucursal = (int) base64_decode(@$_GET['suc']);
if(!filter_var($sucursal,FILTER_VALIDATE_INT)){ echo "<div style='width:100%;background:red;text-align:center;font-size:30px;color:white'>LA URL NO ES VALIDA :(</div>"; return false;}

#region Permisos
if (!$rol->getPermissionControl($_SESSION['controles'], Controls::empleado->value, $sucursal) && ($_SESSION['tipo_user'] != 'ROOT' && $_SESSION['tipo_user'] != 'ADMIN' )) {
  echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
 return false;
}

$rol->getBranchInPermission($_SESSION['controles'], Controls::empleado->value);

$modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) ? true : false;
#endregion
  ?>

  <div class="main-wrapper" style="width:100%;margin:0;padding:0">
    <!-- ! Main -->
    <main class="main" >
    
        <div class="container" >
            
            <div class="row">
        
            <div class="col-12" >
                <div id="mensaje"></div>
            <div class="card" >
            <form  id="form-employee" enctype="multipart/form-data" onsubmit="return addEmployee();">
            <div class="card-header">
                   Datos del empleado
                </div>
                <div class="card-body">
                <div class="form-group row">
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Sucursal*</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <select name="sucursal" class="form-control form-control-sm" required>
                                <?php 
                                        $obsuc = new Sucursal();
                                        foreach($obsuc->GetDataAll() as $data){
                                            $selsuc = $sucursal == $data['pksucursal'] ? 'selected' : '';
                                            if (in_array($data['pksucursal'], $rol->getBranch())) {
                                    ?>
                                    <option <?php echo $selsuc; ?> value="<?php echo base64_encode($data["pksucursal"]); ?>"><?php echo $data["nombre"]; ?></option>
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary" style="width:auto">Fecha ingreso *</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <input type="date" class="form-control form-control-sm" required name="ingreso" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Nombre*</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <input type="text" class="form-control form-control-sm" required name="nombre" autocomplete="nope">
                            </div>
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Apellidos*</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <input type="text" class="form-control form-control-sm" required name="apellidos" autocomplete="off">
                            </div>  
                        </div>
                       
                        <div class="form-group row">
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Puesto</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <select class="form-control form-control-sm" name="puesto">
                                    <option value=""></option>
                                    <?php 
                                        $oPuesto = new Puesto();
                                        foreach($oPuesto->GetDataAll() as $puesto){
                                    ?>
                                    <option value="<?php echo $puesto['pkpuesto']; ?>"><?php echo $puesto['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">C.P</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <input type="text" class="form-control form-control-sm" name="cp" autocomplete="off" >
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Direccion</label>  
                            </div>
                            <div class="col-12 col-sm-11">
                                <input type="text" class="form-control form-control-sm" name="direccion" autocomplete="nope" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Estado</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <select class="form-control form-control-sm" name="estado" onchange="return loadmunicipio(this.value)">
                                    <option value="">Seleccione estado</option>
                                    <?php $obest = new Estado();
                                        foreach($obest->GetDataAll() as $estado){
                                    ?>
                                    <option value="<?php echo $estado['id_estado']; ?>"><?php echo $estado["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Municipio</label>  
                            </div>
                            <div class="col-12 col-sm-5">
                                <select class="form-control form-control-sm" id="municipio" name="municipio">
                                    <option value=""></option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Curp</label>  
                            </div>
                            <div class="col-12 col-sm-3">
                                <input type="text" class="form-control form-control-sm" name="curp" autocomplete="off">
                            </div>
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Rfc</label>  
                            </div>
                            <div class="col-12 col-sm-3">
                                <input type="text" class="form-control form-control-sm" name="rfc" autocomplete="off" >
                            </div> 
                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Nss</label>  
                            </div>
                            <div class="col-12 col-sm-3">
                                <input type="text" class="form-control form-control-sm" name="nss" autocomplete="off" >
                            </div>   
                        </div>
                        <div class="form-group row">

                            <div class="col-12 col-sm-1">
                                <label class="label text-secondary">Foto</label>  
                            </div>
                            <div class="col-12 col-sm-11">
                                <input type="file" class="form-control form-control-sm" name="foto" accept="image/*">
                            </div>
                        </div>
                        <hr>

                        <div class="float-right" id="g-botones"><br>
                                <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Guardar</button>
                        </div>
                  
                </div>
                </form> <br> 
            </div>

        </div>
            
        </div>
        </div>

    </main>
<?php
  include_once '../dependencias/php/footer.php';
?>
<script src="../dependencias/js/cargas.js" type="text/javascript"></script>
<script type="text/javascript" src="../dependencias/js/Catalogo/Employee.js"></script>


