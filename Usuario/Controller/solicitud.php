<?php @session_start();
include_once '../../controlador/conexion.php';
include_once '../../class/sesion.php';

$oSesion = new Sesion();
$mensaje = [];

$sesion = (int) base64_decode($_POST['solicitud']);
$status = trim($_POST['status']);

if(!filter_var($sesion,FILTER_VALIDATE_INT)){
    return false;
}

if(empty($status)) {
    $mensaje['error'] = 'El estatus es obligatorio';
    echo json_encode($mensaje);
     return false;}


if($oSesion->updateSesion($sesion,$status)){
        $mensaje['success'] = 'Estado actualizado';
}else{
    $mensaje['error'] = 'Error al actualizar el estado de la sesión';
}


echo json_encode($mensaje);