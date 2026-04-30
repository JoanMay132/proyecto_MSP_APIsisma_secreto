<?php 
include_once "../../controlador/conexion.php";
include_once "../../controlador/Usercli.php";
include_once "../../class/Helper.php";

$fkcliente = filter_var(base64_decode($_POST['fkcliente']),FILTER_VALIDATE_INT);

if(!$fkcliente){
    return false;
}

$mensaje = [];

$usercli = new Usercli();

//Agrega nuevos registros
for($i = 0; $i < count($_POST['titulo']); $i++){
    if($_POST['titulo'][$i] != '' || $_POST['nombre'][$i] != '' || $_POST['deptocli'][$i] != '' || $_POST['puesto'][$i] != ''){
        $titulo = Helper::val_input($_POST['titulo'][$i]);
        $nombre = Helper::val_input($_POST['nombre'][$i]);
        $deptocli = Helper::val_input($_POST['deptocli'][$i]);
        $puesto = Helper::val_input($_POST['puesto'][$i]);

        $data = array($titulo,$nombre,$puesto,$deptocli,$fkcliente);

        $usercli->Add($data);
    }   
}

//Actualiza registros existentes
if(count($_POST['tituloReg']) > 0){
    for($c = 0; $c < count($_POST['tituloReg']); $c++){
        
            $tituloReg = Helper::val_input($_POST['tituloReg'][$c]);
            $nombreReg = Helper::val_input($_POST['nombreReg'][$c]);
            $deptocliReg = Helper::val_input($_POST['deptocliReg'][$c]);
            $puestoReg = Helper::val_input($_POST['puestoReg'][$c]);
            $pkuserReg =(int) base64_decode($_POST['user'][$c]);


            $dataReg = array($tituloReg,$nombreReg,$puestoReg,$deptocliReg,$pkuserReg);

            $usercli->UpdateData($dataReg);
        
    }
}

$mensaje['success'] = 'Guardado correctamente';
echo json_encode($mensaje);
?>