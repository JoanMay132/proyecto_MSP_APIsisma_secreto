<?php
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Deptocli.php';
    $obdepto = new Deptocli();

    $pkdepto =(int) base64_decode($_POST['pkdepto']);
    $pkdepto = filter_var($pkdepto,FILTER_VALIDATE_INT);
    
    if(!$pkdepto){
        return false;
    }

    echo $obdepto->Delete($pkdepto);


?>