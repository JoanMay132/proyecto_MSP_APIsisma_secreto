<?php 
    class Subcotizacion extends Conexion{

        private static $conexion;
        public $pksubcotizacion;
        public $folio;

        public $numrow;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO subcotizacion (
                folio,
                fecha,
                fkcliente,
                fkusercli,
                fkattnusercli,
                titulo,
                lab,
                garantia,
                cargo,
                fkecotizo,
                fkeresponsable,
                vigencia,
                ocompra,
                dnormativos,
                efabricacion,
                formpago,
                dcredito,
                tiempoent,
                dattecnicos,
                doclegal,
                costo,
                fkdeptocli,
                factura,
                estado,
                factura2,
                fechafactura,
                observacion,
                contenido,
                moneda,
                tipocambio,
                descto,
                iva,
                fkcotizacion,
                fksucursal,
                nombre
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array(
                $data["folio"],
                $data["fecha"],
                $data["fkcliente"],
                $data["fkusercli"],
                $data["attn"],
                $data["titulo"],
                $data["lab"],
                $data["garantia"],
                $data["cargo"],
                $data["cotizo"],
                $data["responsable"],
                $data["vigencia"],
                $data["ocompra"],
                $data["datnormativos"],
                $data["fabricacion"],
                $data["fpago"],
                $data["credito"],
                $data["tentrega"],
                $data["dattecnicos"],
                $data["doclegal"],
                $data["fcosto"],
                $data["area"],
                $data["factura1"],
                $data["estado"],
                $data["factura2"],
                $data["ffactura"],
                $data["observaciones"],
                $data["contenido"],
                $data["tmoneda"],
                $data["tcambio"],
                $data["descto"],
                $data["iva"],
                $data["cotizacion"],
                $data["sucursal"],
                $data["nombre"]
                )) > 0){
                    $this->pksubcotizacion= self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        
        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT pksubcotizacion,folio FROM subcotizacion WHERE fksucursal = ? ');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;

            //Implementacion: Presupuesto, carga de cotizacion
        }

        
        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM subcotizacion,sucursal WHERE subcotizacion.pksubcotizacion = :id AND subcotizacion.fksucursal = sucursal.pksucursal ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Obtiene la lista de subcotizaciones para mostrar en la cot
        public function ListAll($cotizacion)
        {
            $query = self::$conexion->prepare('SELECT pksubcotizacion,nombre,total,fksucursal FROM subcotizacion WHERE fkcotizacion = :id ');
            $query->bindParam(':id',$cotizacion);
            $query->execute();
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            $this->numrow = count($query);
            return $query;
        }

        public function Update(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE subcotizacion SET 
                folio = ?,
                fecha = ?,
                fkcliente = ?,
                fkusercli = ?,
                fkattnusercli = ?,
                titulo = ?,
                lab = ?,
                garantia = ?,
                cargo = ?,
                fkecotizo = ?,
                fkeresponsable = ?,
                vigencia = ?,
                ocompra = ?,
                dnormativos = ?,
                efabricacion = ?,
                formpago = ?,
                dcredito = ?,
                tiempoent = ?,
                dattecnicos = ?,
                doclegal = ?,
                costo = ?,
                fkdeptocli = ?,
                factura = ?,
                estado = ?,
                factura2 = ?,
                fechafactura = ?,
                observacion = ?,
                contenido = ?,
                moneda = ?,
                tipocambio = ?,
                descto = ?,
                iva = ?,
                subtotal1 = ?,
                total = ?
            WHERE pksubcotizacion = ? ');
            if($query->execute(array(
                $data["folio"],
                $data["fecha"],
                $data["fkcliente"],
                $data["fkusercli"],
                $data["attn"],
                $data["titulo"],
                $data["lab"],
                $data["garantia"],
                $data["cargo"],
                $data["cotizo"],
                $data["responsable"],
                $data["vigencia"],
                $data["ocompra"],
                $data["datnormativos"],
                $data["fabricacion"],
                $data["fpago"],
                $data["credito"],
                $data["tentrega"],
                $data["dattecnicos"],
                $data["doclegal"],
                $data["fcosto"],
                $data["area"],
                $data["factura1"],
                $data["estado"],
                $data["factura2"],
                $data["ffactura"],
                $data["observaciones"],
                $data["contenido"],
                $data["tmoneda"],
                $data["tcambio"],
                $data["descto"],
                $data["iva"],
                $data["subtotal"],
                $data["total"],
                $data["pksubcotizacion"]
            )) > 0){
                return true;
             }

             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM subcotizacion WHERE pksubcotizacion = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;

        }

        //Actualiza el nombre de la subcotizacion
        public function UpdateName($id,$name) : bool{
            $query = self::$conexion->prepare('UPDATE subcotizacion SET 
                nombre = :name
            WHERE pksubcotizacion = :id');
            $query->bindParam(':id',$id);
            $query->bindParam(':name',$name);
            if($query->execute()){
                return true;
            }
            

                return false;
        }

        //Join cotizacion para la lista de cotizaciones
        public function GetDataJoin($data){
           
            $query = self::$conexion->prepare('SELECT 
                subcotizacion.pksubcotizacion,
                subcotizacion.folio,
                subcotizacion.fecha,
                subcotizacion.fkcliente,
                subcotizacion.fkecotizo,
                subcotizacion.ocompra,
                subcotizacion.factura, 
                cliente.pkcliente,
                cliente.nombre AS ncliente,
                empleado.pkempleado,
                empleado.nombre AS nempleado, 
                empleado.apellidos FROM subcotizacion
            LEFT JOIN cliente ON subcotizacion.fkcliente = cliente.pkcliente 
            LEFT JOIN empleado ON subcotizacion.fkecotizo = empleado.pkempleado WHERE subcotizacion.fksucursal = ? ORDER BY folio DESC');
            $query->execute(array($data));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Servicio de cotizacion
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO subservcotizacion(pda,cant,fkunidad,descripcion,tipotrabajo,preciounit,subtotal,clave,item,fksubcotizacion) VALUES (?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array($data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["ttrabajo"],$data["costo"],$data["subtotal"],$data["clave"],$data["item"],$data["fksubcotizacion"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE subservcotizacion SET 
                pda = ?,
                cant = ?,
                fkunidad = ?,
                descripcion = ?,
                tipotrabajo = ?,
                preciounit = ?,
                subtotal = ?,
                clave = ?,
                item = ?,
                contenido = ? 
            WHERE pksubservcot = ?');

            if($query->execute(array(
                $data["pda"],
                $data["cantidad"],
                $data["unidad"],
                $data["descripcion"],
                $data["ttrabajo"],
                $data["costo"],
                $data["subtotal"],
                $data["clave"],
                $data["item"],
                $data["contenido"],
                $data["pksubservcotizacion"]
            )) > 0){
               return true;
            }

            return false;
        }


        public function DeleteServ($servicio) : bool{
            $query = self::$conexion->prepare('DELETE FROM subservcotizacion WHERE pksubservcot = ?');

           if( $query->execute(array($servicio)) > 0 ){
                return true;
           }
           return false; 


        }

        public function GetDataAllServ($revicion){
           
            $query = self::$conexion->prepare('SELECT * FROM subservcotizacion WHERE fksubcotizacion = ? ORDER BY pda');
            $query->execute(array($revicion));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Print($id) {
            $query = self::$conexion->prepare('SELECT 
                        subcotizacion.pksubcotizacion,
                        subcotizacion.fksucursal,
                        subcotizacion.folio,
                        subcotizacion.fecha,
                        subcotizacion.titulo,
                        subcotizacion.cargo,
                        subcotizacion.lab,
                        subcotizacion.dnormativos,
                        subcotizacion.efabricacion,
                        subcotizacion.formpago,
                        subcotizacion.dcredito,
                        subcotizacion.tiempoent,
                        subcotizacion.vigencia,
                        subcotizacion.dattecnicos,
                        subcotizacion.doclegal,
                        subcotizacion.total,
                        subcotizacion.moneda,
                        subcotizacion.subtotal1,
                        subcotizacion.descto,
                        subcotizacion.iva,
                        subcotizacion.tipocambio,
                        subcotizacion.observacion,
                        cliente.pkcliente,
                        cliente.nombre,
                        cliente.direccion,
                        usercli.nombre as nombre_usercli,
                        usercli.titulo,
                        empleado.nombre as nombre_empleado,
                        empleado.apellidos,
                        atn.titulo as titulo_atn,
                        atn.nombre as nombre_atn FROM subcotizacion
                LEFT JOIN cliente ON subcotizacion.fkcliente = cliente.pkcliente
                LEFT JOIN usercli ON subcotizacion.fkusercli = usercli.pkusercli
                LEFT JOIN empleado ON subcotizacion.fkeresponsable = empleado.pkempleado
                LEFT JOIN usercli AS atn ON subcotizacion.fkattnusercli = atn.pkusercli WHERE subcotizacion.pksubcotizacion = :id ');
            $query->bindParam(':id',$id);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los servicios
        public function Servprint($orden){
           
            $query = self::$conexion->prepare('SELECT subservcotizacion.*,unidad.nombre FROM subservcotizacion
                LEFT JOIN unidad ON subservcotizacion.fkunidad = unidad.pkunidad WHERE fksubcotizacion = ? ORDER BY pda
            ');
            $query->execute(array($orden));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null;// Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>