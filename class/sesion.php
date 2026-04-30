<?php
 class Sesion extends Conexion{

    private static $conexion;
    private string $usuario;

    private string $user_sesion;
    private string $tipo_user;
    private string $status_user = '';
    private string $status_sesion;
    private string $id_sesion;
    private string $id_usuario;
    public string $mensaje;
    private string $token_sesion = '';

    public array $result;

    function __construct(){
        self::$conexion = parent::getConexion();
    }

    public function Login($usuario,$password) : bool{
        $query = self::$conexion->prepare('SELECT * FROM usuario WHERE usuario = :user ');
        $query->bindParam(':user',$usuario);
        $query->execute();
        if ($query->rowCount() > 0) {
                $result = $query->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password,$result['password'])){
                        $this->id_usuario = $result['pkusuario'];
                        $this->usuario =  $result['usuario'];
                        $this->tipo_user =  $result['rol'];
                        $this->status_user =  $result['status'];
                            
                       return true;
                }
                $this->mensaje = "Usuario o contraseña incorrecto";
                return false;
        }
        $this->mensaje = "Usuario o contraseña incorrecto";
        return false;

    }

    public function findSesionForToken($token,$user_agent,$hostname){
        $query = self::$conexion->prepare('SELECT * FROM sesion WHERE token_sesion = :token AND user_agent = :user_agent /*AND hostname = :hostname*/ ');
        //$query->bindParam(':user',$usuario);
        $query->bindParam(':token',$token);
        $query->bindParam(':user_agent',$user_agent);
       // $query->bindParam(':hostname',$hostname);
        $query->execute();
        if ($query->rowCount() > 0) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $this->user_sesion = $result['fkusuario'];
            $this->token_sesion = $result['token_sesion'];
            $this->status_sesion = $result['status'];
            $this->id_sesion = $result['pksesion'];

        return true;
        }
        return false;
        
    }

    public function findSesionForUser($usuario,$user_agent,$hostname){
        $query = self::$conexion->prepare('SELECT * FROM sesion WHERE fkusuario = :user AND  user_agent = :user_agent /*AND hostname = :hostname */');
        $query->bindParam(':user',$usuario);
        //$query->bindParam(':token',$token);
        $query->bindParam(':user_agent',$user_agent);
        //$query->bindParam(':hostname',$hostname);
        $query->execute();
        if ($query->rowCount() > 0) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $this->user_sesion = $result['fkusuario'];
            $this->token_sesion = $result['token_sesion'];
            $this->status_sesion = $result['status'];
            $this->id_sesion = $result['pksesion'];

        return true;
        }
        return false;
        
    }

    public function setSesion($usuario,$token,$user_agent,$hostname,$ipaddress) : bool{
        $query = self::$conexion->prepare('INSERT INTO sesion (fkusuario,token_sesion,user_agent,hostname,ip_address,status) VALUES (?,?,?,?,?,?)');
            if($query->execute(array($usuario,$token,$user_agent,$hostname,$ipaddress,"pendiente"))){
                $this->status_sesion = "pendiente";
                $this->token_sesion = $token;
                    return true;
            }

            return false;
    }

    public function updateSesion($sesion,$status) : bool{
        $query = self::$conexion->prepare('UPDATE sesion SET status = :status WHERE pksesion = :sesion ');
        $query->bindParam(':status',$status);
        $query->bindParam(':sesion',$sesion);
        if ($query->execute() > 0) {
            return true;
        }

        return false;
    }

    public function GetData($sucursal,$status) : array{
        $query = self::$conexion->prepare('SELECT 
                empleado.nombre,
                empleado.apellidos,
                empleado.fksucursal,
                usuario.usuario,
                usuario.correo,
                usuario.status,
                usuario.rol,
                sesion.* FROM sesion
                        INNER JOIN empleado ON sesion.fkusuario = empleado.fkusuario AND sesion.status = :status AND empleado.fksucursal = :sucursal
                        INNER JOIN usuario ON empleado.fkusuario = usuario.pkusuario ');
        $query->bindParam(':sucursal',$sucursal);
        $query->bindParam(':status',$status);
        $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function deleteSesion($sesion){
        $query = self::$conexion->prepare('DELETE FROM sesion WHERE pksesion = :sesion');
        $query->bindParam(":sesion",$sesion);
        if($query->execute() > 0){
            return true;
        }

        return false;
    }


    public function getUserName(){
        return $this->usuario;
    }

    public function getTipoUser(){
        return $this->tipo_user;
    }

    public function getStatusUser(){
        return $this->status_user;
    }

    public function getStatus_sesion(){
        return $this->status_sesion;
    }

    public function getId_usuario(){
        return $this->id_usuario;
    }

    public function getId_sesion(){
        return $this->id_sesion;
    }

    public function getToken_sesion(){
        return $this->token_sesion;
    }

    public function getUser_sesion(){
        return $this->user_sesion;
    }

    public function __destruct() {
        self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
    }

 }
