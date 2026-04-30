<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';
    
    $rol = new Permisos();

    $mensaje = [];
    if($_POST['tipo'] === 'ot'){
        include_once '../../controlador/Suborden.php';
        $rol->getPermissionControl($_SESSION['controles'],Controls::ot->value,base64_decode(@$_POST['branch']));
        $sub= new Suborden();
        
    }elseif($_POST['tipo'] === 'cot')
    {
        include_once '../../controlador/Subcotizacion.php';
        $rol->getPermissionControl($_SESSION['controles'],Controls::cotizacion->value,base64_decode(@$_POST['branch']));
        $sub= new Subcotizacion();
    }

       //Verifica si cuenta con los permisos

$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $mensaje['Error'] = 'No se guardaron los cambios. No tienes permiso para realizar esta acción ';
    echo json_encode($mensaje);
    return false;
}

    $pksub =(int) base64_decode($_POST['id']);
    $pksub = filter_var($pksub,FILTER_VALIDATE_INT);
    
    if(!$pksub){
        $mensaje['Error'] = 'Error de ID';
        echo json_encode($mensaje);
        return false;
    }

    $nombre = $_POST['name'];

    if($sub->UpdateName($pksub,$nombre)){
        $mensaje['susccess'] = 'Nombre cambiado';
        return true;
    }

    echo json_encode($mensaje);

?>