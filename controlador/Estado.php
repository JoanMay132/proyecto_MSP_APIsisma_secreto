<?php 
    class Estado extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}


        public function GetDataAll(){
           
            $query = self::$conexion->query('SELECT * FROM estados ORDER BY nombre');
            $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    


    }

?>