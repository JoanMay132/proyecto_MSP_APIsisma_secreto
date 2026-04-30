<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Material.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();
$msg = [];

$fksucursal = filter_var(base64_decode($_POST['sucursal']),FILTER_VALIDATE_INT);

if(!$fksucursal){
    return false;
}
#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::materiales->value,$fksucursal);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $msg['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($msg);
    return false;
}
#endregion

$oMaterial= new Material();

//Agrega nuevos registros
if(isset($_POST['material'])) {
    for($i = 0; $i < count($_POST['material']); $i++){
        $data = [];
        if($_POST['material'][$i] != '' || $_POST['origen'][$i] != '' || $_POST['unidad'][$i] != '' || $_POST['precio'][$i] != ''){
            $material = trim($_POST['material'][$i]);
            $origen = Helper::val_input($_POST['origen'][$i]);
            $unidad = Helper::val_input($_POST['unidad'][$i]);
            $precio = Helper::conver_float($_POST['precio'][$i]);
    
            $data = array($material,$origen,$unidad,$precio,$fksucursal);
    
            $oMaterial->Add($data);
            
        }   
    }
}


//Actualiza registros existentes
if(isset($_POST['pkmaterial'])) {
    for($x = 0; $x < count($_POST['pkmaterial']); $x++){
        $dataU = [];
        if($_POST['materialReg'][$x] != '' || $_POST['origenReg'][$x] != '' || $_POST['unidadReg'][$x] != '' || $_POST['precioReg'][$x] != ''){
            $pkmaterial = (int) base64_decode($_POST['pkmaterial'][$x]);
            $materialReg =  trim($_POST['materialReg'][$x]);
            $origenReg = Helper::val_input($_POST['origenReg'][$x]);
            $unidadReg = Helper::val_input($_POST['unidadReg'][$x]);
            $precioReg = Helper::conver_float($_POST['precioReg'][$x]);
    
            $dataU = array($materialReg,$origenReg,$unidadReg,$precioReg,$pkmaterial);
    
            $oMaterial->UpdateData($dataU);
            
        }   
    }
}
$msg["success"] = "Cambios guardados";

echo json_encode($msg);



?>