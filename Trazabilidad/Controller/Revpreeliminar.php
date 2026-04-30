<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Revpreeliminar.php";
include_once "../../class/Helper.php";
require_once "../../class/Folio.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();
$oRev = new Revpreeliminar();
$error = array();
$msg = array();
$resp = array();
$nameControl = Controls::revpreeliminar->value;

//Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],$nameControl,base64_decode(@$_POST['sucursal']));
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $error['Error_de_folio'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($error);
    return false;
}
//Fin de verificacion

//Se asigna folio
if(isset($_POST["setFolio"]) && $_POST["setFolio"] === "true"){

    $fksucursal = (int) base64_decode($_POST['sucursal']);
    $fecha = Helper::val_input($_POST['fecha']);
    $query = "AND fksucursal = ".$fksucursal."";

    if ($fksucursal != '') {
        if($_POST['fecha'] == ''){
            $resp["error"] = 'El campo fecha es obligatorio';
            echo json_encode($resp);
            return false;
        }

        $oFolio = new Folio("revpreeliminar","folio",$query);
        if($oRev->AddFolio($oFolio->getFolio(),$fksucursal,$fecha)){
            $resp['revision'] = base64_encode( $oRev->pkrevpreeliminar);
            $resp["folio"] = $oFolio->getFolio();
            echo json_encode($resp);

            return true;
        }
    }else{
        $resp["error"] = 'No se ha seleccionado sucursal';
        echo json_encode($resp);
        return false;
    }
}

if(!isset($_POST['revpreeliminar']) && @$_POST['revpreeliminar'] == ""){
    $error["Error_de_folio"] = "No se ha asignado ningún Folio";
}

$datos = array(
    "fkcliente" =>(int) base64_decode($_POST['cliente']),
    "fkusercli" =>(int) base64_decode($_POST['solicito']),
    "depto" => Helper::val_input($_POST['depto']),
    "proyecto" => trim($_POST['proyecto']),
    "fecha" => Helper::val_input($_POST['fecha']),
    "reqinsdoc" => Helper::val_input($_POST['reqinsp']),
    "reqlegales" => Helper::val_input($_POST['reqlegales']),
    "condpago" => Helper::val_input($_POST['condpago']),
    "desviacionexc" => Helper::val_input($_POST['desviacionexc']),
    "reqent" => Helper::val_input($_POST['reqent']),
    "reqespserv" => Helper::val_input($_POST['reqespserv']),
    "propcli" => Helper::val_input($_POST['propcli']),
    "fkeventas" => (int) base64_decode($_POST['venta']),
    "fkeproduccion" => (int) base64_decode($_POST['produccion']),
    "fkecalidad" => (int) base64_decode($_POST['calidad']),
    "fkemanufactura" => (int) base64_decode($_POST['manufactura'] ?? 0),
    "pkrevpreeliminar" => (int) base64_decode(@$_POST['revpreeliminar'])
);

if(count($error) == 0)
{
    
    if($oRev->Update($datos)){

        //Guarda los registros nuevos
        if(isset($_POST["pda"])){
            for($i = 0; $i < count($_POST["pda"]); $i++)
            {
                $dataServ = array(); 
                if($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '' ||  $_POST["costo"][$i] != '')
                {
                    $dataServ = array(
                        "pda" => Helper::float($_POST['pda'][$i]),
                        "cantidad" => Helper::float($_POST['cant'][$i]),
                        "unidad" => Helper::val_input($_POST['unidad'][$i]),
                        "descripcion" => trim($_POST['descripcion'][$i]),
                        "costo" => Helper::conver_float($_POST['costo'][$i]),
                        "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i]),
                        "fkrevpreeliminar" => $datos["pkrevpreeliminar"],
                        "item" => $_POST["item"][$i]
                    );
                
                    $oRev->AddServ($dataServ);
                    
                }
            }
        }

        //Actualiza los registros
        if(isset($_POST["pkservrevicionReg"])){
            for($c = 0; $c < count($_POST["pkservrevicionReg"]); $c++)
            {
                $dataServReg = array(); 
    
                    $dataServReg = array(
                        "pda" => Helper::float($_POST['pdaReg'][$c]),
                        "cantidad" => Helper::float($_POST['cantReg'][$c]),
                        "unidad" => Helper::val_input($_POST['unidadReg'][$c]),
                        "descripcion" => trim($_POST['descripcionReg'][$c]),
                        "costo" => Helper::conver_float($_POST['costoReg'][$c]),
                        "ttrabajo" => Helper::val_input($_POST['ttrabajoReg'][$c]),
                        "fkrevpreeliminar" => $datos["pkrevpreeliminar"],
                        "item" => trim($_POST['itemReg'][$c]),
                        "pkservrevicion" => (int) base64_decode($_POST['pkservrevicionReg'][$c])
                    );
                
                    $oRev->updateServ($dataServReg);
            }
        }


        $msg['success'] = "Guardado Exitosamente";
        $msg['revision'] = base64_encode($datos['pkrevpreeliminar']);
        echo json_encode($msg);
    }
}else{
    echo json_encode($error);
}


?>