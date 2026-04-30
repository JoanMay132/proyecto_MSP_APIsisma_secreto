<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Employee.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$idsuc =(int) base64_decode($_POST['sucursal']);
if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;} 

$oEmp = new Employee();
$datas = array(); // Vaciamos el arreglo

foreach($oEmp->GetDataAll($idsuc) as $data){
    $datas[] = array(base64_encode($data["pkempleado"]),$data["nombre"].' '.$data["apellidos"]);
}
echo json_encode($datas);
?>