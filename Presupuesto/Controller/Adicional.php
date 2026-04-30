<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Adicionales.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$fksucursal = filter_var(base64_decode($_POST['sucursal']),FILTER_VALIDATE_INT);

if(!$fksucursal){
    return false;
}
$msg = [];
$oAdicional= new Adicionales();

#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::adicionales->value,$fksucursal);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $msg['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($msg);
    return false;
}
#endregion


//Agrega nuevos registros
for($i = 0; $i < count($_POST['adicional']); $i++){
    if($_POST['adicional'][$i] != '' || $_POST['origen'][$i] != '' || $_POST['unidad'][$i] != '' || $_POST['precio'][$i] != ''){
        $adicional = trim($_POST['adicional'][$i]);
        $origen = Helper::val_input($_POST['origen'][$i]);
        $unidad = Helper::val_input($_POST['unidad'][$i]);
        $precio = Helper::conver_float($_POST['precio'][$i]);

        $data = array($adicional,$origen,$unidad,$precio,$fksucursal);

        $oAdicional->Add($data);
        
    }   
}


//Actualiza registros existentes
for($x = 0; $x < count($_POST['pkadicional']); $x++){
    if($_POST['adicionalReg'][$x] != '' || $_POST['origenReg'][$x] != '' || $_POST['unidadReg'][$x] != '' || $_POST['precioReg'][$x] != ''){
        $pkadicional = (int) base64_decode($_POST['pkadicional'][$x]);
        $adicionalReg = trim($_POST['adicionalReg'][$x]);
        $origenReg = Helper::val_input($_POST['origenReg'][$x]);
        $unidadReg = Helper::val_input($_POST['unidadReg'][$x]);
        $precioReg = Helper::conver_float($_POST['precioReg'][$x]);

        $dataU = array($adicionalReg,$origenReg,$unidadReg,$precioReg,$pkadicional);

        $oAdicional->UpdateData($dataU);
        
    }   
}
$msg["success"] = "Cambios guardados";

echo json_encode($msg);



?>