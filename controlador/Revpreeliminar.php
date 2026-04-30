
<?php 
    class Revpreeliminar extends Conexion{

        private static $conexion;
        public $pkrevpreeliminar;
        public $folio;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}
        //Revision preeliminar
        public function AddFolio($folio,$sucursal,$fecha): bool{
            $query = self::$conexion->prepare('INSERT INTO revpreeliminar (folio,fksucursal,fecha) VALUES (?,?,?) ');

            if($query->execute(array($folio,$sucursal,$fecha)) > 0){
                $this->pkrevpreeliminar = self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

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
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ');

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
           
            $query = self::$conexion->prepare('SELECT * FROM revpreeliminar WHERE revpreeliminar.fksucursal = ? ORDER BY pkrevpreeliminar DESC');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM revpreeliminar,sucursal WHERE revpreeliminar.pkrevpreeliminar = :id AND revpreeliminar.fksucursal = sucursal.pksucursal ');
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
        public function GetDataJoin($data,$anio){
           
            $query = self::$conexion->prepare('SELECT revpreeliminar.*, cliente.pkcliente,cliente.nombre AS ncliente,empleado.pkempleado,empleado.nombre AS nempleado, empleado.apellidos FROM revpreeliminar 
            LEFT JOIN cliente ON revpreeliminar.fkcliente = cliente.pkcliente 
            LEFT JOIN empleado ON revpreeliminar.fkeventas = empleado.pkempleado WHERE revpreeliminar.fksucursal = ? AND YEAR(revpreeliminar.fecha) = ? ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(folio, "/", 1), "A", -1) AS UNSIGNED) DESC,folio DESC');
            $query->execute(array($data,$anio));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Servicio de revision preeliminar
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO servrevicion (pda,cantidad,unidad,descripcion,costo,tipotrabajo,fkrevpreeliminar,item) VALUES (?,?,?,?,?,?,?,?) ');

            if($query->execute(array($data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["costo"],$data["ttrabajo"],$data["fkrevpreeliminar"],$data["item"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE servrevicion SET pda = ?,cantidad = ?,unidad = ?,descripcion = ?, costo = ?, tipotrabajo = ?, fkrevpreeliminar = ?, item = ? WHERE pkservrevicion = ?');

            if($query->execute(array(
                $data["pda"],
                $data["cantidad"],
                $data["unidad"],
                $data["descripcion"],
                $data["costo"],
                $data["ttrabajo"],
                $data["fkrevpreeliminar"],
                $data["item"],
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

        //Función para imprimir los datos de revision
        public function Print($id) {
            $query = self::$conexion->prepare('SELECT 
                        revpreeliminar.pkrevpreeliminar,
                        revpreeliminar.fksucursal,
                        revpreeliminar.folio,
                        revpreeliminar.fecha,
                        revpreeliminar.depto,
                        revpreeliminar.proyecto,
                        revpreeliminar.reqinsdoc,
                        revpreeliminar.reqlegales,
                        revpreeliminar.reqent,
                        revpreeliminar.condpago,
                        revpreeliminar.reqespserv,
                        revpreeliminar.desviacionexc,
                        revpreeliminar.propcli,
                        cliente.nombre AS nombre_cli,
                        usercli.titulo,
                        usercli.nombre AS nombre_user,
                        ventas.nombre AS nombre_ventas,
                        ventas.apellidos AS apellido_ventas,
                        produccion.nombre AS nombre_produccion,
                        produccion.apellidos AS apellido_produccion,
                        ventas.nombre AS nombre_ventas,
                        ventas.apellidos AS apellido_ventas,
                        produccion.nombre AS nombre_produccion,
                        produccion.apellidos AS apellido_produccion,
                        calidad.nombre AS nombre_calidad,
                        calidad.apellidos AS apellido_calidad FROM revpreeliminar
                LEFT JOIN cliente ON revpreeliminar.fkcliente = cliente.pkcliente
                LEFT JOIN usercli ON revpreeliminar.fkusercli = usercli.pkusercli
                LEFT JOIN empleado AS ventas ON revpreeliminar.fkeventas = ventas.pkempleado
                LEFT JOIN empleado AS produccion ON revpreeliminar.fkeproduccion = produccion.pkempleado
                LEFT JOIN empleado AS calidad ON revpreeliminar.fkecalidad = calidad.pkempleado WHERE revpreeliminar.pkrevpreeliminar = :id ');
            $query->bindParam(':id',$id);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los servicios
        public function Servprint($orden){
           
            $query = self::$conexion->prepare('SELECT servrevicion.*,unidad.nombre FROM servrevicion 
                LEFT JOIN unidad ON servrevicion.unidad = unidad.pkunidad WHERE fkrevpreeliminar = ? ORDER BY pda
            ');
            $query->execute(array($orden));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>