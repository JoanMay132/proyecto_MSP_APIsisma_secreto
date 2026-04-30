<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Orden.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$oOt= new Orden();
$datas = [];

if(isset($_POST['sucursal'] )){
    //Consulta la cot por sucursal
    $idsuc =(int) base64_decode($_POST['sucursal']);
    if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;}
    foreach($oOt->GetDataAll($idsuc) as $data){
        $datas[] = array(base64_encode( $data["pkorden"]),$data["folio"]);
    }
    echo json_encode($datas);
}else{
    //Consulta la orden por id
    $id=(int) base64_decode($_POST['id']);
    if(!filter_var($id,FILTER_VALIDATE_INT)){ return false;}
    $consulta = $oOt->GetData($id);

    $data = array(
        "pkorden" => base64_encode($consulta["pkorden"]),
        "cliente" => base64_encode($consulta["fkcliente"]),
        "solicito" => base64_encode($consulta["fkusercli"]),
        "depto" => $consulta["depto"],
        "fecha" => $consulta["fecha"],
        "folio" => $consulta["folio"],
        "fkcotizacion" => base64_encode($consulta["fkcotizacion"])   
    );
    echo json_encode($data);
}


?>