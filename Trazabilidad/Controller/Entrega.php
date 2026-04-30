<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Entrega.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';
include_once "../../class/Imagen.php";

$rol = new Permisos();
$oEntrega = new Entrega();
$obimg = new Imagen();

$msg = array();
$error = array();

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$suc = (int) base64_decode($_POST['sucursal']);

if(!filter_var($suc,FILTER_VALIDATE_INT)){
    return false;
}

#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::entregas->value,$suc);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $error['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($error);
    return false;
}
#endregion

#region Carga evidencia
$destination = isset($_POST['evidencia_bd']) && @$_POST['evidencia_bd'] != '' ? $_POST['evidencia_bd'] : '' ;


if(isset($_FILES['evidencia']) && $_FILES['evidencia']['name'] != ''){
  
    // Detalles del archivo
    $fileName = $_FILES['evidencia']['name'];
    $fileTmpName = $_FILES['evidencia']['tmp_name'];
    $fileSize = $_FILES['evidencia']['size'];
    $fileError = $_FILES['evidencia']['error'];
    $tipo  = $_FILES['evidencia']['type'];
    
  
    // Verifica si no hubo errores durante la carga
    if($fileError === 0){
        
        if($obimg->validar_img($tipo)){
        // Ruta donde se almacenará la imagen (puede ser una carpeta específica) 
        $destination = '../../dependencias/img/evidencias/'.uniqid().'.'.'webp';
  
        // Mueve el archivo temporal a la ubicación deseada
        //move_uploaded_file($fileTmpName, $destination);
        @copy($fileTmpName, $destination);

        }else{
            $error["Foto"] = "Error al cargar la foto, archivo no valido";
        }
    }
}
#endregion

$datos = array(
    "sucursal" => (int) base64_decode($_POST['sucursal']),
    "fkorden" => (int) base64_decode($_POST['orden']),
    "cliente" => (int) base64_decode($_POST['cliente']),
    "cotizacion" => (int) base64_decode($_POST['cotizacion'] ?? 0),
    "solicito" => (int) base64_decode($_POST['solicito']),
    "depto" => Helper::val_input($_POST['depto']),
    "fecha" => Helper::val_input($_POST['fecha']),
    "observaciones" => trim($_POST['observaciones']),
    "entrego" => (int) base64_decode(@$_POST['entrego']),
    "recibe" => (int) base64_decode(@$_POST['recibio']),
    "evidencia" => $destination
);

    if(isset($_POST['entrega']) && !empty($_POST['entrega'])){
        $datos['pkentrega'] = (int) base64_decode($_POST['entrega']);
        $oEntrega->Update($datos) ?: ($error['error'] = "Error de actualización");
    }

        if(!isset($_POST['entrega'])){
            $oEntrega->Add($datos)  ?: ($error['error'] = "Error de registro");
            $datos['pkentrega'] = $oEntrega->pkentrega;
        }
    

    if (count($error) == 0) {
      
        $edit = array();
        
         //Actualiza los registros
        if(isset($_POST['pda'])){
            for ($i = 0; $i < count($_POST["pda"]); $i++) {
                $dataServ = array();
                if (isset($_POST['pkserv'][$i])) {
                    $dataServ = array(
                        "pkserventrega" => (int) base64_decode($_POST['pkserv'][$i]),
                        "pda" => Helper::float($_POST['pda'][$i]),
                        "cantidad" =>  Helper::float($_POST['cant'][$i]),
                        "unidad" => Helper::val_input($_POST['unidad'][$i]),
                        "descripcion" => trim($_POST['descripcion'][$i]),
                        //"ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i])
                        //"dibujo" => Helper::val_input($_POST['dibujo'][$i])
                        "fkentrega" => $datos['pkentrega']

                    );
                    if ($_POST['pkserv'][$i] != '') {
                        array_push($edit,(int) base64_decode ($_POST['pkserv'][$i]));
                        $oEntrega->updateServ($dataServ);
                    }
                }
            }
        }
        if(count($edit) > 0) {
            $params = array_merge([$datos['pkentrega']], $edit);
            $oEntrega->DelServAll($edit, $params);
        }
        
        //Guarda los registros nuevos
        if(isset($_POST['pda'])){
            for ($i = 0; $i < count($_POST["pda"]); $i++) {
                $dataServ = array(); //Se limpia el arreglo
                $dataServ = array(
                    "pda" => Helper::float($_POST['pda'][$i]),
                    "cantidad" =>  Helper::float($_POST['cant'][$i]),
                    "unidad" => Helper::val_input($_POST['unidad'][$i]),
                    "descripcion" => $_POST['descripcion'][$i],
                    //"ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i]),
                    //"dibujo" => Helper::val_input($_POST['dibujo'][$i]),
                    "fkentrega" => $datos['pkentrega']
                );
                
                if (!isset($_POST['pkserv'][$i])) {
                    if ($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '') {
                       
                        $oEntrega->AddServ($dataServ);
                    }
                } elseif ($_POST['pkserv'][$i] == '') {
                   
                    $oEntrega->AddServ($dataServ);
                }
               
            }
        }

        $msg['success'] = base64_encode($datos['pkentrega']);
        
    }else{
        echo json_encode($error);
        return false;

    }

    echo json_encode($msg);