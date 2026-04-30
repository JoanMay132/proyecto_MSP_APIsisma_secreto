<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Usuario.php";
include_once "../../controlador/Employee.php";
include_once "../../class/Helper.php";
//Manejo de errores
$error = array();
$mensaje = array();

if(!isset($_COOKIE['sesion_usuario']) && !isset($_SESSION['id_usuario'])){
    $error['error'] = 'Error de sesión, consulte con el administrador';
         echo json_encode($error);
         return false;
  }
// if(@$_SESSION['tipo_user'] != 'ADMIN' && @$_SESSION['tipo_user'] != 'ROOT'){
//     $error['error'] = 'No cuentas con los permisos para agregar usuarios';
//     echo json_encode($error);
//     return false;
//  }

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;} 
$obuser = new Usuario();
if(isset($_POST['pkemployee'])){
    $pkemployee = (int) base64_decode($_POST['pkemployee']);
}

if(isset($_POST['pkusuario'])){ $pkusuario= (int) base64_decode($_POST['pkusuario']); }
//Comprobación de los campos requeridos no esten vacios
if(isset($_POST['usuario'])){
    if(empty($_POST['usuario'])) $error['usuario'] = "El nombre de usuario es requerido";
}elseif(isset($_POST['password1']) && isset($_POST['password2'])){
    if(empty($_POST['password1']) || empty($_POST['password2']))$error['Contraseña'] = "Completar los campos de contraseña";
}elseif(isset($_POST['correo'])){
    if(!empty($_POST['correo']) && !filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL))
    {
        $error['Correo'] = "El correo no es valido";
    }
}elseif(isset($_POST['tipo']) || isset($_POST['status']) && isset($_POST['pkusuario'])){
    if(empty($_POST['tipo'])) $error['Tipo'] = "El tipo de usuario es requerido";
}
else{

    if(empty($_POST['usuario']) || empty($_POST['password1']) || empty($_POST['password2']) || empty($_POST['tipo']) ){
    $error['Campos_incompletos'] = "Los campos marcados con * son requeridos";
    }
}




//Verificación de password
if(isset($_POST['password1']) && isset($_POST['password2'])){
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        if(!empty($_POST['password1']) && strlen($password1) < 8){
            $error["Password"] = "La contraseña debe tener minimo 8 caracteres";
            
        }else{
            if($password1 != $password2){
                $error["Coincidencia"] = "Las contraseñas no coinciden";
            }
        }
        if($_SESSION['tipo_user'] != 'ADMIN' && $_SESSION['tipo_user'] != 'ROOT' ){
                if(isset($_POST['actual'])){
                    $resuser = $obuser->GetDataUser($pkusuario);
                    if(!password_verify($_POST['actual'],$resuser['password'])){
                        $error["Contraseña"] = "Contraseña actual incorrecta.";
                    }
                }
        }

        $password = password_hash($password1,PASSWORD_BCRYPT);
}

//Comprobamos que el usuario no exista en la BD
if(isset($_POST['usuario'])){
    $numberUser = count((array)$obuser->GetData($_POST['usuario']));
    if($numberUser > 1){
        $error['Usuario'] = "El nombre de usuario ya existe, intente con otro nombre";
    }
}





$datos = array(
    "usuario" => isset($_POST['usuario']) ? Helper::val_input($_POST['usuario']) : "",
    "correo" => isset($_POST['correo']) ? Helper::val_input( $_POST['correo']): "",
    "password" => isset($password) ? "$password" : "",
    "rool" => isset($_POST['tipo']) ? Helper::val_input($_POST['tipo']) : ""
);

if(count($error) == 0){
    if(isset($_POST['usuario']) && isset($_POST['pkusuario'])){
        $datos["pkusuario"] = $pkusuario; 
        if($obuser->updateUsuario($datos)) $mensaje['Success'] = "Usuario cambiado"; echo json_encode($mensaje);
    }elseif(isset($_POST['correo']) && isset($_POST['pkusuario'])){
        $datos["pkusuario"] = $pkusuario; 
        if($obuser->updateEmail($datos)) $mensaje['Success'] = "Correo actualizado"; echo json_encode($mensaje);
    }elseif((isset($_POST['tipo']) || isset($_POST['status'])) && isset($_POST['pkusuario'])){
        $datos["pkusuario"] = $pkusuario; 
        $datos['status'] = isset($_POST["status"]) == true ? $_POST["status"]: "ALTA"; 
        if($obuser->updateRol($datos)) $mensaje['Success'] = "Tipo de usuario y status actualizados"; echo json_encode($mensaje);
    }
    elseif(isset($_POST['actual']) || ($_SESSION['tipo_user'] == 'ADMIN' || $_SESSION['tipo_user'] == 'ROOT') && isset($_POST['pkusuario'])){
        $datos["pkusuario"] = $pkusuario; 
        if($obuser->updatePassword($datos)) $mensaje['Success'] = "Contraseña cambiada"; echo json_encode($mensaje);
    }
    else{
        if($obuser->addUser($datos)){
            $obEmployee = new Employee();
            $obEmployee->updateUser($obuser->pkusuario,$pkemployee);
            
            $mensaje['Success'] = "Usuario registrado";
            echo json_encode($mensaje);
        }
    }
    

}else{
    echo json_encode($error);
}
?>