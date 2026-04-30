<?php
include_once "../../controlador/conexion.php";
include_once "../../controlador/Deptocli.php";
include_once "../../class/Helper.php";

//Creamos los objetos
$deptocli = new Deptocli();
$Mensaje = [];
//Se actuliza los departamentos
if(isset($_POST["pkdeptocli"])){
    if(count($_POST["pkdeptocli"]) > 0){
        for($c = 0; $c < count($_POST["pkdeptocli"]); $c++){
            
            //Guardamos los departamentos que no estan vacios
            $deptocli->UpdateData(array($_POST['nombreReg'][$c],base64_decode($_POST['pkdeptocli'][$c])));
        } 
    }
}

if(count($_POST["nombre"]) > 0){
    for($i = 0; $i < count($_POST["nombre"]); $i++){
        //Guardamos los departamentos que no estan vacios
        if(!empty($_POST["nombre"][$i]))  $deptocli->Add(array($_POST["nombre"][$i],base64_decode($_POST["fkcliente"])));
    } 
}

$Mensaje["Success"] = "Cambios guardados";

echo json_encode($Mensaje);
?>


