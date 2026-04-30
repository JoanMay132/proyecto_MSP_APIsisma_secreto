<?php 
if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$pre = (int) base64_decode($_POST['presupuesto']);

include_once '../../controlador/conexion.php';
spl_autoload_register(function($class){
    include_once "../../controlador/".$class.".php";
});
isset($_POST['tipo']) && @$_POST['tipo'] == "true" ? $oMano = new Premobra2() : $oMano = new Premobra();
//$oMano = new Premobra();

    if(!filter_var($pre,FILTER_VALIDATE_INT)){ return false;}

    $consulta = $oMano->GetDataAll($pre);
 
    echo json_encode($consulta);

