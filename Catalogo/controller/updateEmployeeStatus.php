<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Employee.php";
include_once "../../class/Permisos.php";
include_once "../../class/Controles.php";

$error = array();

if($_SERVER['REQUEST_METHOD'] !== "POST"){
    echo json_encode(array("error" => "Metodo no permitido"));
    return false;
}

$empleado = (int) base64_decode($_POST['empleado'] ?? '');
$sucursal = (int) base64_decode($_POST['sucursal'] ?? '');
$estado = (int) ($_POST['estado'] ?? -1);

if(!filter_var($empleado, FILTER_VALIDATE_INT)){
    $error['error'] = "Empleado invalido";
}

if(!filter_var($sucursal, FILTER_VALIDATE_INT)){
    $error['error'] = "Sucursal invalida";
}

if($estado !== 0 && $estado !== 1){
    $error['error'] = "Estado invalido";
}

$rol = new Permisos();
$rol->getPermissionControl($_SESSION['controles'], Controls::empleado->value, $sucursal);
$modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) || ($_SESSION['tipo_user'] === 'ROOT' || $_SESSION['tipo_user'] === 'ADMIN');
if(!$modifica){
    $error['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCION EN LA SUCURSAL SELECCIONADA';
}

if(count($error) > 0){
    echo json_encode($error);
    return false;
}

$employee = new Employee();
if(!$employee->setStatus($empleado, $sucursal, $estado)){
    echo json_encode(array("error" => "No se pudo actualizar el estado"));
    return false;
}

echo json_encode(array(
    "success" => true,
    "estado" => $estado
));
?>
