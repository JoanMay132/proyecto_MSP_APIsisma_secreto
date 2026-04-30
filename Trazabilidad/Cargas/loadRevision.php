<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Revpreeliminar.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$oRevision= new Revpreeliminar();
$datas = [];

if(isset($_POST['sucursal'] )){
    //Consulta la revision por sucursal
    $idsuc =(int) base64_decode($_POST['sucursal']);
    if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;}
    foreach($oRevision->GetDataAll($idsuc) as $data){
        $datas[] = array(base64_encode( $data["pkrevpreeliminar"]),$data["folio"]);
    }
    echo json_encode($datas);
}else{
    //Consulta la revision por id
    $id=(int) base64_decode($_POST['id']);
    if(!filter_var($id,FILTER_VALIDATE_INT)){ return false;}
    $consulta = $oRevision->GetData($id);

    $data = array(
        "pkrevision" => base64_encode($consulta["pkrevpreeliminar"]),
        "cliente" => base64_encode($consulta["fkcliente"]),
        "solicito" => base64_encode($consulta["fkusercli"]),
        "depto" => $consulta["depto"],
        "fecha" => $consulta["fecha"],
        "folio" => $consulta["folio"],
        
    );
    echo json_encode($data);
}


?>