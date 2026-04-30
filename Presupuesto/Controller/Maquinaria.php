<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Maquinaria.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$fksucursal = filter_var(base64_decode($_POST['sucursal']),FILTER_VALIDATE_INT);

if(!$fksucursal){
    return false;
}
$msg = [];
#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::maquinaria->value,$fksucursal);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $msg['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($msg);
    return false;
}
#endregion
$oMaquinaria= new Maquinaria();

//Agrega nuevos registros
for($i = 0; $i < count($_POST['maquinaria']); $i++){
    if($_POST['maquinaria'][$i] != '' || $_POST['origen'][$i] != '' ||  $_POST['precio'][$i] != ''){
        $maquinaria = trim($_POST['maquinaria'][$i]);
        $origen = Helper::val_input($_POST['origen'][$i]);
        $precio = Helper::conver_float($_POST['precio'][$i]);

        $data = array($maquinaria,$origen,$precio,$fksucursal);

        $oMaquinaria->Add($data);
        
    }   
}


//Actualiza registros existentes
for($x = 0; $x < count($_POST['pkmaquinaria']); $x++){
    if($_POST['maquinariaReg'][$x] != '' || $_POST['origenReg'][$x] != '' || $_POST['precioReg'][$x] != ''){
        $pkmaquinaria = (int) base64_decode($_POST['pkmaquinaria'][$x]);
        $maquinariaReg = trim($_POST['maquinariaReg'][$x]);
        $origenReg = Helper::val_input($_POST['origenReg'][$x]);
        $precioReg = Helper::conver_float($_POST['precioReg'][$x]);

        $dataU = array($maquinariaReg,$origenReg,$precioReg,$pkmaquinaria);

        $oMaquinaria->UpdateData($dataU);
        
    }   
}
$msg["success"] = "Cambios guardados";

echo json_encode($msg);



?>