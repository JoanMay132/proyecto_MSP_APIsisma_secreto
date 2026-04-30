<?php 
if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$pre = (int) base64_decode($_POST['presupuesto']);

include_once '../../controlador/conexion.php';
spl_autoload_register(function($class){
    include_once "../../controlador/".$class.".php";
});
isset($_POST['tipo']) && @$_POST['tipo'] == "true" ? $oServ = new Preservicio2() : $oServ = new Preservicio();
//$oServ = new Preservicio();

    if(!filter_var($pre,FILTER_VALIDATE_INT)){ return false;}

    $consulta = $oServ->GetDataAll($pre);
 
    echo json_encode($consulta);

