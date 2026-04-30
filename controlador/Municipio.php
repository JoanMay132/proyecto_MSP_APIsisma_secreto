
<?php 
    class Municipio extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}


        public function GetDataAll($estado){
           
            $query = self::$conexion->prepare('SELECT * FROM municipios WHERE id_estado= ? ORDER BY nombre');
            $query->execute(array($estado));
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    


    }

?>