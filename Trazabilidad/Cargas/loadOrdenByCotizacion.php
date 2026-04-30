<?php
include_once "../../controlador/conexion.php";
include_once "../../controlador/Orden.php";

if($_SERVER['REQUEST_METHOD'] !== "POST"){
    echo json_encode(array("error" => "Metodo no permitido"));
    return;
}

$idCotizacion = (int) base64_decode($_POST['id'] ?? '');
$idSucursal = (int) base64_decode($_POST['sucursal'] ?? '');

if(!filter_var($idCotizacion, FILTER_VALIDATE_INT)){
    echo json_encode(array("error" => "Cotizacion invalida"));
    return;
}

$oOt = new Orden();
$data = $oOt->GetByCotizacion($idCotizacion, $idSucursal);

if(!$data){
    echo json_encode(array("error" => "No se encontro O.T. para la cotizacion seleccionada"));
    return;
}

echo json_encode(array(
    "orden" => base64_encode($data["pkorden"]),
    "cotizacion" => base64_encode($data["fkcotizacion"])
));

?>
