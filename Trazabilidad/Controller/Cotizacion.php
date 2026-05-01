<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Cotizacion.php";
require_once "../../class/Folio.php";
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();
$oCot= new Cotizacion();
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
//Fin de verificacion


//Se asigna folio
if(isset($_POST["setFolio"]) && $_POST["setFolio"] === "true"){

    $fksucursal = (int) base64_decode($_POST['sucursal']);
    $fecha = Helper::val_input($_POST['fecha']);
    $iva = Helper::float($_POST['iva']);
    $query = "AND fksucursal = ".$fksucursal."";

    if ($fksucursal != '') {
        if($_POST['fecha'] == ''){
            $msg["error"] = 'El campo fecha es obligatorio';
            echo json_encode($msg);
            return false;
        }

        $oFolio = new Folio("cotizacion","folio",$query);
        if($oCot->AddFolio($oFolio->getFolio(),$fksucursal,$fecha,$iva)){
            $msg['cotizacion'] = base64_encode( $oCot->pkcotizacion);
            $msg["folio"] = $oFolio->getFolio();
            echo json_encode($msg);

            return true;
        }

        $msg["error"] = 'No se pudo asignar folio';
        echo json_encode($msg);
        return false;
    }else{
        $msg["error"] = 'No se ha seleccionado sucursal';
        echo json_encode($msg);
        return false;
    }
}

if(!isset($_POST['cotizacion']) && @$_POST['cotizacion'] == ""){
    $msg["error_folio"] = "No se ha asignado ningún Folio";
    echo json_encode($msg);
    return false;
}


$datos = array(
    // "folio" =>Helper::val_input($_POST['folio']),
    "fecha" => Helper::val_input($_POST['fecha']),
    "fkrevision" =>(int) base64_decode($_POST['revision'] ?? 0),
    "fkcliente" =>(int) base64_decode($_POST['cliente']),
    "fkusercli" =>(int) base64_decode($_POST['solicito']),
    "attn" => (int) base64_decode($_POST['attn']),
    //"depto" => Helper::val_input($_POST['depto']),
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
    "revision" => (int) base64_decode($_POST['revision'] ?? 0),
    "sucursal" => (int) base64_decode($_POST['sucursal'])
);


// if(!isset($_POST['pkcotizacion'])){
    
//     if($oCot->Add($datos)){

//         //Guarda los registros nuevos
//         for($i = 0; $i < count($_POST["pda"]); $i++)
//         {
//             $dataServ = array(); 
//             if($_POST["pda"][$i] != '' || $_POST["cant"][$i] != '' ||  $_POST["unidad"][$i] != '' || $_POST["descripcion"][$i] != '' || $_POST["ttrabajo"][$i] != '' ||  $_POST["costo"][$i] != '' || $_POST["clave"][$i] != '' || $_POST["item"][$i] != '')
//             {
//                 $dataServ = array(
//                     "pda" => Helper::float($_POST['pda'][$i]),
//                     "cantidad" => Helper::float($_POST['cant'][$i]),
//                     "unidad" => Helper::val_input($_POST['unidad'][$i]),
//                     "descripcion" =>trim($_POST['descripcion'][$i]),
//                     "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$i]),
//                     "costo" => Helper::float($_POST['costo'][$i]),
//                     "subtotal" => Helper::float($_POST['subtotal'][$i]),
//                     "clave" => Helper::val_input($_POST['clave'][$i]),
//                     "item" =>Helper::val_input( $_POST["item"][$i]),
//                     "fkcotizacion" => $oCot->pkcotizacion 
                   
//                 );
               
//                 $oCot->AddServ($dataServ);
                
//             }
//         }
//     }
// }
// else
// {
    // if( $_POST['pkcotizacion'] == ""){
    //     $msg['error'] = "EL FOLIO NO EXISTE";
    //     echo json_encode($msg);
    //     return false;
    // }

    $datos['pkcotizacion'] =  (int) base64_decode($_POST['cotizacion']);
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
                    "pkservcotizacion" => (int) base64_decode($_POST["pkservcotizacion"][$i]),
                );
            
                $oCot->updateServ($dataServReg);
            }
        }

        //guarda los nuevos servicios que se agregen
        $dataServ = array();
        if(isset($_POST['pda'])){ 
            for($c = 0; $c < count($_POST["pda"]); $c++){
                if($_POST["pda"][$c] != '' || $_POST["cant"][$c] != '' ||  $_POST["unidad"][$c] != '' || $_POST["descripcion"][$c] != '' || $_POST["ttrabajo"][$c] != '' ||  $_POST["costo"][$c] != '' || $_POST["clave"][$c] != '' || $_POST["item"][$c] != '')
                {
                    $dataServ = array(
                        "pda" => Helper::val_input($_POST['pda'][$c]),
                        "cantidad" => Helper::val_input($_POST['cant'][$c]),
                        "unidad" => Helper::val_input($_POST['unidad'][$c]),
                        "descripcion" =>trim($_POST['descripcion'][$c]),
                        "ttrabajo" => Helper::val_input($_POST['ttrabajo'][$c]),
                        "costo" => Helper::float($_POST['costo'][$c]),
                        "subtotal" => Helper::float($_POST['subtotal'][$c]),
                        "clave" => Helper::val_input($_POST['clave'][$c]),
                        "item" =>Helper::val_input( $_POST["item"][$c]),
                        "fkcotizacion" => $datos['pkcotizacion']
                    
                    );
                
                    $oCot->AddServ($dataServ);
                }
                
            }
        }

    } 
    $msg['cotizacion'] = base64_encode ($datos['pkcotizacion']);
    $msg['success'] = "Guardado Exitosamente";
    echo json_encode($msg);
    return false;
//}
        
        // $msg['success'] = "Guardado Exitosamente";
        // $msg['cotizacion'] = base64_encode($oCot->pkcotizacion);
        // echo json_encode($msg);
    


?>