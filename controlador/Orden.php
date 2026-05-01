<?php 
    class Orden extends Conexion{

        private static $conexion;
        public $pkorden;
        public $folio;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function AddFolio($folio,$sucursal,$fecha): bool{
            $query = self::$conexion->prepare('INSERT INTO orden (
                folio,
                fksucursal,
                fkcliente,
                fkusercli,
                fkcotizacion,
                fecha,
                depto,
                diaentrega,
                tipo,
                observaciones,
                fkeproduccion,
                fkeenterado
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array(
                $folio,
                $sucursal,
                0,
                0,
                0,
                $fecha,
                '',
                0,
                'normal',
                '',
                0,
                0
            )) > 0){
                $this->pkorden = self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO orden (
                folio,
                fksucursal,
                fkcliente,
                fkusercli,
                fkcotizacion,
                fecha,
                depto,
                diaentrega,
                tipo,
                observaciones,
                fkeproduccion,
                fkeenterado
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array(
                $data["folio"],
                $data["sucursal"],
                $data["cliente"],
                $data["solicito"],
                $data["cotizacion"],
                $data["fecha"],
                $data["depto"],
                $data["diaentrega"],
                $data["tipo"],
                $data["observaciones"],
                $data["auxiliar"],
                $data["enterado"]
               
                )) > 0){
                    $this->pkorden= self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        
        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT pkorden,folio,fkcotizacion FROM orden WHERE fksucursal = ? ORDER BY pkorden DESC');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;

            //Implementacion: Presupuesto, carga de cotizacion
        }

        
        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM orden,sucursal WHERE orden.pkorden = :id AND orden.fksucursal = sucursal.pksucursal ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetByCotizacion(int $cotizacion, int $sucursal = 0){
            if($sucursal > 0){
                $query = self::$conexion->prepare('SELECT pkorden, fkcotizacion FROM orden WHERE fkcotizacion = ? AND fksucursal = ? ORDER BY pkorden DESC LIMIT 1');
                $query->execute(array($cotizacion, $sucursal));
            }else{
                $query = self::$conexion->prepare('SELECT pkorden, fkcotizacion FROM orden WHERE fkcotizacion = ? ORDER BY pkorden DESC LIMIT 1');
                $query->execute(array($cotizacion));
            }

            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function Update(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE orden SET 
                fkcliente = ?,
                fkusercli  = ?,
                fkcotizacion = ?,
                fecha = ?,
                depto = ?,
                diaentrega = ?,
                tipo = ?,
                observaciones = ?,
                fkeproduccion = ?,
                fkeenterado = ?
            WHERE pkorden = ? ');
            if($query->execute(array(
                $data["cliente"],
                $data["solicito"],
                $data["cotizacion"],
                $data["fecha"],
                $data["depto"],
                $data["diaentrega"],
                $data["tipo"],
                $data["observaciones"],
                $data["auxiliar"],
                $data["enterado"],
                $data["pkorden"]
            )) > 0){
                return true;
             }

             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM orden WHERE pkorden = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;

        }

        //Join cotizacion para la lista de cotizaciones
        public function GetDataJoin($data,$anio){
           
            $query = self::$conexion->prepare('SELECT 
            orden.pkorden,
            orden.folio,
            orden.fecha,
            orden.fkcliente,
            orden.fkeproduccion,
            cliente.pkcliente,
            cliente.nombre AS ncliente,
            empleado.pkempleado,
            empleado.nombre AS nempleado, 
            empleado.apellidos,
            cotizacion.ocompra, 
            cotizacion.folio AS folioCot FROM orden
        LEFT JOIN cotizacion ON orden.fkcotizacion = cotizacion.pkcotizacion
        LEFT JOIN cliente ON orden.fkcliente = cliente.pkcliente 
        LEFT JOIN empleado ON orden.fkeproduccion = empleado.pkempleado WHERE orden.fksucursal = ? AND YEAR(orden.fecha) = ? ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(orden.folio, "/", 1), "C", -1) AS UNSIGNED) DESC,folio DESC');
            $query->execute(array($data,$anio));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Servicio de cotizacion
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO servorden(pda,cantidad,unidad,descripcion,tipotrabajo,dibujo,fkorden) VALUES (?,?,?,?,?,?,?) ');

            if($query->execute(array($data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["ttrabajo"],$data["dibujo"],$data["fkorden"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE servorden SET 
                pda = ?,
                cantidad = ?,
                unidad = ?,
                descripcion = ?,
                tipotrabajo = ?,
                dibujo = ?
            WHERE pkservorden = ?');

            if($query->execute(array(
                $data["pda"],
                $data["cantidad"],
                $data["unidad"],
                $data["descripcion"],
                $data["ttrabajo"],
                $data["dibujo"],
                $data["pkservorden"]
            )) > 0){
               return true;
            }

            return false;
        }

        public function DeleteServ($servicio) : bool{
            $query = self::$conexion->prepare('DELETE FROM servorden WHERE pkservorden= ?');

           if( $query->execute(array($servicio)) > 0 ){
                return true;
           }
           return false;

        }

        public function GetDataAllServ($revicion){
           
            $query = self::$conexion->prepare('SELECT * FROM servorden WHERE fkorden = ? ORDER BY pda');
            $query->execute(array($revicion));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }
        

        public function DelServAll($data,$array) : bool{
            $placeholder = rtrim(str_repeat('?,',count($data)),',');
            $query = self::$conexion->prepare('DELETE FROM servorden WHERE fkorden = ? AND pkservorden NOT IN ('.$placeholder.') ');
            
           if( $query->execute($array) > 0 ){
                return true;
           }
           return false;

        }

        //Función para imprimir los datos de entrega
        public function Print($id) {
            $query = self::$conexion->prepare('SELECT 
                        orden.pkorden,
                        orden.fksucursal,
                        orden.folio,
                        orden.fecha,
                        orden.depto,
                        orden.diaentrega,
                        orden.tipo,
                        orden.observaciones,
                        cliente.nombre AS nombre_cli,
                        usercli.titulo,
                        usercli.nombre AS nombre_user,
                        cotizacion.folio AS folio_cot,
                        eelaboro.nombre AS nombre_elaboro,
                        eelaboro.apellidos AS apellido_elaboro,
                        empleado.nombre,
                        empleado.apellidos FROM orden
                LEFT JOIN cliente ON orden.fkcliente = cliente.pkcliente
                LEFT JOIN cotizacion ON orden.fkcotizacion = cotizacion.pkcotizacion
                LEFT JOIN usercli ON orden.fkusercli = usercli.pkusercli
                LEFT JOIN empleado ON orden.fkeproduccion = empleado.pkempleado
                LEFT JOIN empleado AS eelaboro ON orden.fkeenterado = eelaboro.pkempleado WHERE orden.pkorden = :id ');
            $query->bindParam(':id',$id);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los servicios
        public function Servprint($orden){
           
            $query = self::$conexion->prepare('SELECT servorden.*,unidad.nombre FROM servorden 
                LEFT JOIN unidad ON servorden.unidad = unidad.pkunidad WHERE fkorden = ? ORDER BY pda
            ');
            $query->execute(array($orden));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Concepto($sucursal,$anio){
            $query = self::$conexion->prepare('SELECT 
            servorden.descripcion,
            servorden.tipotrabajo,
            orden.fecha,
            orden.folio,
            orden.pkorden,
            cliente.nombre FROM servorden
            LEFT JOIN orden ON servorden.fkorden = orden.pkorden
            LEFT JOIN cliente ON orden.fkcliente = cliente.pkcliente WHERE orden.fksucursal = ? AND YEAR(orden.fecha) = ? ORDER BY orden.fecha DESC
        ');
            $query->execute(array($sucursal,$anio));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }

    }

?>
