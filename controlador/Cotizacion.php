<?php 
    class Cotizacion extends Conexion{

        private static $conexion;
        public $pkcotizacion;
        public $folio;
        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO cotizacion (
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
                subtotal1,
                total,
                fkrevpreeliminar,
                fksucursal
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ');

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
                $data["pcalidad"],
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
                $data["revision"],
                $data["sucursal"]
                )) > 0){
                    $this->pkcotizacion= self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        
        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT pkcotizacion,folio FROM cotizacion WHERE fksucursal = ? ORDER BY pkcotizacion DESC');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;

            //Implementacion: Presupuesto, carga de cotizacion
        }

        
        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM cotizacion,sucursal WHERE cotizacion.pkcotizacion = :id AND cotizacion.fksucursal = sucursal.pksucursal ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Update(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE cotizacion SET 
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
                total = ?,
                fkrevpreeliminar = ?
            WHERE pkcotizacion = ? ');
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
                $data["pcalidad"],
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
                $data["revision"],
                $data["pkcotizacion"]
            )) > 0){
                return true;
             }

             return false;
        }


        public function Delete($provider) : bool{
            $query = self::$conexion->prepare('DELETE FROM cotizacion WHERE pkcotizacion = ?');

           if( $query->execute(array($provider)) > 0 ){
                return true;
           }
           return false;

        }

        //Join cotizacion para la lista de cotizaciones
        public function GetDataJoin($data,$anio){
           
            $query = self::$conexion->prepare('SELECT 
                cotizacion.pkcotizacion,
                cotizacion.folio,
                cotizacion.fecha,
                cotizacion.fkcliente,
                cotizacion.fkecotizo,
                cotizacion.ocompra,
                cotizacion.factura, 
                cliente.pkcliente,
                cliente.nombre AS ncliente,
                deptocli.nombre AS ndepto,
                empleado.pkempleado,
                empleado.nombre AS nempleado, 
                empleado.apellidos FROM cotizacion
            LEFT JOIN cliente ON cotizacion.fkcliente = cliente.pkcliente
            LEFT JOIN deptocli ON cotizacion.fkdeptocli = deptocli.pkdeptocli 
            LEFT JOIN empleado ON cotizacion.fkecotizo = empleado.pkempleado WHERE cotizacion.fksucursal = ? AND YEAR(cotizacion.fecha) = ? ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(folio, "/", 1), "A", -1) AS UNSIGNED) DESC,folio DESC');
            $query->execute(array($data,$anio));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Servicio de cotizacion
        public function AddServ($data): bool{
            $query = self::$conexion->prepare('INSERT INTO servcotizacion(pda,cant,fkunidad,descripcion,tipotrabajo,preciounit,subtotal,clave,item,fkcotizacion) VALUES (?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array($data["pda"],$data["cantidad"],$data["unidad"],$data["descripcion"],$data["ttrabajo"],$data["costo"],$data["subtotal"],$data["clave"],$data["item"],$data["fkcotizacion"])) > 0){
               return true;
            }

            return false;
        }

        //Actualización de servicio
        public function updateServ($data): bool{
            $query = self::$conexion->prepare('UPDATE servcotizacion SET 
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
            WHERE pkservcot = ?');

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
                $data["pkservcotizacion"]
            )) > 0){
               return true;
            }

            return false;
        }

        public function DeleteServ($servicio) : bool{
            $query = self::$conexion->prepare('DELETE FROM servcotizacion WHERE pkservcot = ?');

           if( $query->execute(array($servicio)) > 0 ){
                return true;
           }
           return false;

        }

        public function DelServAll($data,$array) : bool{
            $placeholder = rtrim(str_repeat('?,',count($data)),',');
            $query = self::$conexion->prepare('DELETE FROM servcotizacion WHERE fkcotizacion = ? AND pkservcot NOT IN ('.$placeholder.') ');
            
           if( $query->execute($array) > 0 ){
                return true;
           }
           return false;

        }

        public function GetDataAllServ($revicion){
           
            $query = self::$conexion->prepare('SELECT * FROM servcotizacion WHERE fkcotizacion = ? ORDER BY pda');
            $query->execute(array($revicion));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los datos de entrega
        public function Print($id) {
            $query = self::$conexion->prepare('SELECT 
                        cotizacion.pkcotizacion,
                        cotizacion.fksucursal,
                        cotizacion.folio,
                        cotizacion.fecha,
                        cotizacion.titulo,
                        cotizacion.cargo,
                        cotizacion.lab,
                        cotizacion.dnormativos,
                        cotizacion.efabricacion,
                        cotizacion.formpago,
                        cotizacion.dcredito,
                        cotizacion.tiempoent,
                        cotizacion.vigencia,
                        cotizacion.dattecnicos,
                        cotizacion.doclegal,
                        cotizacion.total,
                        cotizacion.moneda,
                        cotizacion.subtotal1,
                        cotizacion.descto,
                        cotizacion.iva,
                        cotizacion.tipocambio,
                        cotizacion.observacion,
                        cliente.pkcliente,
                        cliente.nombre,
                        cliente.direccion,
                        usercli.nombre as nombre_usercli,
                        usercli.titulo,
                        empleado.nombre as nombre_empleado,
                        empleado.apellidos,
                        atn.titulo as titulo_atn,
                        atn.nombre as nombre_atn FROM cotizacion
                LEFT JOIN cliente ON cotizacion.fkcliente = cliente.pkcliente
                LEFT JOIN usercli ON cotizacion.fkusercli = usercli.pkusercli
                LEFT JOIN empleado ON cotizacion.fkeresponsable = empleado.pkempleado
                LEFT JOIN usercli AS atn ON cotizacion.fkattnusercli = atn.pkusercli WHERE cotizacion.pkcotizacion = :id ');
            $query->bindParam(':id',$id);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        //Función para imprimir los servicios
        public function Servprint($orden){
           
            $query = self::$conexion->prepare('SELECT servcotizacion.*,unidad.nombre FROM servcotizacion
                LEFT JOIN unidad ON servcotizacion.fkunidad = unidad.pkunidad WHERE fkcotizacion = ? ORDER BY pda
            ');
            $query->execute(array($orden));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Concepto($sucursal,$anio){
            $query = self::$conexion->prepare('SELECT 
            servcotizacion.descripcion,
            servcotizacion.tipotrabajo,
            cotizacion.fecha,
            cotizacion.folio,
            deptocli.nombre AS ndepto,
            cotizacion.pkcotizacion,
            cliente.nombre FROM servcotizacion
            LEFT JOIN cotizacion ON servcotizacion.fkcotizacion = cotizacion.pkcotizacion
            LEFT JOIN deptocli ON servcotizacion.fkcotizacion = cotizacion.pkcotizacion AND cotizacion.fkdeptocli = deptocli.pkdeptocli
            LEFT JOIN cliente ON cotizacion.fkcliente = cliente.pkcliente WHERE cotizacion.fksucursal = ? AND YEAR(cotizacion.fecha) = ? ORDER BY cotizacion.fecha DESC
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