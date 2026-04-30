<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Mobra.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';

    $rol = new Permisos();
    $oMObra= new MObra();

    $pkoperario=(int) base64_decode($_POST['operario']);
    $pkoperario = filter_var($pkoperario,FILTER_VALIDATE_INT);
    
    if(!$pkoperario){
        return false;
    }

#region Verifica si cuenta con los permisos
    $rol->getPermissionControl($_SESSION['controles'],Controls::manobra->value,base64_decode(@$_POST['branch']));
    $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

    if(!$modifica){
        return false;
    }
#endregion

    echo $oMObra->Delete($pkoperario);


?>