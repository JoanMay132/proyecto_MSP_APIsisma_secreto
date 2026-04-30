<?php 
    class Predefinido extends Conexion{

        private static $conexion;

        public $pkpresupuesto;

        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add(array $data): bool{
            $query = self::$conexion->prepare('INSERT INTO predefinido (
                folio,
                fkcotizacion,
                fkcliente,
                pda,
                servicio,
                solicita,
                fecha,
                textra,
                utiempo,
                costoextra,
                htamenor,
                segpersonal,
                importepcns,
                cantmult,
                cantdiv,
                indirectos,
                financiamiento,
                utilidad,
                descripcion,
                total,
                tipo,
                fkservcot,
                fksucursal
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array(
                $data["folio"],
                $data["fkcotizacion"],
                $data["fkcliente"],
                $data["pda"],
                $data["servicio"],
                $data["solicita"],
                $data["fecha"],
                $data["textra"],
                $data["utiempo"],
                $data["costoextra"],
                $data["htamenor"],
                $data["segpersonal"],
                $data["importepcns"],
                $data["cantmul"],
                $data["cantdiv"],
                $data["indirectos"],
                $data["financiamiento"],
                $data["utilidad"],
                $data["descripcion"],
                $data["total"],
                $data["tipo"],
                $data["fkservcot"],
                $data["fksucursal"]
            )) > 0){
                $this->pkpresupuesto = self::$conexion->lastInsertId();
               return true;
            }

            return false;
        }



        public function UpdateData(array $data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        predefinido
            SET 
                folio = ?,
                fkcotizacion = ?,
                fkcliente = ?,
                pda = ?,
                servicio = ?,
                solicita = ?,
                fecha = ?,
                textra = ?,
                utiempo = ?,
                costoextra = ?,
                htamenor = ?,
                segpersonal = ?,
                importepcns = ?,
                cantmult = ?,
                cantdiv = ?,
                indirectos = ?,
                financiamiento = ?,
                utilidad = ?,
                descripcion = ?,
                fkservcot = ?,
                total = ?
                    WHERE pkpresupuesto = ?');

           if( $query->execute(array(
                $data["folio"],
                $data["fkcotizacion"],
                $data["fkcliente"],
                $data["pda"],
                $data["servicio"],
                $data["solicita"],
                $data["fecha"],
                $data["textra"],
                $data["utiempo"],
                $data["costoextra"],
                $data["htamenor"],
                $data["segpersonal"],
                $data["importepcns"],
                $data["cantmul"],
                $data["cantdiv"],
                $data["indirectos"],
                $data["financiamiento"],
                $data["utilidad"],
                $data["descripcion"],
                $data["fkservcot"],
                $data["total"],
                $data["pkpresupuesto"]

        )) > 0 ){
                return true;
           }
           return false;

        }

        // function findServcot($id, $sub = "") : bool{ //Consulta si ya existen registros del servicio
        //     $sub = $sub != "" ? 1 : 0;
        //     $query = self::$conexion->prepare('SELECT fkservcot,pkpresupuesto FROM presupuesto WHERE fkservcot = :id AND tipo = :tipo ');
        //     $query->bindParam(':id',$id);
        //     $query->bindParam(':tipo',$sub);
        //     $query->execute();
        //     if($query->rowCount() > 0){
        //         $this->pkpresupuesto = $query->fetch()['pkpresupuesto'];
        //         return true;
        //     }
        //     return false;
        // }

        public function GetData($data){
           
            $query = self::$conexion->prepare('SELECT * FROM predefinido,sucursal WHERE predefinido.pkpresupuesto = :id AND predefinido.fksucursal = sucursal.pksucursal ');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function GetDataList($sucursal)
        {
            $query = self::$conexion->prepare('SELECT 
                predefinido.pkpresupuesto,
                predefinido.servicio,
                predefinido.total
             FROM predefinido  WHERE predefinido.fksucursal = ? ORDER BY folio,pda DESC' );
            $query->execute(array($sucursal));
            $query = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query;
        }

        public function Delete($data) : bool{
            $query = self::$conexion->prepare('DELETE FROM predefinido WHERE pkpresupuesto = ?');

           if( $query->execute(array($data)) > 0 ){
                return true;
           }
           return false;
        }

        public function GetTExtra($data){
           
            $query = self::$conexion->prepare('SELECT textra,utiempo,costoextra FROM predefinido WHERE pkpresupuesto = :id');
            $query->bindParam(':id',$data);
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query;
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    
        
    }
