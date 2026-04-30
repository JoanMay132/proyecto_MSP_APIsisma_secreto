
<?php 
    class Usuario extends Conexion{

        private static $conexion;

        public $pkusuario;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function addUser($data){
           
            $query = self::$conexion->prepare('INSERT INTO usuario (usuario,password,correo,status,rol) VALUES (?,?,?,?,?)');
            if($query->execute(array($data['usuario'],$data['password'],$data['correo'],"ALTA",$data['rool']))){
                $this->pkusuario = self::$conexion->lastInsertId();
                return true;
            }

            return false;
           
        }
        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM usuario WHERE fksucursal = ? ORDER BY nombre');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }
        public function GetData($usuario){
           
            $query = self::$conexion->prepare('SELECT * FROM usuario WHERE usuario = ?');
            $query->execute(array($usuario));
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetDataUser($usuario){
           
            $query = self::$conexion->prepare('SELECT * FROM usuario WHERE pkusuario = ?');
            $query->execute(array($usuario));
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function updateUsuario($data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        usuario
                                                SET 
                                                    usuario = ?
                                                WHERE pkusuario= ? ');

            if( $query->execute(array($data['usuario'],$data['pkusuario'])) > 0 ){
                return true;
            }
            return false;
        }

        public function updatePassword($data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        usuario
                                                SET 
                                                    password = ?
                                                WHERE pkusuario= ? ');

            if( $query->execute(array($data['password'],$data['pkusuario'])) > 0 ){
                return true;
            }
            return false;
        }

        public function updateEmail($data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        usuario
                                                SET 
                                                    correo = ?
                                                WHERE pkusuario= ? ');

            if( $query->execute(array($data['correo'],$data['pkusuario'])) > 0 ){
                return true;
            }
            return false;
        }

        public function updateRol($data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        usuario
                                                SET 
                                                    rol = ?,
                                                    status = ?
                                                WHERE pkusuario= ? ');

            if( $query->execute(array($data['rool'],$data['status'],$data['pkusuario'])) > 0 ){
                return true;
            }
            return false;
        }



        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        usuario
                                                SET 
                                                    usuario = ?,
                                                    password = ?, 
                                                    correo = ?,
                                                    status = ?,
                                                    rool = ?
                                                WHERE pkusuario= ? ');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        //Inner join de usuraios y empleados
        public function User_employee($usuario){
                $query = self::$conexion->prepare("SELECT 
                    empleado.nombre,
                    empleado.apellidos,
                    empleado.fksucursal,
                    usuario.*
                    FROM usuario INNER JOIN empleado ON usuario.pkusuario = :user AND usuario.pkusuario = empleado.fkusuario
                ");

                $query->bindParam(":user",$usuario);
                if($query->execute() > 0){
                    $query = $query->fetch(PDO::FETCH_ASSOC);
                   return $query;
                }
        }

        public function Delete($user) : bool{
           
            $query = self::$conexion->prepare('DELETE FROM usuario WHERE pkusuario = ?');
            if($query->execute(array($user)) > 0){
                return true;
             }
             return false;

        }

         public function __destruct() {
           self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }

        
        

    }

?>