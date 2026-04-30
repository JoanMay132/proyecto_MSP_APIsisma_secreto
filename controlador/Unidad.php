
<?php 
    class Unidad extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}


        public function GetDataAll(){
           
            $query = self::$conexion->query('SELECT * FROM unidad');
            //$query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    


    }

?>