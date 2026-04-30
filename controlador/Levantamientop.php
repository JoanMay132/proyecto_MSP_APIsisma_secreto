
<?php 
    include_once("conexion.php");
    class Levantamiento extends Conexion{

        private static $conexion;
        public $idLevantamiento ;
        public $folio;
        private  $success = false;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}
        /*
        public function Add(array $datos){
            $query = self::$conexion->prepare('INSERT INTO levantamiento (folio,empresa,solicito,departamento) VALUES (?,?,?,?) ');

            if($query->execute(array($datos['folio'],$datos['empresa'],$datos['solicito'],$datos['departamento'])) > 0){
                $this->success = true;
            }

            return $this->success;
        }*/

        public function Add(string $folio){
            $query = self::$conexion->prepare('INSERT INTO levantamiento (folio) VALUES (?) ');

            if($query->execute(array($folio)) > 0){
                $this->idLevantamiento  = self::$conexion->lastInsertId();
                $this->success = true;
            }

            return $this->success;
        }

        public function GetDataAll(){
           
            $query = self::$conexion->query('SELECT * FROM levantamiento');
            return $query;
        }

        public function GetDataPrepare(array $array){
            $query = self::$conexion->prepare(" SELECT * FROM levantamiento WHERE folio = ?");
            $query->execute($array);

            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE levantamiento SET folio = ? , empresa = ? , solicito = ? , departamento = ? WHERE pklevantamiento = ?');

           if( $query->execute(array($data["folio"],$data["empresa"],$data["solicito"],$data["departamento"],$data["pklevantamiento"])) > 0 ){
                return $this->success = true;
           }
           return $this->success;

        }

        public function addService($data) : bool{
            $query = self::$conexion->prepare('INSERT INTO serv_levantamiento (pda,cant,unidad,descripcion,costo,dibujo,fklevantamiento) VALUES (?,?,?,?,?,?,?)');

           if( $query->execute($data) > 0 ){
                return $this->success = true;
           }
           
           return $this->success;
        }

        public function UpdateService(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE serv_levantamiento SET pda = ? , cant = ? , unidad = ? , descripcion = ?, costo = ?, dibujo = ? WHERE pkservlevantamiento = ?');

           if( $query->execute(array($data)) > 0 ){
                return $this->success = true;
           }
           return $this->success;

        }

        public function GetServiceAll($lev){
           
            $query = self::$conexion->prepare(" SELECT * FROM serv_levantamiento WHERE fklevantamiento = ? ORDER BY pda");
            $query->execute(array($lev));

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteService($pkservicio) : bool{
            $query = self::$conexion->prepare("DELETE FROM serv_levantamiento WHERE pkservlevantamiento = ? ");
            if($query->execute(array($pkservicio))> 0){
                return $this->success = true;
            }
            return $this->success;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>