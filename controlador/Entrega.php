<?php 
    class Entrega extends Conexion{

        private static $conexion;
        public $pkentrega;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}


        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO entrega (
                fksucursal,
                fkorden,
                fkcotizacion,
                fkcliente,
                fksolicito,
                depto,
                fecha,
                observaciones,
                fkentrego,
                fkrecibe,
                evidencia
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array(
                
                $data["sucursal"],
                $data["fkorden"],
                $data["cotizacion"],
                $data["cliente"],
                $data["solicito"],
                $data["depto"],
                $data["fecha"],
                $data["observaciones"],
                $data["entrego"],
                $data["recibe"],
                $data["evidencia"],
               
                )) > 0){
                    $this->pkentrega= self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }


        
        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM entrega,sucursal WHERE entrega.pkentrega = :id AND entrega.fksucursal = sucursal.pksucursal ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Update(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE entrega SET 
                fkorden = ?,
                fkcotizacion = ?,
                fkcliente = ?,
                fksolicito = ?,
                depto = ?,
                fecha = ?,
                observaciones = ?,
                fkentrego = ?,
                fkrecibe = ?,
                evidencia = ?
            WHERE pkentrega = ? ');
            if($query->execute(array(
                $data["fkorden"],
                $data["cotizacion"],
                $data["cliente"],
                $data["solicito"],
                $data["depto"],
                $data["fecha"],
                $data["observaciones"],
                $data["entrego"],
                $data["recibe"],
                $data["evidencia"],
                $data["pkentrega"]
            )) > 0){
                return true;
             }

             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM entrega WHERE pkentrega = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;

        }

        //Join entrega para la lista de entregas
        public function GetDataJoin($data,$anio){
           
            $query = self::$conexion->prepare('SELECT 
            entrega.pkentrega,
            entrega.fecha,
            entrega.fkcliente,
            entrega.fkentrego,
            orden.pkorden,
            orden.folio,
            cliente.pkcliente,
            cliente.nombre AS ncliente,
            empleado.pkempleado,
            empleado.nombre AS nempleado, 
            empleado.apellidos, 
            cotizacion.folio AS folioCot FROM entrega
        LEFT JOIN orden ON entrega.fkorden = orden.pkorden
        LEFT JOIN cliente ON entrega.fkcliente = cliente.pkcliente
        LEFT JOIN cotizacion ON entrega.fkcotizacion = cotizacion.pkcotizacion
        LEFT JOIN empleado ON entrega.fkentrego = empleado.pkempleado WHERE entrega.fksucursal = ? AND YEAR(entrega.fecha) = ? ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(orden.folio, "/", 1), "A", -1) AS UNSIGNED) DESC,orden.folio DESC');
            $query->execute(array($data,$anio));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Servicio de entrega
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO serventrega(pda,cantidad,unidad,descripcion,fkentrega) VALUES (?,?,?,?,?) ');

            if($query->execute(array($data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["fkentrega"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE serventrega SET 
                pda = ?,
                cantidad = ?,
                unidad = ?,
                descripcion = ?
            WHERE pkserventrega = ?');

            if($query->execute(array(
                $data["pda"],
                $data["cantidad"],
                $data["unidad"],
                $data["descripcion"],
                // $data["ttrabajo"],
                // $data["dibujo"],
                $data["pkserventrega"]
            )) > 0){
               return true;
            }

            return false;
        }

        public function DeleteServ($servicio) : bool{
            $query = self::$conexion->prepare('DELETE FROM serventrega WHERE pkserventrega = ?');

           if( $query->execute(array($servicio)) > 0 ){
                return true;
           }
           return false;

        }

        public function GetDataAllServ($revicion){
           
            $query = self::$conexion->prepare('SELECT * FROM serventrega WHERE fkentrega = ? ORDER BY pda');
            $query->execute(array($revicion));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }
        

        public function DelServAll($data,$array) : bool{
            $placeholder = rtrim(str_repeat('?,',count($data)),',');
            $query = self::$conexion->prepare('DELETE FROM serventrega WHERE fkentrega = ? AND pkserventrega NOT IN ('.$placeholder.') ');            
           if( $query->execute($array) > 0 ){
                return true;
           }
           return false;
        }

        //Función para imprimir los datos de entrega
        public function Print($id) {
            $query = self::$conexion->prepare('SELECT 
                        entrega.pkentrega,
                        entrega.fksucursal,
                        entrega.fecha,
                        entrega.depto,
                        entrega.observaciones,
                        entrega.evidencia,
                        cliente.nombre AS nombre_cli,
                        usercli.titulo,
                        usercli.nombre AS nombre_user,
                        orden.folio,
                        cotizacion.folio AS folio_cot,
                        erecibe.nombre AS nombre_recibe,
                        empleado.nombre,
                        empleado.apellidos FROM entrega
                LEFT JOIN cliente ON entrega.fkcliente = cliente.pkcliente
                LEFT JOIN orden ON entrega.fkorden = orden.pkorden
                LEFT JOIN cotizacion ON entrega.fkcotizacion = cotizacion.pkcotizacion
                LEFT JOIN usercli ON entrega.fksolicito = usercli.pkusercli
                LEFT JOIN empleado ON entrega.fkentrego = empleado.pkempleado
                LEFT JOIN usercli AS erecibe ON entrega.fkrecibe = erecibe.pkusercli WHERE entrega.pkentrega = :id ');
            $query->bindParam(':id',$id);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los servicios
        public function Servprint($entrega){
           
            $query = self::$conexion->prepare('SELECT serventrega.*,unidad.nombre FROM serventrega 
                LEFT JOIN unidad ON serventrega.unidad = unidad.pkunidad WHERE fkentrega = ? ORDER BY pda
            ');
            $query->execute(array($entrega));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    

    }

