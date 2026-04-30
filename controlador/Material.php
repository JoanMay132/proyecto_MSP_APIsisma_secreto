<?php 
    class Material extends Conexion{

        private static $conexion;

        public $pkmaterial;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO material(nombre,origen,unidad,costo,fksucursal) VALUES (?,?,?,?,?) ');

            if($query->execute($data) > 0){
                $this->pkmaterial = self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }

        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM material WHERE fksucursal = ? ORDER BY nombre');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        material
                                                SET 
                                                    nombre = ?,
                                                    origen = ?,
                                                    unidad = ?,
                                                    costo = ?
                                                WHERE pkmaterial= ?');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        public function Delete($data) : bool{
            $query = self::$conexion->prepare('DELETE FROM material WHERE pkmaterial = ?');

           if( $query->execute(array($data)) > 0 ){
                return true;
           }
           return false;

        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        

    }

?>