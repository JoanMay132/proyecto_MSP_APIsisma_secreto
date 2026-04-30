<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Orden.php";
include_once "../../class/Helper.php";
require_once("../../class/Folio.php");
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$oOrden = new Orden();
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

#region verificacion (Verifica si cuenta con los permisos)
$rol->getPermissionControl($_SESSION['controles'],Controls::ot->value,$suc);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $error['error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($error);
    return false;
}
#endregion

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
        $oFolio = new Folio("orden", "folio", $query);
        if ($oOrden->AddFolio($oFolio->getFolio(), $fksucursal,$fecha)) {
            $resp["pkorden"] = base64_encode($oOrden->pkorden);
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

if (!isset($_POST['orden']) && @$_POST['orden'] == "") {
    $error["error"] = "No se ha asignado ningún Folio";
}

$datos = array(
    "cliente" => (int) base64_decode(@$_POST['cliente']),
    "solicito" => (int) base64_decode(@$_POST['solicito']),
    "depto" => Helper::val_input($_POST['depto']),
    "fecha" => Helper::val_input($_POST['fecha']),
    "diaentrega" => Helper::val_input($_POST['diaentrega']),
    "tipo" => Helper::val_input(@$_POST['tipo']),
    "observaciones" => trim($_POST['observaciones']),
    "cotizacion" => (int) base64_decode($_POST['cotizacion']),
    "auxiliar" => (int) base64_decode(@$_POST['auxiliar']),
    "enterado" => (int) base64_decode(@$_POST['enterado']),
    "pkorden" => (int) base64_decode(@$_POST['orden'])
);
$datos['tipo'] = $datos['tipo'] == '' ? 'normal' : $datos['tipo'];

if (count($error) == 0) {

    if ($oOrden->Update($datos)) {
        $edit = array();
        //Guarda los registros nuevos
        if(isset($_POST['pda'])) {
            for ($i = 0; $i < count($_POST["pda"]); $i++) {
                $dataServ = array();
                if (isset($_POST['pkserv'][$i])) {
                    $dataServ = array(
                        "pkservorden" => (int) base64_decode($_POST['pkserv'][$i]),
                        "pda" => Helper::float($_POST['pda'][$i]),
                        "cantidad" => Helper::float($_POST['cant'][$i]),
                        "unidad" => Helper::val_input($_POST['unidad'][$i]),
                        "descripcion" => trim($_POST['descripcion'][$i]),
                        "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i]),
                        "dibujo" => Helper::val_input($_POST['dibujo'][$i])

                    );
                    if ($_POST['pkserv'][$i] != '') {
                        array_push($edit,(int) base64_decode ($_POST['pkserv'][$i]));
                        $oOrden->updateServ($dataServ);
                    }
                }
            }
        }
        
        if(count($edit) > 0) {
            $params = array_merge([$datos['pkorden']], $edit);
            $oOrden->DelServAll($edit, $params);
        }
        

        //Guarda los registros nuevos
        if(isset($_POST["pda"])) {
            for ($i = 0; $i < count($_POST["pda"]); $i++) {
                $dataServ = array(); //Se limpia el arreglo
                $dataServ = array(
                    "pda" => Helper::float($_POST['pda'][$i] ?? 0),
                    "cantidad" => Helper::float($_POST['cant'][$i] ?? 0),
                    "unidad" => Helper::val_input($_POST['unidad'][$i] ?? ''),
                    "descripcion" => $_POST['descripcion'][$i] ?? '',
                    "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i] ?? ''),
                    "dibujo" => Helper::val_input($_POST['dibujo'][$i] ?? ''),
                    "fkorden" => $datos["pkorden"]
                );
                if (!isset($_POST['pkserv'][$i])) {
                    if ($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '' ||  $_POST["dibujo"][$i] != '') {
                       
                        $oOrden->AddServ($dataServ);
                    }
                } elseif ($_POST['pkserv'][$i] == '') {
                    $dataServ['fkorden'] = $datos["pkorden"];
                    $oOrden->AddServ($dataServ);
                }
            }
        
        }

        $msg['success'] = "Guardado Exitosamente";
        $msg['orden'] = base64_encode($datos['pkorden']);
        echo json_encode($msg);
    }
} else {
    echo json_encode($error);
}
