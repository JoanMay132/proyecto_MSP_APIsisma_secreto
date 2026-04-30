<?php $popper = false; 

if($_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php"){ 
  include_once './controlador/conexion.php';
  include_once './class/Permisos.php';
  include_once './class/Controles.php';
}
else{
  include_once '../controlador/conexion.php';
  include_once '../class/Permisos.php';
  include_once '../class/Controles.php';
}
if(!isset($rol)){
  $rol = new Permisos();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SISMA <?php echo $title; ?></title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="./img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <?php
      //$url = "/sisma/";
    if($_SERVER['REQUEST_URI']=== "/mspapisisma/main" ||$_SERVER['REQUEST_URI']=== "/mspapisisma/main.php") {   ?>
      <link rel="stylesheet" href="./dependencias/css/bootstrap.min.css">
      <link rel="stylesheet" href="./dependencias/css/style.css?v=1.0.0">
      <link rel="stylesheet" href="./dependencias/css/menu-bar.css">
      <link rel="stylesheet" href="./dependencias/css/sweetalert2.min.css">
      <link rel="stylesheet" href="./dependencias/fonts2/font-awesome.min.css">
      <link rel="shortcut icon" href="./dependencias/img/msp_api_icono.ico" type="image/x-icon">
  <?php }else{ ?>
      <link rel="stylesheet" href="../dependencias/css/bootstrap.min.css">
      <link rel="stylesheet" href="../dependencias/css/style.css?v=1.0.0">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="../dependencias/css/sweetalert2.min.css">
      <link rel="stylesheet" href="../dependencias/fonts2/font-awesome.min.css">
      <link rel="stylesheet" href="../dependencias/css/menu-bar.css">
      <link rel="shortcut icon" href="../dependencias/img/msp_api_icono.ico" type="image/x-icon">
 <?php }?>
 
</head>
<body>
