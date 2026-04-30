<?php 
    class Catlogo extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}


        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM catalogo WHERE fksucursal = ?  AND status = "activo" ');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }
        
        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    

    }

?>