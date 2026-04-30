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
    $msg['error'] = 'NO TIENES PERMISO PARA AGREGAR/MODIFICAR';
    echo json_encode($msg);
    return false;
}
#endregion

$oMaterial= new Material();


if($_POST['id'] != ''){
    $id = base64_decode($_POST['id']);
    $data = array(
        trim($_POST['material']),
        $_POST['origen'],
        $_POST['unidad'],
        Helper::conver_float($_POST['precio']),
        $id
    );

    if($oMaterial->UpdateData($data)){
        $msg['success'] = 'Material actualizado';
    }else{
        $msg['error'] = 'Error al actualizar';
    }
}else{

    if($_POST['material'] != '' || $_POST['origen'] != '' || $_POST['unidad'] != '' || $_POST['precio'] != ''){
        $data = array(
            trim($_POST['material']),
            $_POST['origen'],
            $_POST['unidad'],
            Helper::conver_float($_POST['precio']),
            $fksucursal
        );

        if($oMaterial->Add($data)){
            $msg['pkmaterial'] = base64_encode($oMaterial->pkmaterial);
            $msg['success'] = 'Material agregado';
        }else{
            $msg['error'] = 'Error al agregar';
        }

    }

}

echo json_encode($msg);

?>