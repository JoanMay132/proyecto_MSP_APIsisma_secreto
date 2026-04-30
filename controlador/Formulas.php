<?php
class Formulas extends Conexion{

    private static $conexion;

    function __construct() 
    {
       
        self::$conexion = parent::getConexion();
       
    } 

    public function getFormulaM2($sucursal) {
        $query = self::$conexion->prepare('SELECT * FROM formulam2 WHERE  fksucursal = :suc ');
        $query->bindParam(':suc',$sucursal);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    public function updateFM2(array $data): bool{
        $query = self::$conexion->prepare('UPDATE formulam2 SET espesor = ?, factor = ?, km2 = ? WHERE pkformulam2 = ?');
        
        if($query->execute(array(
            $data['espesor'],
            $data['factor'],
            $data['km2'],
            $data['pkformulam2']
        )) > 0){
            return true;
        }
    
        return false;
    }

    //Pesos de placas
    public function getFormulaPeso($sucursal) {
        $query = self::$conexion->prepare('SELECT * FROM peso_placa WHERE  fksucursal = :suc ');
        $query->bindParam(':suc',$sucursal);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    public function updateFPeso(array $data): bool{
        $query = self::$conexion->prepare('UPDATE peso_placa SET ancho = ?, longitud = ?, placa = ?, kilom2 = ?, peso = ? WHERE pkpeso = ?');
        
        if($query->execute(array(
            $data['ancho'],
            $data['longitud'],
            $data['placa'],
            $data['kilom2'],
            $data['peso'],
            $data['pkpeso']
        )) > 0){
            return true;
        }
    
        return false;
    }

    //Pesos de Materiales Redondos
    public function getFPMaterial($sucursal) {
        $query = self::$conexion->prepare('SELECT * FROM peso_material WHERE  fksucursal = :suc ');
        $query->bindParam(':suc',$sucursal);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    public function updateFPMaterial(array $data): bool{
        $query = self::$conexion->prepare('UPDATE peso_material SET od = ?, idmat = ?, longmaterial = ?, pmaterial = ? WHERE pkpesomat = ?');
        
        if($query->execute(array(
            $data['od'],
            $data['idmat'],
            $data['longmaterial'],
            $data['pmaterial'],
            $data['pkpesomat']
        )) > 0){
            return true;
        }
    
        return false;
    }

    public function __destruct() {
        self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
    }


}

