<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Cotizacion.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$oRevision= new Cotizacion();

if(isset($_POST['sucursal'] )){
    //Consulta la cotizacion por sucursal
    $idsuc =(int) base64_decode($_POST['sucursal']);
    if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;}
    foreach($oRevision->GetDataAll($idsuc) as $data){
        $datas[] = array(base64_encode( $data["pkcotizacion"]),$data["folio"]);
    }
    echo json_encode($datas);
}else{
    //Consulta la cotizacion por id
    $id=(int) base64_decode($_POST['id']);
    if(!filter_var($id,FILTER_VALIDATE_INT)){ return false;}
    $consulta = $oRevision->GetData($id);

    $data = array(
        "pkcotizacion" => base64_encode($consulta["pkcotizacion"]),
        "cliente" => base64_encode($consulta["fkcliente"]),
        "solicito" => base64_encode($consulta["fkusercli"]),
        "fecha" => $consulta["fecha"],
        "folio" => $consulta["folio"]
    );
    echo json_encode($data);
}


?>