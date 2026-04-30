
<?php 
    class Servicios extends Conexion{

        private static $conexion;

        public $pkservicio;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}



        public function GetDataAll($sucursal,$catalogo){
           
            $query = self::$conexion->prepare('SELECT * FROM servicios WHERE servicios.fksucursal = :suc AND servicios.fkcatalogo = :cat ');
            $query->bindParam(':suc',$sucursal);
            $query->bindParam(':cat',$catalogo);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function GetData($pkcliente){
           
            $query = self::$conexion->prepare('SELECT * FROM servicios,gruposerv WHERE servicios.pkservicios = ? AND servicios.fkgruposerv = gruposerv.pkgruposerv');
            $query->execute(array("{$pkcliente}"));
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    



    }

?>