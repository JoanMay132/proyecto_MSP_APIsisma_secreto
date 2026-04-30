<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Subcotizacion.php";
include_once "../../class/Helper.php";
require_once("../../class/SubFolio.php");
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();
$oCot= new Subcotizacion();

$msg = array();

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
$suc = (int) base64_decode($_POST['sucursal']);

if(!filter_var($suc,FILTER_VALIDATE_INT)){
    return false;
}

//Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::cotizacion->value,$suc);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $msg['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($msg);
    return false;
}
//Fin de verificación de permisos

//Se asigna folio
if(isset($_POST["Newfolio"]) && $_POST["Newfolio"] == true){
    $pkcotizacion = (int) base64_decode($_POST['pkcotizacion']);
    $folioCot = $_POST['folio'];
    $oFolio = new SubFolio("subcotizacion","folio","fkcotizacion","{$pkcotizacion}","{$folioCot}");
    $folio =  $oFolio->getFolio();
}

$datos = array(
    "nombre" => isset($_POST['nombre']) ? "COT ".Helper::val_input($_POST['nombre']) : "",
    "fecha" => Helper::val_input($_POST['fecha']),
    "fkcliente" =>(int) base64_decode($_POST['cliente']),
    "fkusercli" =>(int) base64_decode($_POST['solicito']),
    "attn" => (int) base64_decode($_POST['attn']),
    // "depto" => Helper::val_input($_POST['depto']),
    "titulo" => trim($_POST['titulo']),
    "cargo" => Helper::val_input($_POST['cargo']),
    "cotizo" => (int) base64_decode($_POST['cotizo']),
    "responsable" => (int) base64_decode($_POST['responsable']),
    "vigencia" => Helper::val_input($_POST['vigencia']),
    "ocompra" => Helper::val_input($_POST['ocompra']),
    "fpago" => Helper::val_input($_POST['fpago']),
    "credito" => Helper::val_input($_POST['credito']),
    "tentrega" => Helper::val_input($_POST['tentrega']),
    "lab" => Helper::val_input($_POST['lab']),
    "garantia" => Helper::val_input($_POST['garantia']),
    "datnormativos" => Helper::val_input($_POST['datnormativos'] ?? ''),
    "fabricacion" => Helper::val_input($_POST['fabricacion']),
    "pcalidad" => Helper::val_input($_POST['pcalidad']),
    "dattecnicos" => Helper::val_input($_POST['dattecnicos'] ?? ''),
    "doclegal" => Helper::val_input($_POST['doclegal']),
    "fcosto" => Helper::val_input($_POST['fcosto']),
    "area" => (int) base64_decode($_POST['area']),
    "factura1" => Helper::val_input($_POST['factura1']),
    "estado" => Helper::val_input($_POST['estado']),
    "factura2" => Helper::val_input($_POST['factura2']),
    "ffactura" => Helper::val_input($_POST['ffactura']),
    "observaciones" => Helper::val_input($_POST['observaciones']),
    "contenido" => Helper::float($_POST['cnacional']),
    "tmoneda" => Helper::val_input($_POST['tmoneda']),
    "tcambio" => Helper::val_input($_POST['tcambio']),
    "descto" => Helper::float($_POST['descto']),
    "iva" => Helper::float($_POST['iva']),
    "subtotal" => Helper::float($_POST['inputSubtotal']),
    "total" => Helper::float($_POST['inputTotal']),
    "cotizacion" => (int) base64_decode($_POST['pkcotizacion']),
    "sucursal" => (int) base64_decode($_POST['sucursal'])
);

//Comprueba si el folio es un nuevo registro o una actualizacion
$datos['folio'] = isset($_POST['Newfolio'] ) && $_POST['Newfolio'] == true ? $folio : Helper::val_input($_POST['folio']);

if(isset($_POST['Newfolio']) && $_POST['Newfolio'] == true)
{
   if($oCot->Add($datos)){
        $msg['success'] = 'Subcotizacion guardada';
        $msg['cotizacion'] = base64_encode($oCot->pksubcotizacion);
   }
}
else
{
    if($_POST['pksubcotizacion'] == ""){
        $msg['error'] = "EL FOLIO NO EXISTE";
        echo json_encode($msg);
        return false;
    }
    $datos['pksubcotizacion'] =  (int) base64_decode($_POST['pksubcotizacion']);
    if($oCot->Update($datos)){ //Actualiza los datos de la cotizacion

        //Actualiza los datos registrados
        $dataServReg = array(); //Se crea el array
        if(isset($_POST["pkservcotizacion"])){
            for($i = 0 ; $i < count($_POST["pkservcotizacion"]); $i++){
                $dataServReg = array(
                    "pda" => Helper::float($_POST['pdaReg'][$i]),
                    "cantidad" => Helper::float($_POST['cantReg'][$i]),
                    "unidad" => Helper::val_input($_POST['unidadReg'][$i]),
                    "descripcion" =>trim($_POST['descripcionReg'][$i]),
                    "ttrabajo" => Helper::val_input($_POST['ttrabajoReg'][$i]),
                    "costo" => Helper::float($_POST['costoReg'][$i]),
                    "subtotal" => Helper::float($_POST['subtotalReg'][$i]),
                    "clave" => Helper::val_input($_POST['claveReg'][$i]),
                    "item" => Helper::val_input($_POST["itemReg"][$i]),
                    "contenido" => Helper::val_input($_POST["contenidoReg"][$i]),
                    "pksubservcotizacion" => (int) base64_decode($_POST["pkservcotizacion"][$i]),
                );
            
                $oCot->updateServ($dataServReg);
            }
        }

        //guarda los nuevos servicios que se agregen
        $dataServ = array(); 
        if(isset($_POST["pda"])){
            for($c = 0; $c < count($_POST["pda"]); $c++){
                if($_POST["pda"][$c] != '' || $_POST["cant"][$c] != '' ||  $_POST["unidad"][$c] != '' || $_POST["descripcion"][$c] != '' || $_POST["ttrabajo"][$c] != '' ||  $_POST["costo"][$c] != '' || $_POST["clave"][$c] != '' || $_POST["item"][$c] != '')
                {
                    $dataServ = array(
                        "pda" => Helper::float($_POST['pda'][$c]),
                        "cantidad" => Helper::float($_POST['cant'][$c]),
                        "unidad" => Helper::val_input($_POST['unidad'][$c]),
                        "descripcion" =>trim($_POST['descripcion'][$c]),
                        "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$c]),
                        "costo" => Helper::float($_POST['costo'][$c]),
                        "subtotal" => Helper::float($_POST['subtotal'][$c]),
                        "clave" => Helper::val_input($_POST['clave'][$c]),
                        "item" =>Helper::val_input( $_POST["item"][$c]),
                        "fksubcotizacion" => $datos['pksubcotizacion']
                    
                    );
                
                    $oCot->AddServ($dataServ);
                }
                
            }
        }

    } 
    $msg['success'] = 'Cambios guardados';
    $msg['cotizacion'] = base64_encode ($datos['pksubcotizacion']);
}

    
    echo json_encode($msg);
    return false;
?>