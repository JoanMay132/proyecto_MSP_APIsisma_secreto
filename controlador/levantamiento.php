
<?php 
    class Levantamiento extends Conexion{

        private static $conexion;
        public $pklevantamiento;
        public $folio;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}
        //Levantamiento
        /*
        public function AddFolio($folio,$sucursal): bool{
            $query = self::$conexion->prepare('INSERT INTO revpreeliminar (folio,fksucursal) VALUES (?,?) ');

            if($query->execute(array($folio,$sucursal)) > 0){
                $this->pklevantamiento= self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }*/

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO revpreeliminar (
                fkcliente,
                fkusercli,
                depto,
                proyecto,
                fecha,
                reqinsdoc,
                reqlegales,
                reqent,
                condpago,
                reqespserv,
                desviacionexc,
                propcli,
                fkeventas,
                fkeproduccion,
                fkecalidad,
                fkemanufactura,
                fksucursal
                ) VALUES (?,?,?,?,?,?,?) ');

            if($query->execute(array(
                $data["fkcliente"],
                $data["fkusercli"],
                $data["depto"],
                $data["proyecto"],
                $data["fecha"],
                $data["reqinsdoc"],
                $data["reqlegales"],
                $data["reqent"],
                $data["condpago"],
                $data["reqespserv"],
                $data["desviacionexc"],
                $data["propcli"],
                $data["fkeventas"],
                $data["fkeproduccion"],
                $data["fkecalidad"],
                $data["fkemanufactura"],
                $data["fksucursal"]
                
                )) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM levantamiento WHERE ksucursal = ? ');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM levantamiento WHERE pklevantamiento = :id ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Update(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE revpreeliminar SET 
                fkcliente = ?,
                fkusercli = ?,
                depto = ?,
                proyecto = ?,
                fecha = ?,
                reqinsdoc = ?,
                reqlegales = ?,
                reqent = ?,
                condpago = ?,
                reqespserv = ?,
                desviacionexc = ?,
                propcli = ?,
                fkeventas = ?,
                fkeproduccion = ?,
                fkecalidad = ?,
                fkemanufactura = ?
            WHERE pkrevpreeliminar = ? ');
            if($query->execute(array(
                $data["fkcliente"],
                $data["fkusercli"],
                $data["depto"],
                $data["proyecto"],
                $data["fecha"],
                $data["reqinsdoc"],
                $data["reqlegales"],
                $data["reqent"],
                $data["condpago"],
                $data["reqespserv"],
                $data["desviacionexc"],
                $data["propcli"],
                $data["fkeventas"],
                $data["fkeproduccion"],
                $data["fkecalidad"],
                $data["fkemanufactura"],
                $data["pkrevpreeliminar"]
            )) > 0){
                return true;
             }

             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM revpreeliminar WHERE pkrevpreeliminar = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;

        }

        //Join revision preeliminar
        public function GetDataJoin($data){
           
            $query = self::$conexion->prepare('SELECT revpreeliminar.*, cliente.pkcliente,cliente.nombre AS ncliente,empleado.pkempleado,empleado.nombre AS nempleado, empleado.apellidos FROM revpreeliminar 
            LEFT JOIN cliente ON revpreeliminar.fkcliente = cliente.pkcliente 
            LEFT JOIN empleado ON revpreeliminar.fkeventas = empleado.pkempleado WHERE revpreeliminar.fksucursal = ? ORDER BY folio DESC');
            $query->execute(array($data));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Servicio de revision preeliminar
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO servrevicion (pda,cantidad,unidad,descripcion,costo,fkrevpreeliminar,fkcatserv) VALUES (?,?,?,?,?,?,?) ');

            if($query->execute(array($data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["costo"],$data["fkrevpreeliminar"],$data["fkcatserv"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE servrevicion SET pda = ?,cantidad = ?,unidad = ?,descripcion = ?, costo = ?, fkrevpreeliminar = ?,fkcatserv = ? WHERE pkservrevicion = ?');

            if($query->execute(array(
                $data["pda"],
                $data["cantidad"],
                $data["unidad"],
                $data["descripcion"],
                $data["costo"],
                $data["fkrevpreeliminar"],
                $data["fkcatserv"],
                $data["pkservrevicion"]
            )) > 0){
               return true;
            }

            return false;
        }

        public function DeleteServ($servicio) : bool{
            $query = self::$conexion->prepare('DELETE FROM servrevicion WHERE pkservrevicion = ?');

           if( $query->execute(array($servicio)) > 0 ){
                return true;
           }
           return false;

        }

        public function GetDataAllServ($revicion){
           
            $query = self::$conexion->prepare('SELECT * FROM servrevicion WHERE fkrevpreeliminar = ? ORDER BY pda');
            $query->execute(array($revicion));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>