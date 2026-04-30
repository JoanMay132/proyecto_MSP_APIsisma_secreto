<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Usercli.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}

$idclient =(int) base64_decode($_POST['client']);
if(!filter_var($idclient,FILTER_VALIDATE_INT)){ return false;}

$oUser = new Usercli();
$datas = [];

foreach($oUser->getJoinDepto($idclient) as $data){
    $datas[] = array(base64_encode($data["pkusercli"]),/*$data["titulo"].'. '.*/$data["nombre"],$data["nombredepto"],$data["cargo"]);
}
echo json_encode($datas);
?>