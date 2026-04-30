<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Ocompra.php";
include_once "../../class/Helper.php";
require_once("../../class/Folio.php");
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$oReq = new Ocompra(); //Se crea el objeto para la orden de compra
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
            $resp["error"] = 'El campo fecha orden es obligatorio';
            echo json_encode($resp);
            return false;
        }
        $oFolio = new Folio("ocompra", "folio", $query);
        if ($oReq->AddFolio($oFolio->getFolio(), $fksucursal,$fecha)) {
            $resp["pkocompra"] = base64_encode($oReq->pkocompra);
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

if (!isset($_POST['ocompra']) && @$_POST['ocompra'] == "") {
    $error["error"] = "No se ha asignado ningún Folio";
}

$datos = array(
    "fechaorden" => Helper::val_input($_POST['fechaorden']),
    "fechaent" => Helper::val_input($_POST['fechaentrega'] ?? ''),
    "fkrequisicion" => (int) base64_decode($_POST['nrequisicion'] ?? 0),
    "fkorden" => (int) base64_decode($_POST['orden'] ?? 0),
    "moneda" => Helper::val_input($_POST['moneda']),
    "condpago" => Helper::val_input($_POST['condpago']),
    "fkproveedor" => (int) base64_decode($_POST['proveedor'] ?? 0),
    "rfc" => Helper::val_input($_POST['rfc']),
    "direccion" => Helper::val_input($_POST['direccion']),
    "contacto" => Helper::val_input($_POST['contacto']),
    "telefono" => Helper::val_input($_POST['telefono']),
    "correo" => Helper::val_input($_POST['correo']),
    "nproveedor" => Helper::val_input($_POST['nproveedor']),
    "direntrega" => Helper::val_input($_POST['direntrega']),
    "fkecomprador" => (int) base64_decode($_POST['comprador'] ?? 0),
    "telefono2" => Helper::val_input($_POST['telefono2']),
    "email" => Helper::val_input($_POST['email']),
    "observaciones" => trim($_POST['observaciones']),
    "diascredito" => Helper::val_input($_POST['credito']),
    "fkesolicita" => (int) base64_decode($_POST['solicita'] ?? 0),
    "fkeautoriza" => (int) base64_decode($_POST['autoriza'] ?? 0),
    "estado" => Helper::val_input($_POST['estado']),
    "importe" => Helper::float($_POST['inputSubtotal']),
    "descto" => Helper::float($_POST['descto']),
    "iva" => Helper::float($_POST['iva']),
    "total" => Helper::float($_POST['inputTotal']),
    "pkocompra" => (int) base64_decode($_POST['ocompra'] ?? 0)
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
                        "pkservocompra" => (int) base64_decode($_POST['pkserv'][$i]),
                        "pda" => Helper::float($_POST['pda'][$i]),
                        "cantidad" => Helper::float($_POST['cant'][$i]),
                        "unidad" => Helper::val_input($_POST['unidad'][$i]),
                        "descripcion" =>trim($_POST['descripcion'][$i]),
                        "preciounit" => Helper::float($_POST['punit'][$i]),
                        "subtotal" => Helper::float($_POST['importe'][$i])

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
                    "descripcion" =>trim($_POST['descripcion'][$i]),
                    "preciounit" => Helper::float($_POST['punit'][$i]),
                    "subtotal" => Helper::float($_POST['importe'][$i]),
                    "fkocompra" => $datos["pkocompra"]
                );
                if (!isset($_POST['pkserv'][$i])) {
                    if ($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '') {
                       
                        $oReq->AddServ($dataServ);
                    }
                }
            }
        
        }

        $msg['success'] = "Guardado Exitosamente";
        $msg['ocompra'] = base64_encode($datos['pkocompra']);
        echo json_encode($msg);
    }
} else {
    echo json_encode($error);
}