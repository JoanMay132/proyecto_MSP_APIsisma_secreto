<?php 
if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$pre = (int) base64_decode($_POST['presupuesto']);

include_once '../../controlador/conexion.php';
spl_autoload_register(function($class){
    include_once "../../controlador/".$class.".php";
});


isset($_POST['tipo']) && @$_POST['tipo'] == "true" ?  $oMaterial = new Prematerial2() : $oMaterial = new Prematerial();
//$oMaterial = new Prematerial();

    if(!filter_var($pre,FILTER_VALIDATE_INT)){ return false;}

    $consulta = $oMaterial->GetDataAll($pre);
 
    echo json_encode($consulta);

