<?php 
if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$pre = (int) base64_decode($_POST['presupuesto']);

include_once '../../controlador/conexion.php';
spl_autoload_register(function($class){
    include_once "../../controlador/".$class.".php";
});

isset($_POST['tipo']) && @$_POST['tipo'] == "true" ? $oMaq = new Premaquinaria2() : $oMaq = new Premaquinaria();
//$oMaq = new Premaquinaria();

    if(!filter_var($pre,FILTER_VALIDATE_INT)){ return false;}

    $consulta = $oMaq->GetDataAll($pre);
 
    echo json_encode($consulta);

