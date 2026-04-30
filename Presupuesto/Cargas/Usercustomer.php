<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Usercli.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}

$iduser =(int) base64_decode($_POST['user']);
if(!filter_var($iduser,FILTER_VALIDATE_INT)){ return false;}

$oUser = new Usercli();


$datas = $oUser->GetData($iduser);

echo json_encode($datas);
?>