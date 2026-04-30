<?php
include_once '../../controlador/conexion.php';
include_once '../../controlador/Usuario.php';
include_once "../../class/Helper.php";
if(!isset($_POST['user']) && empty($_POST['user'])){
    return false;
}

$oUser = new Usuario();
$color = null; $message = null;

$usuario = Helper::val_input($_POST['user']);

$dataUser =(array) $oUser->GetData($usuario);
if(!empty($usuario)){
    if(count($dataUser) > 1){
        $message = "El nombre de usuario no esta disponible, intente con otro";
        $color = 'red';
    }else{
        $message = "El nombre de usuario esta disponible";
        $color = 'green';
    }
}

echo '<span style="color:'.$color.'">'.$message.'</span>';
 
?>
