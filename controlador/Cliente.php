
<?php 
    class Cliente extends Conexion{

        private static $conexion;

        public $pkcliente;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add($data): bool{
            $query = self::$conexion->prepare('INSERT INTO cliente (nombre,direccion,fkestado,fkmunicipio,pais,rfc,cp,telefono,correo1,correo2,imagen,fksucursal,moneda) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array($data['cliente'],$data['direccion'],$data['estado'],$data['municipio'],$data['pais'],$data['rfc'],$data['cp'],$data['tel'],$data['correo1'],$data['correo2'],$data['img'],$data['sucursal'],$data['moneda'])) > 0){
                $this->pkcliente  = self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM cliente WHERE fksucursal = ? ORDER BY nombre');
            $query->execute(array("{$sucursal}"));
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function GetData($pkcliente){
           
            $query = self::$conexion->prepare('SELECT cliente.*,sucursal.pksucursal,sucursal.nombre AS namesuc FROM cliente,sucursal WHERE pkcliente = ? AND cliente.fksucursal = sucursal.pksucursal');
            $query->execute(array("{$pkcliente}"));
            return $query->fetch(PDO::FETCH_ASSOC);
        }



        public function UpdateData($data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        cliente 
                                                SET 
                                                    nombre = ?, 
                                                    direccion = ?, 
                                                    fkestado = ?, 
                                                    fkmunicipio = ?,
                                                    pais = ?,
                                                    rfc = ?,
                                                    cp = ?,
                                                    telefono = ?,
                                                    correo1 = ?,
                                                    correo2 = ?,
                                                    imagen = ?,
                                                    fksucursal = ?, 
                                                    moneda = ?
                                                WHERE pkcliente = ?');

           if( $query->execute(array($data['cliente'],$data['direccion'],$data['estado'],$data['municipio'],$data['pais'],$data['rfc'],$data['cp'],$data['tel'],$data['correo1'],$data['correo2'],$data['img'],$data['sucursal'],$data['moneda'],$data['pkcliente'])) > 0 ){
                return true;
           }
           return false;

        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }

 
        /*
        public function deleteService($pkservicio) : bool{
            $query = self::$conexion->prepare("DELETE FROM serv_levantamiento WHERE pkservlevantamiento = ? ");
            if($query->execute(array($pkservicio))> 0){
                return $this->success = true;
            }
            return $this->success;
        }*/
        

    }

?>