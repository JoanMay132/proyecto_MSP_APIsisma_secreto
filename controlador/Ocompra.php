<?php

class Ocompra extends Conexion{

    private static $conexion;

    public $pkocompra;

    function __construct(){

        self::$conexion = parent::getConexion();

    }

    public function AddFolio($folio, $sucursal, $fechaorden, $fechaentrega): bool{
        $query = self::$conexion->prepare('INSERT INTO ocompra (
            folio,
            fechaorden,
            fechaent,
            fkrequisicion,
            fkorden,
            moneda,
            condpago,
            fkproveedor,
            rfc,
            direccion,
            contacto,
            telefono,
            correo,
            nproveedor,
            direntrega,
            fkecomprador,
            telefono2,
            email,
            observaciones,
            diascredito,
            fkesolicita,
            fkeautoriza,
            estado,
            importe,
            descto,
            subtotal,
            iva,
            total,
            fksucursal
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ');

        if($query->execute(array(
            $folio,
            $fechaorden,
            $fechaentrega,
            0,
            0,
            '',
            '',
            0,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            0,
            '',
            '',
            '',
            0,
            0,
            0,
            'VIGENTE',
            0,
            0,
            0,
            0,
            0,
            $sucursal
        )) > 0){
            $this->pkocompra = self::$conexion->lastInsertId();
           return true;
        }

        return false;
    }

    public function Update(array $data) : bool{
           
        $query = self::$conexion->prepare('UPDATE ocompra SET 
            fechaorden = ?,
            fechaent = ?,
            fkrequisicion = ?,
            fkorden = ?,
            moneda = ?,
            condpago = ?,
            fkproveedor = ?,
            rfc = ?,
            direccion = ?,
            contacto = ?,
            telefono = ?,
            correo = ?,
            nproveedor = ?,
            direntrega = ?,
            fkecomprador = ?,
            telefono2 = ?,
            email = ?,
            observaciones = ?,
            diascredito = ?,
            fkesolicita = ?,
            fkeautoriza = ?,
            estado = ?,
            importe = ?,
            descto	 = ?,
            -- subtotal = ?,
            iva = ?,
            total = ?
        WHERE pkocompra = ? ');
        if($query->execute(array(
            $data['fechaorden'],
            $data['fechaent'],
            $data['fkrequisicion'],
            $data['fkorden'],
            $data['moneda'],
            $data['condpago'],
            $data['fkproveedor'],
            $data['rfc'],
            $data['direccion'],
            $data['contacto'],
            $data['telefono'],
            $data['correo'],
            $data['nproveedor'],
            $data['direntrega'],
            $data['fkecomprador'],
            $data['telefono2'],
            $data['email'],
            $data['observaciones'],
            $data['diascredito'],
            $data['fkesolicita'],
            $data['fkeautoriza'],
            $data['estado'],
            $data['importe'],
            $data['descto'],	
            //$data['subtotal'],
            $data['iva'],
            $data['total'],
            $data['pkocompra']
        )) > 0){
            return true;
         }

         return false;
    }

    public function GetData($data){
           
        $query = self::$conexion->prepare('SELECT ocompra.*,sucursal.pksucursal,sucursal.nombre, sucursal.direccion AS dirsuc FROM ocompra,sucursal WHERE ocompra.pkocompra = :id AND ocompra.fksucursal = sucursal.pksucursal ');
        $query->bindParam(':id',$data);
        $query->execute();
        $query = $query->fetch(PDO::FETCH_ASSOC);
        return $query;
    }

    public function GetDataJoin($data,$anio){
           
        $query = self::$conexion->prepare('SELECT 
        ocompra.pkocompra,
        ocompra.folio,
        ocompra.fechaorden,
        ocompra.estado,
        proveedor.pkproveedor,
        proveedor.nombre AS name_proveedor FROM ocompra
    LEFT JOIN proveedor ON ocompra.fkproveedor = proveedor.pkproveedor WHERE ocompra.fksucursal = ? AND YEAR(ocompra.fechaorden) = ? ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(folio, "/", 1), "A", -1) AS UNSIGNED) DESC,folio DESC');
        $query->execute(array($data,$anio));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function AddServ($data): bool{
        $query = self::$conexion->prepare('INSERT INTO servocompra (pda,cant,fkunidad,descripcion,preciounit,subtotal,fkocompra) VALUES (?,?,?,?,?,?,?) ');

        if($query->execute(array(
                    $data["pda"],
                    $data["cantidad"],
                    $data["unidad"],
                    $data["descripcion"],
                    $data["preciounit"],
                    $data["subtotal"],
                    $data["fkocompra"]
                   
        )) > 0){
           return true;
        }

        return false;
    }
    //Obtiene los servicios de la requisición
    public function GetDataAllServ($id){
           
        $query = self::$conexion->prepare('SELECT * FROM servocompra WHERE fkocompra = ? ORDER BY pda');
        $query->execute(array($id));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function UpdateServ($data): bool{
        $query = self::$conexion->prepare('UPDATE servocompra SET pda = ?,cant = ?, fkunidad = ?,descripcion = ?,preciounit = ?,subtotal = ? WHERE pkservocompra = ?');

        if($query->execute(array(
                    $data["pda"],
                    $data["cantidad"],
                    $data["unidad"],
                    $data["descripcion"],
                    $data["preciounit"],
                    $data["subtotal"],
                    $data["pkservocompra"]
        )) > 0){
           return true;
        }

        return false;
    }

    //Función para imprimir los datos de entrega
    public function Print($id) {
        $query = self::$conexion->prepare('SELECT 
        ocompra.pkocompra,
        ocompra.fksucursal,
        ocompra.folio,
        ocompra.fechaorden,
        ocompra.fechaent,
        requisicion.folio AS folioreq,
        ocompra.moneda,
        ocompra.condpago,
        proveedor.nombre AS nomproveedor,
        ocompra.rfc,
        ocompra.direccion,
        ocompra.contacto,
        ocompra.telefono,
        ocompra.correo,
        ocompra.nproveedor,
        ocompra.direntrega,
        ocompra.telefono2,
        ocompra.email,
        ocompra.observaciones,
        ocompra.importe,
        ocompra.descto,
        ocompra.iva,
        ocompra.total,
        esolicita.nombre AS solicita_nombre, 
        esolicita.apellidos AS solicita_apellidos,
        eautoriza.nombre AS autoriza_nombre,
        eautoriza.apellidos AS autoriza_apellidos,
        ecomprador.nombre AS comprador_nombre,
        ecomprador.apellidos AS comprador_apellidos FROM ocompra
        LEFT JOIN empleado AS esolicita ON ocompra.fkesolicita = esolicita.pkempleado 
        LEFT JOIN empleado AS eautoriza ON ocompra.fkeautoriza = eautoriza.pkempleado
        LEFT JOIN empleado AS ecomprador ON ocompra.fkecomprador = ecomprador.pkempleado
        LEFT JOIN proveedor ON ocompra.fkproveedor = proveedor.pkproveedor 
        LEFT JOIN requisicion ON ocompra.fkrequisicion = requisicion.pkrequisicion WHERE ocompra.pkocompra = ? ');
        $query->execute(array($id));
        $query = $query->fetch(PDO::FETCH_ASSOC);
        return $query;
    }

    //Función para imprimir los servicios
    public function Servprint($orden){
       
        $query = self::$conexion->prepare('SELECT servocompra.*,unidad.nombre FROM servocompra 
            LEFT JOIN unidad ON servocompra.fkunidad = unidad.pkunidad WHERE fkocompra = ? ORDER BY pda
        ');
        $query->execute(array($orden));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function DeleteServ($servicio) : bool{
        $query = self::$conexion->prepare('DELETE FROM servocompra WHERE pkservocompra = ?');

       if( $query->execute(array($servicio)) > 0 ){
            return true;
       }
       return false;

    }

    public function __destruct() {
        self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
    }



    

}