<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Sucursal.php";

$obsuc = new Sucursal();

foreach($obsuc->GetDataAll() as $data){
    $datas[] = array($data["nombre"],$data["direccion"]);
}
echo json_encode($datas);
?>