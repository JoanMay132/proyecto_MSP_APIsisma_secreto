<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';

    $rol = new Permisos();
    
    if($_SERVER['REQUEST_METHOD'] != "POST"){ return false;}
    $id = (int) base64_decode($_POST['data']);
    $id = filter_var($id,FILTER_VALIDATE_INT);
    $mensaje = [];
    
    if(!$id){
        return false;
    }

#region Verifica si cuenta con los permisos
    $rol->getPermissionControl($_SESSION['controles'],Controls::analisiscosto->value,base64_decode(@$_POST['branch']));
    $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

    if(!$modifica){
         return false;
    }
#endregion

    $tipo = $_POST['tipo'];

    $map = [
        'materiales' => ['path' => '../../controlador/Prematerial2.php','class' => 'Prematerial2'],
        'manoobra' => ['path' => '../../controlador/Premobra2.php','class' => 'Premobra2'],
        'maquinaria' => ['path' => '../../controlador/Premaquinaria2.php','class' => 'Premaquinaria2'],
        'servicios' => ['path' => '../../controlador/Preservicio2.php','class' => 'Preservicio2']
    ];

    if(isset($map[$tipo])){
        include_once($map[$tipo]['path']);
        $class = $map[$tipo]['class'];
        echo (new $class())->Delete($id);
    }else{
        return false;
    }
    

    




?>