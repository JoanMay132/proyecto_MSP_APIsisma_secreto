<?php 
    class Controles extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}        
        public function getControls(){
           
            $query = self::$conexion->prepare('SELECT * FROM controles INNER JOIN modulo ON controles.fkmodulo = modulo.pkmodulo');
            $query->execute();
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function getPermission($id,$sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM permiso WHERE fkusuario = :id AND fksucursal = :suc ');
            $query->bindParam(":id",$id);
            $query->bindParam(":suc",$sucursal);
            $query->execute();
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function getPermissionForUser($usuario,$control,$sucursal,$operacion) : bool{
           
            $query = self::$conexion->prepare('SELECT * FROM permiso WHERE fkusuario = :id AND fkcontrol = :controles AND fksucursal = :sucursal AND fkoperacion = :operacion ');
            $query->bindParam(":id",$usuario);
            $query->bindParam(":controles",$control);
            $query->bindParam(":sucursal",$sucursal);
            $query->bindParam(":operacion",$operacion);
            $query->execute();
            if( $query->rowCount() > 0){
                //$query = $query->fetch(PDO::FETCH_ASSOC);
                return true;
            }
           
            return false;
        }

        public function getOperations(){
           
            $query = self::$conexion->prepare('SELECT * FROM operacion ');
            $query->execute();
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function addPermission(array $data){
            $query = self::$conexion->prepare('INSERT INTO permiso (fkcontrol,fksucursal,fkusuario,fkoperacion) VALUES (?,?,?,?)  ');
            if($query->execute(array(
                $data['control'],
                $data['sucursal'],
                $data['usuario'],
                $data['operacion']
            )) > 0){
                return true;
            }

         return false;
        }

        private function updatePermission(array $data) : bool{
           
            $query = self::$conexion->prepare('UPDATE permiso SET  fkorden = ? WHERE pkentrega = ? ');
            if($query->execute(array($data["fkorden"])) > 0){
                return true;
             }

             return false;
        }


        public function Delete($id) : bool{
            $query = self::$conexion->prepare('DELETE FROM permiso WHERE pkpermiso = :id ');
            $query->bindParam(":id",$id);
           if($query->execute() > 0 ){
                return true;
           }
           return false;

        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }


    
    }

