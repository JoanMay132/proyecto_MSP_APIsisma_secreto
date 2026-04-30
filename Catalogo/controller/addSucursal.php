<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Sucursal.php";
include_once "../../class/Helper.php";

if(empty($_POST['nombre']) || empty($_POST['direccion'])){
    return false;
}

$datos = array(
    "nombre" => Helper::val_input($_POST['nombre']),
    "direccion" => Helper::val_input(($_POST['direccion']))

);


$obsuc = new Sucursal();
echo $obsuc->Add(array($datos["nombre"],$datos["direccion"]));
?>