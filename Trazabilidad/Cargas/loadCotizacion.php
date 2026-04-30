<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Cotizacion.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$oCot= new Cotizacion();
$datas = [];

if(isset($_POST['sucursal'] )){
    //Consulta la cot por sucursal
    $idsuc =(int) base64_decode($_POST['sucursal']);
    if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;}
    foreach($oCot->GetDataAll($idsuc) as $data){
        $datas[] = array(base64_encode( $data["pkcotizacion"]),$data["folio"]);
    }
    echo json_encode($datas);
}else{
    //Consulta la revision por id
    $id=(int) base64_decode($_POST['id']);
    if(!filter_var($id,FILTER_VALIDATE_INT)){ return false;}
    $consulta = $oCot->GetData($id);

    $data = array(
        "pkcotizacion" => base64_encode($consulta["pkcotizacion"]),
        "cliente" => base64_encode($consulta["fkcliente"]),
        "solicito" => base64_encode($consulta["fkusercli"]),
        //"depto" => $consulta["depto"],
        "fecha" => $consulta["fecha"],
        "folio" => $consulta["folio"]   
    );
    echo json_encode($data);
}


?>