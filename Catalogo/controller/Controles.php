<?php
include_once "../../controlador/conexion.php";
include_once "../../controlador/Controles.php";
include_once "../../class/Helper.php";
require_once("../../class/Folio.php");

$oCont = new Controles();
$error = array();
$msg = array();


if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}

$suc = (int) base64_decode($_POST['sucursal']);

if(!filter_var($suc,FILTER_VALIDATE_INT)){
    $error["sucursal"] = "No se ha seleccionado sucursal o la sucursal no es valida";
}

if(count($error) == 0){
    $data = array(
        "sucursal" => $suc,
        "usuario" =>(int) base64_decode($_POST['usuario']),
        "operacion" =>(int) base64_decode($_POST['operacion']),
        "control" =>(int) base64_decode($_POST['control'])
    );

    if(isset($_POST['checked']) && $_POST['checked'] == 'true'){
        if($oCont->addPermission($data)){
            $msg['success'] = 'Permiso asignado';

            echo json_encode($msg); return true;
        }else{
            $error['registro'] = 'No se ha podido asignar el permiso';
        }
    }else if(isset($_POST['checked']) && $_POST['checked'] == 'false'){
        if(isset($_POST['permiso']) && !empty($_POST['permiso'])){
           if($oCont->Delete($_POST['permiso'])){
            $msg['success'] = 'Permiso asignado';
            echo json_encode($msg); return true;
           }
           else{
                $error['registro'] = 'No se ha podido quitar el permiso';
           }
        }
    }
    
}

echo json_encode($error);