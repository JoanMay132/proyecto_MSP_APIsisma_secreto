<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Cliente.php";
include_once "../../controlador/Deptocli.php";
include_once "../../class/Helper.php";
include_once "../../class/Imagen.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$obimg = new Imagen();

$error = array();

//Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::clientes->value,$_POST['sucursal']);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $error['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($error);
    return false;
}
//Fin de verificacion

if(empty($_POST['cliente']) || 
   empty($_POST['estado']) ||
   empty($_POST['municipio'])||
   empty($_POST['pais'])
   ){
    $error["campos_requeridos"] = "Los campos marcados con * son requeridos";
    
}

if(!empty($_POST["correo1"]) && filter_var($_POST["correo1"], FILTER_VALIDATE_EMAIL)){
    $error["correo1"] = "El correo no es valido";
}
if(!empty($_POST["correo2"]) && filter_var($_POST["correo2"], FILTER_VALIDATE_EMAIL)){
    $error["correo2"] = "El correo no es valido";
}

$destination = '';
if(isset($_FILES['imagen'])){
  
    // Detalles del archivo
    $fileName = $_FILES['imagen']['name'];
    $fileTmpName = $_FILES['imagen']['tmp_name'];
    $fileSize = $_FILES['imagen']['size'];
    $fileError = $_FILES['imagen']['error'];
    $tipo  = $_FILES['imagen']['type'];
    
  
    // Verifica si no hubo errores durante la carga
    if($fileError === 0){
        
        if($obimg->validar_img($tipo)){
        // Ruta donde se almacenará la imagen (puede ser una carpeta específica) 
        $destination = '../../dependencias/img/clientes/'. $fileName;
  
        // Mueve el archivo temporal a la ubicación deseada
        //move_uploaded_file($fileTmpName, $destination);
       @copy($fileTmpName, $destination);
        }else{
            $error["Imagen"] = "Error al cargar la imagen, archivo no valido";
        }
    }
}

$datos = array(
    "cliente" => Helper::val_input($_POST['cliente']),
    "direccion" => Helper::val_input(($_POST['direccion'])),
    "estado" => Helper::val_input(($_POST['estado'])),
    "municipio" => Helper::val_input(($_POST['municipio'] ?? 0)),
    "pais" => Helper::val_input(($_POST['pais'])),
    "rfc" => Helper::val_input(($_POST['rfc'])),
    "cp" => Helper::val_input(($_POST['cp'])),
    "tel" => Helper::val_input(($_POST['tel'])),
    "correo1" => Helper::val_correo(($_POST['correo1'])),
    "correo2" => Helper::val_correo(($_POST['correo2'])),
    "sucursal" => Helper::val_input($_POST['sucursal']),
    "moneda" => Helper::val_input($_POST['moneda']),
    "img" => "$destination"
);



if(count($error) === 0){
   
    $obcli = new Cliente();
    if($obcli->Add($datos) && count($_POST["ndepto"]) > 0 ){
        //Creamos el objeto
        $deptocli = new Deptocli();

        for($i = 0; $i < count($_POST["ndepto"]); $i++){
            //Guardamos los departamentos que no estan vacios
            if(!empty($_POST["ndepto"][$i]))  $deptocli->Add(array($_POST['ndepto'][$i],$obcli->pkcliente));
        } 
    }
    $data = array(
        "Mensaje" => "Guardado Exitoso",
        "pkcliente" => (int)$obcli->pkcliente
    );
    echo json_encode($data);
    //echo json_encode("Guardado exitoso", JSON_PRETTY_PRINT);
    
}else{
    echo json_encode($error);
}
?>