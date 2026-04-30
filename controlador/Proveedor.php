
<?php 
    class Proveedor extends Conexion{

        private static $conexion;

        public $pkproveedor;
        public $nombre;
        public $telefono;
        public $correo;
        public $direccion;
        public $datbancario;
        public $rfc;
        public $fksucursal;
        public $nproveedor;
        public $ciudad;
        public $contacto;


        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(Proveedor $data): bool{
            $query = self::$conexion->prepare('INSERT INTO proveedor (nombre,telefono,correo,direccion,ciudad,datbancario,rfc,nproveedor,contacto,fksucursal) VALUES (?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array($data->nombre,$data->telefono,$data->correo,$data->direccion,$data->ciudad,$data->datbancario,$data->rfc,$data->nproveedor,$data->contacto,$data->fksucursal)) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM proveedor WHERE fksucursal = ? ');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetData($proveedor){
           
            $query = self::$conexion->prepare('SELECT proveedor.*,sucursal.pksucursal,sucursal.nombre as nsucursal FROM proveedor,sucursal WHERE pkproveedor = ? AND proveedor.fksucursal = sucursal.pksucursal');
            $query->execute(array($proveedor));
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Update(Proveedor $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE proveedor SET nombre = ?, telefono = ?, correo = ?, direccion = ?, ciudad = ?, datbancario = ?, rfc = ?, nproveedor = ?, contacto = ?,fksucursal = ? WHERE pkproveedor = ?');
            if($query->execute(array($data->nombre,$data->telefono,$data->correo,$data->direccion,$data->ciudad,$data->datbancario,$data->rfc,$data->nproveedor,$data->contacto,$data->fksucursal,$data->pkproveedor)) > 0){
                return true;
             }
             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM proveedor WHERE pkproveedor = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    }

?>