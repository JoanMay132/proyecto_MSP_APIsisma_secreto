<?php @session_start(); $title = "LOGIN";

include_once 'controlador/conexion.php';
include_once 'controlador/Usuario.php';
include_once 'class/sesion.php';
include_once 'class/Info.php';
include_once 'class/Permisos.php';
include_once 'class/Controles.php';

if(!isset($rol)){
    $rol = new Permisos();
  }
$login = new Sesion();
$oUsuario = new Usuario();


if(isset($_COOKIE['sesion_usuario_mspapi'])){
    $token = $_COOKIE['sesion_usuario_mspapi'];

    $user_agent = Info::obtener_info_navegador();
    $hostname = Info::get_hostname();
    if($login->findSesionForToken($token,$user_agent,$hostname)){        
            if($login->getStatus_sesion() === 'aceptada' ){
                $result = $oUsuario->User_employee($login->getUser_sesion()); //Busca al usuario en join con el empleado
                //Incia las sessiones
                $_SESSION['name_user'] = $result['nombre'].' '.$result['apellidos'];
                $_SESSION['id_usuario'] = $result['pkusuario'];
                $_SESSION['tipo_user'] = $result['rol'];
                $_SESSION['sucursal'] = base64_encode($result['fksucursal']);    
                if($rol->existPermission($login->getUser_sesion())){ //Consulta los permisos
                    $_SESSION['controles'] = $rol->result;
                    
                }
                header("location:main");
                return true;
            }                
    }else{
        setcookie('sesion_usuario_mspapi', '', time() - 3600, '/', '', true, true);
        session_destroy();
        //header("location:index");
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SISMA <?php echo $title; ?></title>
  <!-- Favicon -->

  <!-- Custom styles -->
      <link rel="stylesheet" href="./dependencias/css/bootstrap.min.css">
      <link rel="stylesheet" href="./dependencias/css/style.css">
      <link rel="stylesheet" href="./dependencias/css/sweetalert2.min.css">
      <link rel="stylesheet" href="./dependencias/fonts2/font-awesome.min.css">
      <link rel="shortcut icon" href="./dependencias/img/msp_api_icono.ico" type="image/x-icon">

      <style>
        body {
            background-color: #f8f9fa; /* Fondo claro */
        }
        .login-container {
            margin-top: 5%;
            max-width: 400px;
            padding: 30px;
            background-color: #ffffff; /* Fondo blanco */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-header h3 {
            color: #343a40; /* Color de texto oscuro */
        }
        .btn-custom {
            background-color: #0056b3; /* Color azul petróleo */
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #004494; /* Hover más oscuro */
        }
        .form-group .input-group-text {
            background-color: #e9ecef;
            border-right: 0;
        }
        .form-control {
            border-left: 0;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <div class="login-header">
                    <!-- Imagen de ejemplo -->
                    <img src="dependencias/img/Logo_Premium_Maquinados-04.svg?v=1.0" style="width: 15rem;" alt="Logo Empresa" class="img-fluid mb-3 img-responsive">
                    <h5>Inicio de sesión</h5>
                   
                </div>
                <form id="form-login">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="username" required autocomplete="off" class="form-control" id="username" placeholder="Ingrese su usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" required name="password" class="form-control" id="password" placeholder="Ingrese su contraseña">
                        </div>
                    </div>
                    <!-- <center><div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Permanecer conectado</label>
                    </div></center> -->
                    <button type="submit" id="ingresar" class="btn btn-custom btn-block">Ingresar</button>
                    <div class="spinner-border" role="status">
  <span class="sr-only">Loading...</span>
</div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="./dependencias/js/jquery-3.6.0.min.js"></script>
<script src="./dependencias/js/popper.min.js"></script>
<script src="./dependencias/js/bootstrap.min.js"></script>
<script src="./dependencias/js/sweetalert2.min.js"></script>
<script src="./dependencias/js/Login.js"></script>

</body>
</html>

