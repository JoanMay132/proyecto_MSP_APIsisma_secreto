
<?php 
    class Deptocli extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add($data): bool{
            $query = self::$conexion->prepare('INSERT INTO deptocli (nombre,fkcliente) VALUES (?,?) ');

            if($query->execute($data) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll($cliente){
           
            $query = self::$conexion->prepare('SELECT * FROM deptocli WHERE fkcliente = ? ORDER BY nombre');
            $query->execute(array($cliente));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }



        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        deptocli 
                                                SET 
                                                    nombre = ? 
                                                WHERE pkdeptocli = ?');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        public function Delete($data) : bool{
            $query = self::$conexion->prepare('DELETE FROM deptocli WHERE pkdeptocli = ?');

           if( $query->execute(array($data)) > 0 ){
                return true;
           }
           return false;

        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>