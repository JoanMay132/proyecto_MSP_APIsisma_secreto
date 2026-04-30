<?php 
    class MObra extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO operario(descripcion,origen,costo,fksucursal) VALUES (?,?,?,?) ');

            if($query->execute($data) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM operario WHERE fksucursal = ? ORDER BY descripcion');
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }



        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        operario
                                                SET 
                                                    descripcion = ?,
                                                    origen = ?,
                                                    costo = ?
                                                WHERE pkoperario= ?');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        public function Delete($data) : bool{
            $query = self::$conexion->prepare('DELETE FROM operario WHERE pkoperario = ?');

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