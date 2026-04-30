
<?php 
    class Puesto extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add($data): bool{
            $query = self::$conexion->prepare('INSERT INTO puesto (nombre) VALUES (?) ');

            if($query->execute(array($data)) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll(){
           
            $query = self::$conexion->query('SELECT * FROM puesto ORDER BY nombre');
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }



        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        puesto 
                                                SET 
                                                    nombre = ? 
                                                WHERE pkpuesto = ?');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>