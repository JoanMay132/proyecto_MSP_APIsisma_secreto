<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Requisicion.php";
include_once "../../class/Helper.php";
require_once("../../class/Folio.php");
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$oReq = new Requisicion();
$error = array();
$resp = array();
$msg = array();

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$suc = (int) base64_decode($_POST['sucursal']);

if(!filter_var($suc,FILTER_VALIDATE_INT)){
    $error["error"] = "No se ha seleccionado sucursal o la sucursal no es valida";
    echo json_encode($error);
    return false;
}

// #region verificacion (Verifica si cuenta con los permisos)
// $rol->getPermissionControl($_SESSION['controles'],Controls::ot->value,$suc);
// $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

// if(!$modifica){
//     $error['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
//     echo json_encode($error);
//     return false;
// }
// #endregion

#region Asignacion de Folio
if (isset($_POST["setFolio"]) && @$_POST["setFolio"] == "true") {

    $fksucursal = (int) base64_decode($_POST['sucursal']);
    $fecha = Helper::val_input($_POST['fecha']);
    $query = "AND fksucursal = " . $fksucursal . "";

    if ($fksucursal != '') {
        if($_POST['fecha'] == ''){
            $resp["error"] = 'El campo fecha es obligatorio';
            echo json_encode($resp);
            return false;
        }
        $oFolio = new Folio("requisicion", "folio", $query);
        if ($oReq->AddFolio($oFolio->getFolio(), $fksucursal,$fecha)) {
            $resp["pkrequisicion"] = base64_encode($oReq->pkrequisicion);
            $resp["folio"] = $oFolio->getFolio();
            echo json_encode($resp);

            return true;
        }
    } else {
        $resp["error"] = 'No se ha seleccionado sucursal';
        echo json_encode($resp);
        return false;
    }
}
#endregion

if (!isset($_POST['requisicion']) && @$_POST['requisicion'] == "") {
    $error["error"] = "No se ha asignado ningún Folio";
}

$datos = array(
    "fkorden" => (int) base64_decode(@$_POST['orden']),
    "proyecto" => Helper::val_input(@$_POST['proyecto']),
    "fecha" => Helper::val_input($_POST['fecha']),
    "estado" => Helper::val_input($_POST['estado']),
    "clasificacion" => Helper::val_input(@$_POST['clasificacion']),
    "lugarent" => Helper::val_input(@$_POST['lugarent']),
    "observaciones" => trim($_POST['observaciones']),
    "solicita" => (int) base64_decode(@$_POST['solicita']),
    "recibe" => (int) base64_decode(@$_POST['recibe']),
    "autoriza" => (int) base64_decode(@$_POST['autoriza']),
    "pkrequisicion" => (int) base64_decode(@$_POST['requisicion'])
);

if (count($error) == 0) {

    if ($oReq->Update($datos)) {
        $edit = array();
        //Actualiza los registros
        if(isset($_POST['pda'])) {
            for ($i = 0; $i < count($_POST["pda"]); $i++) {
                $dataServ = array();
                if (isset($_POST['pkserv'][$i])) {
                    $dataServ = array(
                        "pkservrequisicion" => (int) base64_decode($_POST['pkserv'][$i]),
                        "pda" => Helper::float($_POST['pda'][$i]),
                        "cantidad" => Helper::float($_POST['cant'][$i]),
                        "unidad" => Helper::val_input($_POST['unidad'][$i]),
                        "descripcion" => trim($_POST['descripcion'][$i]),
                        "nparte" => Helper::val_input($_POST['nparte'][$i])

                    );

                    $oReq->updateServ($dataServ);
                }
            }
        }
        
        
        //Guarda los registros nuevos
        if(isset($_POST["pda"])) {
            for ($i = 0; $i < count($_POST["pda"]); $i++) {
                $dataServ = array(); //Se limpia el arreglo
                $dataServ = array(
                    "pda" => Helper::float($_POST['pda'][$i]),
                    "cantidad" => Helper::float($_POST['cant'][$i]),
                    "unidad" => Helper::val_input($_POST['unidad'][$i]),
                    "descripcion" => trim($_POST['descripcion'][$i]),
                    "nparte" => Helper::val_input($_POST['nparte'][$i]),
                    "fkrequisicion" => $datos["pkrequisicion"]
                );
                if (!isset($_POST['pkserv'][$i])) {
                    if ($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '' ||  $_POST["nparte"][$i] != '') {
                       
                        $oReq->AddServ($dataServ);
                    }
                }
            }
        
        }

        $msg['success'] = "Guardado Exitosamente";
        $msg['requisicion'] = base64_encode($datos['pkrequisicion']);
        echo json_encode($msg);
    }
} else {
    echo json_encode($error);
}