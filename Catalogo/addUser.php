<?php @session_start(); {$title = "- USUARIO";}
  include '../dependencias/php/head.php';
  include_once '../controlador/Usuario.php';

    #region Sesión
    if(!isset($_COOKIE['sesion_usuario']) && !isset($_SESSION['id_usuario'])){
        echo '<script>window.close();</script>';
    exit;
      }
      #endregion
 


  if(isset($_GET['user'])){ 
    //Se recibe la URL y se valida
   $pkusuario = (int) base64_decode($_GET['user']);
   $pkusuario = filter_var($pkusuario,FILTER_VALIDATE_INT);

        if(!$pkusuario){
            echo "ERROR AL MOSTRAR EL CONTENIDO, LA URL NO ES VALIDA";
            return false;
        }
  }else{
        //Se recibe la URL y se valida
        $pkemployee = (int) base64_decode($_GET['employee']);
        $pkemployee = filter_var($pkemployee,FILTER_VALIDATE_INT);

        if(!$pkemployee){
            echo "ERROR AL MOSTRAR EL CONTENIDO, LA URL NO ES VALIDA";
            return false;
        }
  }
  ?>

  <div class="main-wrapper" style="width:100%;margin:0;padding:0">
    <!-- ! Main -->
    <main class="main" >
    
        <div class="container" >
            
            <div class="row">
            <?php if(isset($_GET['user']) && !empty($_GET['user']) ){ 
                $obuser = new Usuario();
                    $resUser = $obuser->GetDataUser($pkusuario);
                ?>
            <div class="col-12" >
           
                <div class="card" >
                <form  id="form-updatenombre"  onsubmit="return updateNombre();">
                    <input type="hidden" name="pkusuario" value="<?php echo $_GET['user']; ?>">
                    <div class="card-header">
                        Cambiar el nombre de usuario
                        </div>
                        <div class="card-body">                           
                            <div class="form-group">
                                <label class="label text-secondary" for="usuario">Usuario * </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-user"></span></div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" value="<?php echo $resUser['usuario']; ?>" required id="usuario" placeholder="Nombre de usuario" name="usuario" oninput="return Valida(this.value);">
                                    
                                </div>
                                <span class="txt-12" id="valid_user"></span>
                            </div>
                                <div class="float-right" id="g-botones">
                                        <button class="btn-sm btn-primary" name="updateuser" id="updateuser">Guardar</button>
                                </div>
                        
                        </div>
                </form>
                <form  id="form-updatepassword"  onsubmit="return updatePassword();">
                    <input type="hidden" name="pkusuario" value="<?php echo $_GET['user']; ?>">
                    <div class="card-header">
                        Cambiar contraseña
                        </div>
                        <div class="card-body">
                        <?php if($_SESSION['tipo_user'] != 'ADMIN' && $_SESSION['tipo_user'] != 'ROOT'){ ?>   
                        <div class="form-group row">
                                <label class="label text-secondary col-12 col-sm-3" for="constrasena1">Contraseña actual * </label>
                                <div class="input-group col-12 col-sm-9">
                                    <div class="input-group-prepend" style="height:31px">
                                        <div class="input-group-text"><span class="fa fa-lock"></span></div>
                                    </div>
                                    <input type="password" name="actual"  class="form-control form-control-sm" id="actual" placeholder="Contraseña actual">
                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-group row">
                                <label class="label text-secondary col-12 col-sm-3" for="contrasena1">Contraseña * </label>
                                <div class="input-group col-12 col-sm-9" style="height:31px">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-lock"></span></div>
                                    </div>
                                    <input type="password" name="password1" class="form-control form-control-sm" id="contrasena1" placeholder="Contraseña" oninput="validaPass();">
                                    <span class="txt-12" id="valida_pass1"></span>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label class="label text-secondary col-12 col-sm-3" for="contrasena2">Repita la contraseña * </label>
                                <div class="input-group col-12 col-sm-9">
                                    <div class="input-group-prepend" style="height:31px">
                                        <div class="input-group-text"><span class="fa fa-lock"></span></div>
                                    </div>
                                    <input type="password" name="password2" class="form-control form-control-sm" id="contrasena2" placeholder="Repita la contraseña" oninput="return validaPass2();">
                                    <span class="txt-12" id="valida_pass2"></span>
                                </div>
                                
                            </div>

                                <div class="float-right" id="g-botones">
                                
                                        <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Guardar</button>
                                </div>
                        
                        </div>
                </form>
                <form  id="form-correo"  onsubmit="return updateCorreo();">
                    <input type="hidden" name="pkusuario" value="<?php echo $_GET['user']; ?>">
                    <div class="card-header">
                        Cambiar correo
                        </div>
                        <div class="card-body">                           
                        <div class="form-group">
                                <label class="label text-secondary" for="email">Correo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-at"></span></div>
                                    </div>
                                    <input type="email" name="correo" class="form-control form-control-sm" id="email" placeholder="Correo" onchange="return validarCorreo(this.value);" value="<?php echo $resUser['correo']; ?>">
                                </div>
                                <span class="txt-12" id="valida_correo"></span>
                            </div>
                                <div class="float-right" id="g-botones">
                                        <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Guardar</button>
                                </div>
                        
                        </div>
                </form>
                <?php 
                                if(@$_SESSION['tipo_user'] === 'ADMIN' || @$_SESSION['tipo_user'] === 'ROOT'){

                            ?>
                <form  id="form-rol"  onsubmit="return updateRol();">
                    <input type="hidden" name="pkusuario" value="<?php echo $_GET['user']; ?>">
                    <div class="card-header">
                        Cambiar tipo y estado
                        </div>
                        <div class="card-body">                           
                        <div class="form-group row">
                                <label class="label text-secondary col-12 col-sm-2" for="correo">Tipo</label>
                                <div class="input-group col-12 col-sm-5">
                                    <select name="tipo" class="form-control form-control-sm" id="tipo" required>
                                        <?php $normal = $resUser['rol'] == 'NORMAL' ? 'selected':'';
                                              $admin = $resUser['rol'] == 'ADMIN' ? 'selected':'';
                                        ?>
                                        <option <?php echo $normal; ?> value="NORMAL">Normal</option>
                                        <option <?php echo $admin; ?>  value="ADMIN">Administrador</option>
                                    </select>
                                </div>
                                <?php $selecCheck = $resUser['status'] == 'BAJA' ? 'checked' : ''; ?>
                                <label class="label text-secondary" for="status">Suspender <span style="font-size:15px;" class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="bottom" title="Al suspender al usuario se le niega el acceso al sistema, puedes activarlo y desactivarlo en cualquier momento."></span></label>
                                &nbsp;<input type="checkbox" id="status" name="status" value="BAJA"  class="" <?php echo $selecCheck; ?>>
                            </div>
                                <div class="float-right" id="g-botones">
                                        <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Guardar</button>
                                </div>
                        
                        </div>
                </form><?php } ?>
            </div>
        </div>
        <?php }else{ ?>
            <div class="col-12" >
                <div id="mensaje" class="mensaje">
                    Al crear el usuario se le esta dando acceso de entrar y controlar partes del sistema al empleado, 
                    puedes restringir las acciones que hacen los usuarios en el modulo de permisos.
                </div>
                <div class="card" >
                <form  id="form-user"  onsubmit="return addUser();">
                    <input type="hidden" name="pkemployee" value="<?php echo $_GET['employee']; ?>">
                    <div class="card-header">
                        Datos del Usuario
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="label text-secondary" for="usuario">Usuario * </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-user"></span></div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" required id="usuario" placeholder="Nombre de usuario" name="usuario" oninput="return Valida(this.value);">
                                    
                                </div>
                                <span class="txt-12" id="valid_user"></span>
                            </div>
                            <div class="form-group">
                                <label class="label text-secondary" for="correo">Correo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-at"></span></div>
                                    </div>
                                    <input type="email" name="correo" class="form-control form-control-sm" id="email" placeholder="Correo" onchange="return validarCorreo(this.value);">
                                </div>
                                <span class="txt-12" id="valida_correo"></span>
                            </div>
                            <div class="form-group">
                                <label class="label text-secondary" for="constrasena1">Contraseña * </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-lock"></span></div>
                                    </div>
                                    <input type="password" name="password1" required class="form-control form-control-sm" id="contrasena1" placeholder="Contraseña" oninput="validaPass();">
                                </div>
                                <span class="txt-12" id="valida_pass1"></span>
                            </div>
                            <div class="form-group">
                                <label class="label text-secondary" for="contrasena2">Repita la contraseña * </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><span class="fa fa-lock"></span></div>
                                    </div>
                                    <input type="password" name="password2" required class="form-control form-control-sm" id="contrasena2" placeholder="Repita la contraseña" onchange="validaPass2();">
                                </div>
                                <span class="txt-12" id="valida_pass2"></span>
                            </div>

                            <div class="form-group">
                                <label class="label text-secondary" for="tipo">Tipo de usuario * <span style="font-size:15px;" class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="bottom" title="El usuario tipo Administrador tiene control total del sistema, el usuario Normal es aquel que tiene acceso a solo siertas partes del sistema dependiendo de los permisos."></span></label>
                                <select name="tipo" class="form-control form-control-sm" id="tipo" required>
                                    <option value=""></option>
                                    <option value="NORMAL">Normal</option>
                                    <option value="ADMIN">Administrador</option>
                                </select>
                            </div>
                            

                                <hr>

                                <div class="float-right" id="g-botones">
                                        <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Guardar</button>
                                </div>
                        
                        </div>
                </form> <br> 
            </div>

        </div>


            <?php } ?>
            
        </div>
        </div>

    </main>
<?php
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Catalogo/Users.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        });
</script>

