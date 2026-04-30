<?php 
    class Suborden extends Conexion{

        private static $conexion;
        public $pksuborden;
        public $folio;
        public $numrow;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO suborden (
                folio,
                nombre,
                fksucursal,
                fkcliente,
                fkusercli,
                fecha,
                depto,
                diaentrega,
                tipo,
                observaciones,
                fkeproduccion,
                fkeenterado,
                fkorden
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array(
                $data["folio"],
                $data["nombre"],
                $data["sucursal"],
                $data["cliente"],
                $data["solicito"],
                $data["fecha"],
                $data["depto"],
                $data["diaentrega"],
                $data["tipo"],
                $data["observaciones"],
                $data["auxiliar"],
                $data["enterado"],
                $data["fkorden"]
                )) > 0){
                    $this->pksuborden= self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        
        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT pkorden,folio FROM orden WHERE fksucursal = ? ');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;

            //Implementacion: Presupuesto, carga de cotizacion
        }

        
        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM suborden,sucursal WHERE suborden.pksuborden = :id AND suborden.fksucursal = sucursal.pksucursal ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Update(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE suborden SET 
                fkcliente = ?,
                fkusercli  = ?,
                fecha = ?,
                depto = ?,
                diaentrega = ?,
                tipo = ?,
                observaciones = ?,
                fkeproduccion = ?,
                fkeenterado = ?
            WHERE pksuborden = ? ');
            if($query->execute(array(
                $data["cliente"],
                $data["solicito"],
                $data["fecha"],
                $data["depto"],
                $data["diaentrega"],
                $data["tipo"],
                $data["observaciones"],
                $data["auxiliar"],
                $data["enterado"],
                $data["pksuborden"],
            )) > 0){
                return true;
             }

             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM suborden WHERE pksuborden = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;

        }

        //Join cotizacion para la lista de cotizaciones
        public function GetDataJoin($data){
           
            $query = self::$conexion->prepare('SELECT 
            suborden.pksuborden,
            suborden.folio,
            suborden.fecha,
            suborden.fkcliente,
            suborden.fkeproduccion,
            cliente.pkcliente,
            cliente.nombre AS ncliente,
            empleado.pkempleado,
            empleado.nombre AS nempleado, 
            empleado.apellidos, 
            cotizacion.folio AS folioCot FROM suborden
        LEFT JOIN cotizacion ON suborden.fkcotizacion = cotizacion.pkcotizacion
        LEFT JOIN cliente ON suborden.fkcliente = cliente.pkcliente 
        LEFT JOIN empleado ON suborden.fkeproduccion = empleado.pkempleado WHERE suborden.fksucursal = ? ORDER BY folio DESC');
            $query->execute(array($data));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Obtiene la lista de subcotizaciones para mostrar en la cot
        public function ListAll($ot)
        {
            $query = self::$conexion->prepare('SELECT pksuborden,nombre,fksucursal FROM suborden WHERE fkorden = :id ');
            $query->bindParam(':id',$ot);
            $query->execute();
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            $this->numrow = count($query);
            return $query;
        }

        //Servicio de cotizacion
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO subservorden(fksuborden,pda,cantidad,unidad,descripcion,tipotrabajo,dibujo) VALUES (?,?,?,?,?,?,?) ');

            if($query->execute(array($data["suborden"],$data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["ttrabajo"],$data["dibujo"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE subservorden SET 
                pda = ?,
                cantidad = ?,
                unidad = ?,
                descripcion = ?,
                tipotrabajo = ?,
                dibujo = ?
            WHERE pksubservorden = ?');

            if($query->execute(array(
                $data["pda"],
                $data["cantidad"],
                $data["unidad"],
                $data["descripcion"],
                $data["ttrabajo"],
                $data["dibujo"],
                $data["pksubservorden"]
            )) > 0){
               return true;
            }

            return false;
        }

        public function DeleteServ($servicio) : bool{
            $query = self::$conexion->prepare('DELETE FROM subservorden WHERE pksubservorden= ?');

           if( $query->execute(array($servicio)) > 0 ){
                return true;
           }
           return false;

        }

        public function GetDataAllServ($orden){
           
            $query = self::$conexion->prepare('SELECT * FROM subservorden WHERE fksuborden = ? ORDER BY pda');
            $query->execute(array($orden));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

         //Actualiza el nombre de la suborden
         public function UpdateName($id,$name) : bool{
            $query = self::$conexion->prepare('UPDATE suborden SET 
                nombre = :name
            WHERE pksuborden = :id');
            $query->bindParam(':id',$id);
            $query->bindParam(':name',$name);
            if($query->execute()){
                return true;
            }
            

                return false;
        }

        public function Print($id) {
            $query = self::$conexion->prepare('SELECT 
                        suborden.pksuborden,
                        suborden.fksucursal,
                        suborden.folio,
                        suborden.fecha,
                        suborden.depto,
                        suborden.diaentrega,
                        suborden.tipo,
                        suborden.observaciones,
                        cliente.nombre AS nombre_cli,
                        usercli.titulo,
                        usercli.nombre AS nombre_user,
                        eelaboro.nombre AS nombre_elaboro,
                        eelaboro.apellidos AS apellido_elaboro,
                        empleado.nombre,
                        empleado.apellidos FROM suborden
                LEFT JOIN cliente ON suborden.fkcliente = cliente.pkcliente
                LEFT JOIN usercli ON suborden.fkusercli = usercli.pkusercli
                LEFT JOIN empleado ON suborden.fkeproduccion = empleado.pkempleado
                LEFT JOIN empleado AS eelaboro ON suborden.fkeenterado = eelaboro.pkempleado WHERE suborden.pksuborden = :id ');
            $query->bindParam(':id',$id);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los servicios
        public function Servprint($orden){
           
            $query = self::$conexion->prepare('SELECT subservorden.*,unidad.nombre FROM subservorden 
                LEFT JOIN unidad ON subservorden.unidad = unidad.pkunidad WHERE fksuborden = ? ORDER BY pda
            ');
            $query->execute(array($orden));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null;; // Cierra la conexión cuando el objeto se destruye
        }
    


        

    }

?>