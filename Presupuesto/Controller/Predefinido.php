<?php @session_start();

include_once "../../controlador/conexion.php";
spl_autoload_register(function ($class) {
    include_once "../../controlador/" . $class . ".php";
});
include_once "../../class/Helper.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';


$rol = new Permisos();

$oPre = new Predefinido();
$oMaterial = new Prematerial2();
$oMobra = new Premobra2();
$oMaquin = new Premaquinaria2();
$oServ = new Preservicio2();
$oForm = new Formulas();

$registro = false;
$mensaje = array();

if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}

$sub = @$_POST['sub'] ?? "";

// if(isset($_POST['accion']) && $_POST['accion'] == 'findServcot')
// {
//     $find =(int) base64_decode($_POST['valor']);
//     if($oPre->findServcot($find,$sub)){
//         echo json_encode(base64_encode($oPre->pkpresupuesto));
//         return true;
//     }

//     return false;
// }

$suc = (int) base64_decode($_POST['sucursal']);

if(!filter_var($suc,FILTER_VALIDATE_INT)){
    $mensaje["Error"] = "La sucursal no es valida";
    json_encode($mensaje); return false;
}

#region Permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::analisiscosto->value,$suc);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $mensaje['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($mensaje);
    return false;
}
#endregion


$datos = array(
    "fkcliente" =>(int) base64_decode($_POST['cliente'] ?? 0),
    "servicio" => Helper::val_input($_POST['inputServicio']),
    "fecha" =>Helper::val_input($_POST['fecha'] ?? ''),
    "solicita" =>Helper::val_input($_POST['solicita'] ?? ''),
    "textra" =>Helper::float($_POST['textra']),
    "utiempo" =>Helper::val_input($_POST['utiempo']),
    "costoextra" =>Helper::float($_POST['costoextra']),
    "htamenor" =>Helper::float($_POST['htamenor']),
    "segpersonal" =>Helper::float($_POST['segpersonal']),
    "importepcns" =>Helper::float($_POST['cnacional']),
    "cantmul" =>(int) filter_var($_POST['canmult'], FILTER_SANITIZE_NUMBER_INT),
    "cantdiv" =>(int) filter_var($_POST['cantdiv'], FILTER_SANITIZE_NUMBER_INT),
    "indirectos" =>Helper::float($_POST['indirectos']),
    "financiamiento" =>Helper::float($_POST['financiamiento']),
    "utilidad" =>Helper::float($_POST['utilidad']),
    "total" =>Helper::float($_POST['totalUnitario']),
    "descripcion" => Helper::val_input($_POST['descripcion']),
    "folio" => Helper::val_input($_POST['folio'] ?? ''),
    "pda" => Helper::float($_POST['pda'] ?? ''),
    "fksucursal" =>$suc,
    "fkservcot" =>(int) base64_decode($_POST['servicio'] ?? 0),
    "fkcotizacion" =>(int) base64_decode($_POST['cotizacion'] ?? 0)
);

//Datos de la formula para densidad de las placas
$dataM2 = [
    "pkformulam2" => (int) base64_decode($_POST['pkformulam2']),
    "espesor" => Helper::float($_POST['espesor']),
    "factor" => Helper::float($_POST['factor']),
    "km2" => Helper::float($_POST['km2'])
];


$datos['tipo'] = !empty($sub) ?  1 : 0;
if(!isset($_POST['presupuesto'])){
    if($oPre->Add($datos)){ 
        $presupuesto = $oPre->pkpresupuesto; 
        $registro = true; 
    }
}else{
    $datos['pkpresupuesto'] = (int) base64_decode($_POST['presupuesto']);
    if($oPre->UpdateData($datos)){ 
        $registro = true;
        $presupuesto = $datos['pkpresupuesto'];
     }
}
if($registro)
{
    //Guarda los materiales
    if(isset($_POST['nombre']) && count($_POST['nombre']) > 0)
    {
        for($c = 0; $c < count($_POST['nombre']);$c++){
            if(isset($_POST['prematerial'][$c])){ //Si encuentra registro, actualiza
                $nombre = Helper::val_input($_POST["nombre"][$c]);
                $cantidad = Helper::float($_POST['cantidad'][$c]);
                $unidad =(int) Helper::val_input($_POST["unidad"][$c]);
                $costo = Helper::float($_POST['costo'][$c]);
                $importe = Helper::float($_POST['importe'][$c]);
                $nacional = Helper::val_input($_POST['cn'][$c]);
                $material = (int) base64_decode($_POST['prematerial'][$c]);
                $oMaterial->UpdateData(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$material));
            }else
            { //En caso contrario agrega nuevo registro
                $nombre = Helper::val_input($_POST["nombre"][$c]);
                $cantidad = Helper::float($_POST['cantidad'][$c]);
                $unidad =(int) Helper::val_input($_POST["unidad"][$c]);
                $costo = Helper::float($_POST['costo'][$c]);
                $importe = Helper::float($_POST['importe'][$c]);
                $nacional = Helper::val_input($_POST['cn'][$c]);
                
                $oMaterial->Add(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$presupuesto));
            }
            
        } 
    }

    //Guarda la mano de obra
    if(isset($_POST['descripcionObra']) && count($_POST['descripcionObra']) > 0)
    {
        for($i = 0; $i < count($_POST['descripcionObra']);$i++){
            if(isset($_POST['premobra'][$i])){ 
                $nombre = Helper::val_input($_POST["descripcionObra"][$i]);
                $cantidad =  Helper::float($_POST['cantidadObra'][$i]);
                $unidad =(int) Helper::val_input($_POST["unidadObra"][$i]);
                $costo = Helper::float($_POST['costoObra'][$i]);
                $importe = Helper::float($_POST['importeObra'][$i]);
                $nacional = Helper::val_input($_POST['cnObra'][$i]);
                $pkmobra = (int) base64_decode($_POST['premobra'][$i]);
                $oMobra->UpdateData(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$pkmobra));
            }
            else{
                $nombre = Helper::val_input($_POST["descripcionObra"][$i]);
                $cantidad =  Helper::float($_POST['cantidadObra'][$i]);
                $unidad =(int) Helper::val_input($_POST["unidadObra"][$i]);
                $costo = Helper::float($_POST['costoObra'][$i]);
                $importe = Helper::float($_POST['importeObra'][$i]);
                $nacional = Helper::val_input($_POST['cnObra'][$i]);
                
                $oMobra->Add(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$presupuesto));
            }
        } 
    }

    //Guarda la maquinaria y equipo
    if(isset($_POST['descripcionMaquin']) && count($_POST['descripcionMaquin']) > 0)
    {
        for($x = 0; $x < count($_POST['descripcionMaquin']);$x++){
            if(isset($_POST['premaquinaria'][$x])){ 
                $nombre = Helper::val_input($_POST["descripcionMaquin"][$x]);
                $cantidad = Helper::float($_POST['cantidadMaquin'][$x]);
                $unidad =(int) Helper::val_input($_POST["unidadMaquin"][$x]);
                $costo = Helper::float($_POST['costoMaquin'][$x]);
                $importe = Helper::float($_POST['importeMaquin'][$x]);
                $nacional = Helper::val_input($_POST['cnMaquin'][$x]);
                $pkmaquin = (int) base64_decode($_POST['premaquinaria'][$x]);
                $oMaquin->UpdateData(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$pkmaquin));
            }else
            {
                $nombre = Helper::val_input($_POST["descripcionMaquin"][$x]);
                $cantidad = Helper::float($_POST['cantidadMaquin'][$x]);
                $unidad =(int) Helper::val_input($_POST["unidadMaquin"][$x]);
                $costo = Helper::float($_POST['costoMaquin'][$x]);
                $importe = Helper::float($_POST['importeMaquin'][$x]);
                $nacional = Helper::val_input($_POST['cnMaquin'][$x]);
                
                $oMaquin->Add(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$presupuesto));
            }
            
        } 
    }

    //Guarda servicio adicional
    if(isset($_POST['descripcionAdicional']) && count($_POST['descripcionAdicional']) > 0)
    {
        for($x = 0; $x < count($_POST['descripcionAdicional']);$x++){
            if(isset($_POST['preservicio'][$x])){
                $nombre = Helper::val_input($_POST["descripcionAdicional"][$x]);
                $cantidad = Helper::float($_POST['cantidadAdicional'][$x]);
                $unidad =(int) Helper::val_input($_POST["unidadAdicional"][$x]);
                $costo = Helper::float($_POST['costoAdicional'][$x]);
                $importe = Helper::float($_POST['importeAdicional'][$x]);
                $nacional = Helper::val_input($_POST['cnAdicional'][$x]);
                $pkadicional = $pkmaquin = (int) base64_decode($_POST['preservicio'][$x]);
                $oServ->UpdateData(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$pkadicional));
            }else
            {
                $nombre = Helper::val_input($_POST["descripcionAdicional"][$x]);
                $cantidad = Helper::float($_POST['cantidadAdicional'][$x]);
                $unidad =(int) Helper::val_input($_POST["unidadAdicional"][$x]);
                $costo = Helper::float($_POST['costoAdicional'][$x]);
                $importe = Helper::float($_POST['importeAdicional'][$x]);
                $nacional = Helper::val_input($_POST['cnAdicional'][$x]);
                
                $oServ->Add(array($nombre,$cantidad,$unidad,$costo,$importe,$nacional,$presupuesto));
            }
            
        } 
    }

    //Guarda los datos de los pesos de las placas
    if(isset($_POST['pkpeso']) && count($_POST['pkpeso']) > 0){
        for($p = 0; $p < count($_POST['pkpeso']); $p++){
            $dataFPeso = [
                "pkpeso" => (int) base64_decode($_POST['pkpeso'][$p]),
                "ancho" => Helper::float($_POST['ancho'][$p]),
                "longitud" => Helper::float($_POST['long'][$p]),
                "placa" => $_POST['placa'][$p],
                "kilom2" => Helper::float($_POST['kilom2'][$p]),
                "peso" => round(Helper::float($_POST['peso'][$p]),2)
            ];

            $oForm->updateFPeso($dataFPeso);
        }

    }

    //Guarda los datos de los pesos de los materiales redondos
    if(isset($_POST['pkpesomat']) && count($_POST['pkpesomat']) > 0){
        for($pm = 0; $pm < count($_POST['pkpesomat']); $pm++){
            $dataFPMat = [
                "pkpesomat" => (int) base64_decode($_POST['pkpesomat'][$pm]),
                "od" => Helper::float($_POST['od'][$pm]),
                "idmat" => Helper::float($_POST['idmat'][$pm]),
                "longmaterial" => Helper::float($_POST['longmaterial'][$pm]),
                "pmaterial" => round(Helper::float($_POST['pmaterial'][$pm]),2)
            ];

            $oForm->updateFPMaterial($dataFPMat);
        }

    }

    //Guarda la formula de calculo de kilos x m2
    $oForm->updateFM2($dataM2);

    $mensaje["Success"] = "Presupuesto Guardado";
    $mensaje["Presupuesto"]= base64_encode($presupuesto);
    
}else{
    $mensaje["Error"] = "Error al guardar el presupuesto";
}


echo json_encode($mensaje);