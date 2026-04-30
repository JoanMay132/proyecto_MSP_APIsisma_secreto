<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Adicionales.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';

    $rol = new Permisos();
    $oAdicional = new Adicionales();

    $pkadicional=(int) base64_decode($_POST['adicional']);
    $pkadicional = filter_var($pkadicional,FILTER_VALIDATE_INT);
    
    if(!$pkadicional){
        return false;
    }

    #region Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::adicionales->value,base64_decode(@$_POST['branch']));
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    return false;
}
#endregion

    echo $oAdicional->Delete($pkadicional);


?>