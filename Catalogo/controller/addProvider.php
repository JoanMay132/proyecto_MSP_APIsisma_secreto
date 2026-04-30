<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Proveedor.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();


$obprov = new Proveedor();
$sucursal =(int) base64_decode($_POST['sucursal']);
$mensaje = array();

#region Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::proveedores->value,$sucursal);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $mensaje['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($mensaje);
    return false;
}
#endregion de verificacion



if(!filter_var($sucursal,FILTER_VALIDATE_INT)){
    return false;
}



if(empty($_POST['empresa']) || empty($_POST['sucursal']) ){
    $mensaje['Campos_requeridos'] = "Los campos marcados con * son requeridos";
} 

if(count($mensaje) == 0){

    $obprov->nombre = Helper::val_input(($_POST['empresa']));
    $obprov->telefono = Helper::val_input(($_POST['telefono']));
    $obprov->correo = Helper::val_input(($_POST['correo']));
    $obprov->direccion = Helper::val_input(($_POST['direccion']));
    $obprov->ciudad = Helper::val_input(($_POST['ciudad']));
    $obprov->datbancario = Helper::val_input(($_POST['datbancario']));
    $obprov->rfc = Helper::val_input(($_POST['rfc']));
    $obprov->nproveedor = Helper::val_input(($_POST['nproveedor']));
    $obprov->contacto = Helper::val_input(($_POST['contacto']));
    $obprov->fksucursal =  $sucursal;

    if(isset($_POST['pkprovider']) && !empty($_POST['pkprovider'])){
        $pkprovider =(int) base64_decode($_POST['pkprovider']);
        $obprov->pkproveedor = $pkprovider;
        if(!filter_var($pkprovider,FILTER_VALIDATE_INT)){
            return false;
        }
        if($obprov->Update($obprov)){
            $mensaje["Success"] = "Datos actualizados";
            $mensaje['sucursal'] = base64_encode($sucursal);  
        }

       
    }else{
        if($obprov->Add($obprov)){
            $mensaje["Success"] = "Proveedor Guardado";
            $mensaje['sucursal'] = base64_encode($sucursal); 
            
        }
    }
}

echo json_encode($mensaje);



?>