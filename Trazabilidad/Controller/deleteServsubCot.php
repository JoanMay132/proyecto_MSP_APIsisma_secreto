<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Subcotizacion.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';
    
    $rol = new Permisos();
    $cot = new Subcotizacion();
    $mensaje = [];

    $pkservcot =(int) base64_decode($_POST['id']);
    $pkservcot = filter_var($pkservcot,FILTER_VALIDATE_INT);
    
    if(!$pkservcot){
        return false;
    }

       //Verifica si cuenta con los permisos
        $rol->getPermissionControl($_SESSION['controles'],Controls::cotizacion->value,base64_decode(@$_POST['branch']));
        $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

        if(!$modifica){
            $mensaje['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
            echo json_encode($mensaje);
            return false;
        }

    if($cot->DeleteServ($pkservcot)){
        $mensaje['success'] = 'Eliminado';

    }

    echo json_encode($mensaje);


?>