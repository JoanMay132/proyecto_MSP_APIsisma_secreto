<?php @session_start();
    include_once "../controlador/conexion.php";
    include_once "../controlador/Controles.php";
    include_once "../controlador/Sucursal.php";

    
    if(isset($_GET['key'])){
        $key = trim($_GET['key']);
        if(!password_verify($key,'$2y$10$IXsO8i3HmTRncCAgbVzLdutFU2/PZEiV/ukC0ujwPwqJe3PPhIIfu')){
            echo "Llave incorrecta";
            return false;
        }
    }else if(!isset($_SESSION['tipo_user']) || @$_SESSION['tipo_user'] != 'ROOT'){
        header('Location:../main');
    }

    $oControles = new Controles();
    $oSuc = new Sucursal();
    $conteo = 0;
    $permisos = 0;

    $usuario_id = 1;
    foreach ($oSuc->GetDataAll() as $sucursal) { //Recorremos sucursales
        foreach ($oControles->getControls() as $control) { //Buscamos todos los controles
            foreach ($oControles->getOperations() as $operacion) { //Recorrido de las operaciones
                if(!$oControles->getPermissionForUser($usuario_id,$control['pkcontrol'],$sucursal['pksucursal'],$operacion['pkoperacion'])){

                    $data = array(
                        "control" => $control['pkcontrol'],
                        "sucursal" => $sucursal['pksucursal'],
                        "usuario" => $usuario_id,
                        "operacion" => $operacion['pkoperacion']
                
                    );
                    if($oControles->addPermission($data)){
                        $conteo++;
                    }
                    
                }else{
                    $permisos++;
                }
              
            }
            
        }
    }

    if(isset($_POST['refresh']) && (@$_POST['refresh'])){
            $mensaje['success'] = "Se Asignaron: ".$conteo." Nuevos permisos<br> Cuentas con: ".$permisos." Permisos";
            echo json_encode($mensaje);
            return true;

    }

echo "Se Asignaron: ".$conteo." Permisos <br>";
echo "Cuentas con: ".$permisos." Permisos";

    

    

    
