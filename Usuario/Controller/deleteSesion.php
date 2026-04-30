<?php
    include_once '../../controlador/conexion.php';
    include_once '../../class/sesion.php';
    $oSesion = new Sesion();

    $sesion =(int) base64_decode($_POST['solicitud']);
    $sesion = filter_var($sesion,FILTER_VALIDATE_INT);
    
    if(!$sesion){
        return false;
    }

    echo $oSesion->deleteSesion($sesion);

