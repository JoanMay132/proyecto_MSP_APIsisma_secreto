<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Requisicion.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}

$datas = [];
$idsuc =(int) base64_decode($_POST['sucursal']);
if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;}

$req = new Requisicion();
//$dato = ["pkrequisicion","folio","proyecto"];

foreach($req->GetDataAll($idsuc) as $data){


        $datas[] = array (base64_encode( $data["pkrequisicion"]),$data["folio"],$data["proyecto"]);
    
   
}
echo json_encode($datas);
