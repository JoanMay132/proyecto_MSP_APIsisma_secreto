<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Proveedor.php";

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$prov = new Proveedor();

if(isset($_POST['sucursal']) && @$_POST['sucursal'] != ''){

$idsuc =(int) base64_decode($_POST['sucursal']);
if(!filter_var($idsuc,FILTER_VALIDATE_INT)){ return false;}
    foreach($prov->GetDataAll($idsuc) as $data){
        $datas[] = array (
            base64_encode( $data["pkproveedor"]),
            $data["nombre"]
        );
    }
}else{
   $proveedor =(int) base64_decode($_POST['proveedor']);
        $data = $prov->GetData($proveedor);
        $datas = array (
            "rfc" => $data['rfc'],
            "direccion" => $data['direccion'],
            "contacto" => $data['contacto'],
            "telefono" => $data['telefono'],
            "correo" => $data['correo'],
            "nproveedor" => $data['nproveedor']
        );
    
}   
echo json_encode($datas);
?>