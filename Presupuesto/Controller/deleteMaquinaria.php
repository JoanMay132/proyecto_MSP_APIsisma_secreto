<?php @session_start();
    include_once '../../controlador/conexion.php';
    include_once '../../controlador/Maquinaria.php';
    include_once '../../class/Permisos.php';
    include_once '../../class/Controles.php';

    $rol = new Permisos();
    $oMaquinaria = new Maquinaria();

    $pkmaquinaria = (int) base64_decode($_POST['maq']);
    $pkmaquinaria = filter_var($pkmaquinaria,FILTER_VALIDATE_INT);
    
    if(!$pkmaquinaria){
        return false;
    }
#region Verifica si cuenta con los permisos
$rol->getPermissionControl($_SESSION['controles'],Controls::maquinaria->value,base64_decode(@$_POST['branch']));
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;

if(!$modifica){
    return false;
}
#endregion

    echo $oMaquinaria->Delete($pkmaquinaria);


?>