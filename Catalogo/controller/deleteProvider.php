<?php
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Proveedor.php';
    $provider = new Proveedor();

    $pkprovider =(int) base64_decode($_POST['provider']);
    $pkprovider = filter_var($pkprovider,FILTER_VALIDATE_INT);
    
    if(!$pkprovider){
        return false;
    }

    if($provider->Delete($pkprovider)){
        return true;
    }


?>