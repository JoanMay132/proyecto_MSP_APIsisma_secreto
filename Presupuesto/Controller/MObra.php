<?php  @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Mobra.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$fksucursal = filter_var(base64_decode($_POST['sucursal']),FILTER_VALIDATE_INT);

if(!$fksucursal){
    return false;
}

$msg = [];
$oM  = new MObra();

#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::manobra->value,$fksucursal);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $msg['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($msg);
    return false;
}
#endregion

//Agrega nuevos registros
for($i = 0; $i < count($_POST['descripcion']); $i++){
    if($_POST['descripcion'][$i] != '' || $_POST['origen'][$i] != '' || $_POST['precio'][$i] != ''){
        $descripcion = trim($_POST['descripcion'][$i]);
        $origen = Helper::val_input($_POST['origen'][$i]);
        $precio = Helper::conver_float($_POST['precio'][$i]);

        $data = array($descripcion,$origen,$precio,$fksucursal);

        $oM->Add($data);
        
    }   
}


//Actualiza registros existentes
for($x = 0; $x < count($_POST['pkoperario']); $x++){
    if($_POST['descripcionReg'][$x] != '' || $_POST['origenReg'][$x] != '' || $_POST['precioReg'][$x] != ''){
        $pkoperario = (int) base64_decode($_POST['pkoperario'][$x]);
        $descripcionReg = trim($_POST['descripcionReg'][$x]);
        $origenReg = Helper::val_input($_POST['origenReg'][$x]);
        $precioReg = Helper::conver_float($_POST['precioReg'][$x]);

        $dataU = array($descripcionReg,$origenReg,$precioReg,$pkoperario);

        $oM->UpdateData($dataU);
        
    }   
}
$msg["success"] = "Cambios guardados";

echo json_encode($msg);



?>