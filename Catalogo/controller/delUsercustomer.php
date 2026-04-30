<?php
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Usercli.php';
    $usercli = new Usercli();

    $pkusercli =(int) base64_decode($_POST['user']);
    $pkusercli = filter_var($pkusercli,FILTER_VALIDATE_INT);
    
    if(!$pkusercli){
        return false;
    }

    echo $usercli->Delete($pkusercli);


?>