<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Suborden.php";
include_once "../../class/Helper.php";
require_once("../../class/SubFolio.php");
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$oOt= new Suborden();

$msg = array();

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$suc = (int) base64_decode($_POST['sucursal']);

if(!filter_var($suc,FILTER_VALIDATE_INT)){
    return false;
}

#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::ot->value,$suc);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $msg['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($msg);
    return false;
}
#endregion

//Se asigna folio
if(isset($_POST["Newfolio"]) && $_POST["Newfolio"] == true){
    $pkorden = (int) base64_decode($_POST['orden']);
    $folioOt = $_POST['folio'];
    $oFolio = new SubFolio("suborden","folio","fkorden","{$pkorden}","{$folioOt}");
    $folio =  $oFolio->getFolio();
}

$datos = array(
    "nombre" => "OT ".Helper::val_input($_POST['nombre'] ?? ''),
    "cliente" => (int) base64_decode($_POST['cliente']),
    "solicito" => (int) base64_decode($_POST['solicito'] ?? 0),
    "depto" => Helper::val_input($_POST['depto']),
    "fecha" => Helper::val_input($_POST['fecha']),
    "diaentrega" => Helper::val_input($_POST['diaentrega']),
    "tipo" => Helper::val_input($_POST['tipo']),
    "observaciones" => trim($_POST['observaciones']),
    "auxiliar" => (int) base64_decode($_POST['auxiliar'] ?? 0),
    "enterado" => (int) base64_decode($_POST['enterado'] ?? 0),
    "sucursal" => (int) base64_decode($_POST['sucursal']),
    "fkorden" => (int) base64_decode($_POST['orden'])
);
$datos['tipo'] = $datos['tipo'] == '' ? 'normal' : $datos['tipo'];

//Comprueba si el folio es un nuevo registro o una actualizacion
$datos['folio'] = @$_POST['Newfolio'] == true ? @$folio : Helper::val_input(@$_POST['folio']);


if(isset($_POST['Newfolio']) && $_POST['Newfolio'] == true)
{
   if($oOt->Add($datos)){
        $msg['success'] = 'Suborden guardada';
        $msg['orden'] = base64_encode($oOt->pksuborden);
   }
}
else
{
    if($_POST['orden'] == ""){
        $msg['error'] = "EL FOLIO NO EXISTE";
        echo json_encode($msg);
        return false;
    }
    $datos['pksuborden'] =  (int) base64_decode($_POST['suborden']);
    if($oOt->Update($datos)){ //Actualiza los datos de la suborden

        //Actualiza los datos registrados
        $dataServReg = array(); //Se crea el array
        if(isset($_POST['pda'])){
        for($i = 0 ; $i < count($_POST["pda"]); $i++){
            $dataServ = array();
            if(isset($_POST['pkserv'][$i])){
                $dataServ = array(
                    "pksubservorden" => (int) base64_decode($_POST['pkserv'][$i]),
                    "pda" => Helper::float($_POST['pda'][$i]),
                    "cantidad" => Helper::float($_POST['cant'][$i]),
                    "unidad" => Helper::val_input($_POST['unidad'][$i]),
                    "descripcion" => trim($_POST['descripcion'][$i]),
                    "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i]),
                    "dibujo" => Helper::val_input($_POST['dibujo'][$i])
                );
                $oOt->updateServ($dataServ);
            }
            else{
                 //guarda los nuevos servicios que se agregen
                if($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '' ||  $_POST["dibujo"][$i] != '')
                {
                    $dataServ = array(
                        "pda" => Helper::val_input($_POST['pda'][$i]),
                        "cantidad" => Helper::float($_POST['cant'][$i]),
                        "unidad" => Helper::val_input($_POST['unidad'][$i]),
                        "descripcion" => trim($_POST['descripcion'][$i]),
                        "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i]),
                        "dibujo" => Helper::val_input($_POST['dibujo'][$i]),
                        "suborden" => $datos['pksuborden']
                    
                    );
                
                    $oOt->AddServ($dataServ);
                }
            }
        }
            
        }


    } 
    $msg['success'] = 'Cambios guardados';
    $msg['orden'] = base64_encode($datos['pksuborden']);
}

    
    echo json_encode($msg);
    return false;
?>