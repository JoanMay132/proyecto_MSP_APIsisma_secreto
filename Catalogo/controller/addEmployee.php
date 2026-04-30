<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Employee.php";
include_once "../../class/Helper.php";
include_once "../../class/Imagen.php";
include_once '../../class/Permisos.php';
include_once '../../class/Controles.php';

$rol = new Permisos();

$obimg = new Imagen();
$error = array();

if(empty($_POST['nombre']) || empty($_POST['apellidos']) || empty($_POST['sucursal']) || empty($_POST['ingreso']))
{
    $error["campos_requeridos"] = "Los campos marcados con * son requeridos";    
}

$sucursal =(int) base64_decode($_POST['sucursal']);
if(!filter_var($sucursal,FILTER_VALIDATE_INT)){
    $error["Dato_incorrecto"] = "La sucursal no es valida";
}

#region Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::empleado->value,$sucursal);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    $error['Error'] = 'NO TIENES PERMISO PARA REALIZAR ESTA ACCIÓN EN LA SUCURSAL SELECCIONADA';
    echo json_encode($error);
    return false;
}
#endregion Fin de verificacion

$destination = '';
if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ''){
  
    // Detalles del archivo
    $fileName = $_FILES['foto']['name'];
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileError = $_FILES['foto']['error'];
    $tipo  = $_FILES['foto']['type'];
    
  
    // Verifica si no hubo errores durante la carga
    if($fileError === 0){
        
        if($obimg->validar_img($tipo)){
        // Ruta donde se almacenará la imagen (puede ser una carpeta específica) 
        $destination = '../../dependencias/img/employees/'.uniqid().'.'.'png';
  
        // Mueve el archivo temporal a la ubicación deseada
        //move_uploaded_file($fileTmpName, $destination);
        @copy($fileTmpName, $destination);

        }else{
            $error["Foto"] = "Error al cargar la foto, archivo no valido";
        }
    }
}

$datos = array(
    "nombre" => Helper::val_input($_POST['nombre']),
    "apellidos" => Helper::val_input($_POST['apellidos']),
    "direccion" => Helper::val_input($_POST['direccion']),
    "municipio" => Helper::val_input($_POST['municipio']),
    "estado" => Helper::val_input($_POST['estado']),
    "cp" => Helper::val_input($_POST['cp']),
    "curp" => Helper::val_input($_POST['curp']),
    "rfc" => Helper::val_input($_POST['rfc']),
    "nss" => Helper::val_input($_POST['nss']),
    "puesto" => Helper::val_input($_POST['puesto']),
    "sucursal" => Helper::val_input($sucursal),
    "ingreso" => Helper::val_input($_POST['ingreso']),
    "foto" => "$destination"
);



if(count($error) === 0){
   
    $OEmployee = new Employee();
    if($OEmployee->Add($datos)){        

        $data = array(
            "Mensaje" => "Guardado Exitoso",
            "pkemployee" => base64_encode((int)$OEmployee->pkemployee)
        );

        echo json_encode($data);

    }
   
    
    //echo json_encode("Guardado exitoso", JSON_PRETTY_PRINT);
    
}else{
    echo json_encode($error);
}
?>