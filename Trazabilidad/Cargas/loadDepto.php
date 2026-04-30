<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Deptocli.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}

$idclient =(int) base64_decode($_POST['cliente']);
if(!filter_var($idclient,FILTER_VALIDATE_INT)){ return false;}

$oDepto = new Deptocli();

foreach($oDepto->GetDataAll($idclient) as $data){
    $datas[] = array(base64_encode($data["pkdeptocli"]),$data["nombre"]);
}
echo json_encode($datas);
?>