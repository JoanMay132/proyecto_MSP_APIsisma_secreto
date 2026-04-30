<?php @session_start();
include_once '../controlador/conexion.php';
include_once '../controlador/Usuario.php';
include_once 'sesion.php';
include_once 'Info.php';
include_once 'Permisos.php';
include_once 'Controles.php';

$oUsuario = new Usuario();
$rol = new Permisos();
$login = new Sesion();


    $mensaje = [];

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_agent = Info::obtener_info_navegador();
    $hostname = Info::get_hostname();
    $ip_address = Info::obtener_ip();

    if(!empty($username) && !empty($password)){  //Comprueba que no este vacio
        
        if($login->Login($username,$password)){
            
            if($login->getTipoUser() === 'ROOT'){
                $token = bin2hex(random_bytes(32));
                setcookie('sesion_usuario_mspapi', $token, [
                    'expires' => time() + 60 * 60 * 24 * 30, // Expira en 30 días
                    'path' => '/',
                    'domain' => '', // Ajusta si es necesario
                    'secure' => true, // Solo HTTPS
                    'httponly' => true, // No accesible vía JavaScript
                    'samesite' => 'Lax', // Protección CSRF
                ]);
                $_SESSION['id_usuario'] = $login->getId_usuario();
                $_SESSION['tipo_user'] = $login->getTipoUser();
                $_SESSION['sucursal'] = base64_encode(1);
                $_SESSION['name_user'] = "ROOT";
                if($rol->existPermission($login->getId_usuario())){
                    $_SESSION['controles'] = $rol->result;
                }

                $mensaje['ref'] = "main";
                echo json_encode($mensaje);
                return true;
            }

            if($login->getStatusUser() === 'BAJA' || $login->getStatusUser() === 'baja'){
                $mensaje['Error'] = "Usuario suspendido";
                echo json_encode($mensaje);
                return false;
            }
        
            
            if($login->findSesionForUser($login->getId_usuario(),$user_agent,$hostname)){ //Busca en la tabla sesiones el equipo y navegador
                    $token = $login->getToken_sesion(); //Obtiene el token registrado
                    if($login->getStatus_sesion() === 'aceptada' ){
                        if($login->getId_usuario() === $login->getUser_sesion()){
                            //Cambia el estado a ceptado, ya estaba autorizado
                            $login->updateSesion($login->getId_sesion(),'aceptada');

                            setcookie('sesion_usuario_mspapi', $token, [
                                'expires' => time() + 60 * 60 * 24 * 30, // Expira en 30 días
                                'path' => '/',
                                'domain' => '', // Ajusta si es necesario
                                'secure' => true, // Solo HTTPS
                                'httponly' => true, // No accesible vía JavaScript
                                'samesite' => 'Lax', // Protección CSRF
                            ]);

                            $result = $oUsuario->User_employee($login->getUser_sesion()); //Busca al usuario en join con el empleado
                            //Incia las sessiones
                            $_SESSION['name_user'] = $result['nombre'].' '.$result['apellidos'];
                            $_SESSION['id_usuario'] = $result['pkusuario'];
                            $_SESSION['tipo_user'] = $result['rol'];
                            $_SESSION['sucursal'] = base64_encode($result['fksucursal']);
                            if($rol->existPermission($login->getUser_sesion())){
                                $_SESSION['controles'] = $rol->result;
                            }

                            //Modulos de acceso
                            //header("location:../main");
                            $mensaje['ref'] = "main";
                        }
                      
                    }
                    else if($login->getStatus_sesion() === 'pendiente'){
                        
                        $mensaje['Pendiente'] = "Autorización en proceso, consulte con el administrador";
                    }else if($login->getStatus_sesion() === 'expirado'){
                        //si el usuario ya esta registrado en la sesion, actualiza el token y el status
                        if($login->getId_usuario() === $login->getUser_sesion()){
                            //Cambia el estado a ceptado, ya estaba autorizado
                            $login->updateSesion($login->getId_sesion(),'aceptada');
                            
                            setcookie('sesion_usuario_mspapi', $token, [
                                'expires' => time() + 60 * 60 * 24 * 30, // Expira en 30 días
                                'path' => '/',
                                'domain' => '', // Ajusta si es necesario
                                'secure' => true, // Solo HTTPS
                                'httponly' => true, // No accesible vía JavaScript
                                'samesite' => 'Lax', // Protección CSRF
                            ]);
                            

                            $result = $oUsuario->User_employee($login->getUser_sesion()); //Busca al usuario en join con el empleado
                            
                            //Incia las sessiones
                            $_SESSION['name_user'] = $result['nombre'].' '.$result['apellidos'];
                            $_SESSION['id_usuario'] = $result['pkusuario'];
                            $_SESSION['tipo_user'] = $result['rol'];
                            $_SESSION['sucursal'] = base64_encode($result['fksucursal']);
                            if($rol->existPermission($login->getUser_sesion())){
                                $_SESSION['controles'] = $rol->result;
                            }
                            $mensaje['ref'] = "main";

                        }else{
                            //si esta accediendo otro usuario en el mismo dispositivo que ya estaba aceptado vuelve a enviar la solicitud
                            $token = bin2hex(random_bytes(32));
                                if($login->setSesion($login->getId_usuario(),$token,$user_agent,$hostname,$ip_address)){
                                    $mensaje['Pendiente'] = "Se envio la solicitud de aprovacion de sesion, consulte con el administrador";
                                }
                            }
                    }
                    else if($login->getStatus_sesion() === 'rechazado'){
                        $mensaje['Error'] = "El administrador rechazo la solicitud de sesión";
                    }   
                    //Fin de busqueda en la tabla sesiones 
            }else{ 

                //Crea una nueva solicitud
                $token = bin2hex(random_bytes(32));
                if($login->setSesion($login->getId_usuario(),$token,$user_agent,$hostname,$ip_address)){
                    $mensaje['Pendiente'] = "Se envio la solicitud de aprovacion de sesion, consulte con el administrador";
                }
            }
        }else{
            $mensaje['Error'] = $login->mensaje;
            
        }
    }


    echo json_encode($mensaje);