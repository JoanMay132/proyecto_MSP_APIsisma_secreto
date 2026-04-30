<?php

class Requisicion extends Conexion{

    private static $conexion;

    public $pkrequisicion;

    function __construct(){

        self::$conexion = parent::getConexion();

    }

    public function AddFolio($folio,$sucursal,$fecha): bool{
        $query = self::$conexion->prepare('INSERT INTO requisicion (folio,fksucursal,fecha) VALUES (?,?,?) ');

        if($query->execute(array($folio,$sucursal,$fecha)) > 0){
            $this->pkrequisicion = self::$conexion->lastInsertId();
           return true;
        }

        return false;
    }

    //Obtiene la lista de requisiciones, implementado en Ocompra
    public function GetDataAll($id){
        
        // $sel = count($data) > 0 ? '' : '*';
        // for($i = 0; $i < count($data); $i++){
        //     $sel = $sel.$data[$i].',';
        // }
        // $sel = substr($sel,0,-1);

        $query = self::$conexion->prepare('SELECT pkrequisicion,folio,proyecto FROM requisicion WHERE fksucursal = ? ORDER BY pkrequisicion DESC');
        $query->execute(array($id));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function Update(array $data) : bool{
           
        $query = self::$conexion->prepare('UPDATE requisicion SET 
            fkorden = ?,
            proyecto = ?,
            fecha = ?,
            estado = ?,
            clasificacion = ?,
            observaciones = ?,
            fkesolicita = ?,
            fkerecibe = ?,
            fkeautoriza = ?,
            lugarent = ?
        WHERE pkrequisicion = ? ');
        if($query->execute(array(
            $data['fkorden'],
            $data['proyecto'],
            $data['fecha'],
            $data['estado'],
            $data['clasificacion'],
            $data['observaciones'],
            $data['solicita'],
            $data['recibe'],
            $data['autoriza'],
            $data['lugarent'],
            $data['pkrequisicion']
        )) > 0){
            return true;
         }

         return false;
    }

    public function GetData($data){
           
        $query = self::$conexion->prepare('SELECT * FROM requisicion,sucursal WHERE requisicion.pkrequisicion = :id AND requisicion.fksucursal = sucursal.pksucursal ');
        $query->bindParam(':id',$data);
        $query->execute();
        $query = $query->fetch(PDO::FETCH_ASSOC);
        return $query;
    }

    public function GetDataJoin($data,$anio){
           
        $query = self::$conexion->prepare('SELECT 
        requisicion.pkrequisicion,
        requisicion.folio,
        requisicion.fecha,
        requisicion.proyecto,
        requisicion.fkesolicita,
        requisicion.estado,
        empleado.pkempleado,
        empleado.nombre AS nempleado, 
        empleado.apellidos FROM requisicion
    LEFT JOIN empleado ON requisicion.fkesolicita = empleado.pkempleado WHERE requisicion.fksucursal = ? AND YEAR(requisicion.fecha) = ? ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(folio, "/", 1), "A", -1) AS UNSIGNED) DESC,folio DESC');
        $query->execute(array($data,$anio));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function AddServ($data): bool{
        $query = self::$conexion->prepare('INSERT INTO servrequisicion (pda,cantidad,unidad,descripcion,nparte,fkrequisicion) VALUES (?,?,?,?,?,?) ');

        if($query->execute(array(
                    $data["pda"],
                    $data["cantidad"],
                    $data["unidad"],
                    $data["descripcion"],
                    $data["nparte"],
                    $data["fkrequisicion"],
                   
        )) > 0){
           return true;
        }

        return false;
    }
    //Obtiene los servicios de la requisición
    public function GetDataAllServ($id){
           
        $query = self::$conexion->prepare('SELECT * FROM servrequisicion WHERE fkrequisicion = ? ORDER BY pda');
        $query->execute(array($id));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function UpdateServ($data): bool{
        $query = self::$conexion->prepare('UPDATE servrequisicion SET pda = ?,cantidad = ?,unidad = ?,descripcion = ?,nparte = ? WHERE pkservrequisicion = ?');

        if($query->execute(array(
            $data["pda"],
            $data["cantidad"],
            $data["unidad"],
            $data["descripcion"],
            $data["nparte"],
            $data["pkservrequisicion"]
        )) > 0){
           return true;
        }

        return false;
    }

    public function DeleteServ($servicio) : bool{
        $query = self::$conexion->prepare('DELETE FROM servrequisicion WHERE pkservrequisicion = ?');

       if( $query->execute(array($servicio)) > 0 ){
            return true;
       }
       return false;

    }

     //Función para imprimir los datos de requisicion
     public function Print($id) {
        $query = self::$conexion->prepare('SELECT 
        requisicion.pkrequisicion,
        requisicion.fksucursal,
        requisicion.folio,
        requisicion.fecha,
        requisicion.proyecto,
        requisicion.clasificacion,
        requisicion.observaciones,
        requisicion.lugarent,
        requisicion.estado,
        esolicita.nombre AS solicita_nombre, 
        esolicita.apellidos AS solicita_apellidos,
        eautoriza.nombre AS autoriza_nombre,
        eautoriza.apellidos AS autoriza_apellidos,
        erecibe.nombre AS recibe_nombre,
        erecibe.apellidos AS recibe_apellidos FROM requisicion
        LEFT JOIN empleado AS esolicita ON requisicion.fkesolicita = esolicita.pkempleado 
        LEFT JOIN empleado AS eautoriza ON requisicion.fkeautoriza = eautoriza.pkempleado
        LEFT JOIN empleado AS erecibe ON requisicion.fkerecibe = erecibe.pkempleado WHERE requisicion.pkrequisicion = ? ');
        $query->execute(array($id));
        $query = $query->fetch(PDO::FETCH_ASSOC);
        return $query;
    }

    //Función para imprimir los servicios
    public function Servprint($requisicion){
       
        $query = self::$conexion->prepare('SELECT servrequisicion.*,unidad.nombre FROM servrequisicion 
            LEFT JOIN unidad ON servrequisicion.unidad = unidad.pkunidad WHERE fkrequisicion = ? ORDER BY pda
        ');
        $query->execute(array($requisicion));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }

    public function __destruct() {
        self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
    }

    

}