<?php 
    class Premobra2 extends Conexion{

        private static $conexion;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO premobra2 (nombre,cantidad,unidad,costounit,importe,contenido,fkpresupuesto) VALUES (?,?,?,?,?,?,?) ');

            if($query->execute($data) > 0){
               return true;
            }

            return false;
        }

        public function GetDataAll($presupuesto){
           
            $query = self::$conexion->prepare('SELECT * FROM premobra2 WHERE fkpresupuesto = ? ');
            $query->execute(array($presupuesto));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                       premobra2
                                                SET 
                                                    nombre = ?,
                                                    cantidad = ?,
                                                    unidad = ?,
                                                    costounit = ?,
                                                    importe = ?,
                                                    contenido = ?
                                                WHERE pkpremobra2 = ?');

           if( $query->execute($data) > 0 ){
                return true;
           }
           return false;

        }

        public function Delete($data) : bool{
            $query = self::$conexion->prepare('DELETE FROM premobra2 WHERE pkpremobra2 = ?');

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