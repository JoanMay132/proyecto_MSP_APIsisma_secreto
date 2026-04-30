
<?php 
    class Usercli extends Conexion{

        private static $conexion;

        public $pkcliente;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO usercli (titulo,nombre,cargo,fkdeptocli,fkcliente) VALUES (?,?,?,?,?) ');

            if($query->execute($data) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll($cliente){
           
            $query = self::$conexion->prepare('SELECT * FROM usercli WHERE fkcliente = ? ORDER BY nombre');
            $query->execute(array($cliente));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetData($user){
           
            $query = self::$conexion->prepare('SELECT * FROM usercli WHERE pkusercli = ?');
            $query->execute(array($user));
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        usercli 
                                                SET 
                                                    titulo = ?,
                                                    nombre = ?, 
                                                    cargo = ?,
                                                    fkdeptocli = ?
                                                WHERE pkusercli = ? ');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        public function Delete($user) : bool{
           
            $query = self::$conexion->prepare('DELETE FROM usercli WHERE pkusercli = ?');
            if($query->execute(array($user)) > 0){
                return true;
             }
             return false;

        }

        //Join Usercli y deptocli
        public function getJoinDepto($cliente){
           
            $query = self::$conexion->prepare('SELECT usercli.*,deptocli.pkdeptocli,deptocli.nombre AS nombredepto, deptocli.fkcliente FROM usercli LEFT JOIN deptocli ON usercli.fkdeptocli = deptocli.pkdeptocli WHERE usercli.fkcliente = ? ORDER BY usercli.nombre');
            $query->execute(array($cliente));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>