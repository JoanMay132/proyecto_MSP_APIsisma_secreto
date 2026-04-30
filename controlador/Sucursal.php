
<?php 
    class Sucursal extends Conexion{

        private static $conexion;

        public $pksucursal;
        public $nombre;

        public $direccion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add($data): bool{
            $query = self::$conexion->prepare('INSERT INTO sucursal (nombre,direccion) VALUES (?,?) ');

            if($query->execute($data) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll(){
           
            $query = self::$conexion->query('SELECT * FROM sucursal');
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }



        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        sucursal 
                                                SET 
                                                    nombre = ? , 
                                                    direccion = ? 
                                                WHERE pksucursal = ?');

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