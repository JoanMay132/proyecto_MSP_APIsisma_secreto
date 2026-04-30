<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Revpreeliminar.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';
    
    $rol = new Permisos();
    $rev= new Revpreeliminar();
    $mensaje = [];

    $pkserv =(int) base64_decode($_POST['servicio']);
    $pkserv = filter_var($pkserv,FILTER_VALIDATE_INT);
    
    if(!$pkserv){
        return false;
    }

#region Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::revpreeliminar->value,base64_decode(@$_POST['branch']));
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $mensaje['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($mensaje);
    return false;
}
#endregion

    if($rev->DeleteServ($pkserv)){
        $mensaje['success'] = 'Eliminado';
        echo json_encode($mensaje);
        return true;
    }else{
        $mensaje['Error'] = 'No se pudo eliminar el elemento';
    }

    echo json_encode($mensaje);


?>