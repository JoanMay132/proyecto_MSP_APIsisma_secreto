
<?php 
    class Employee extends Conexion{

        private static $conexion;

        public $pkemployee;


        function __construct() 
		{
           
            self::$conexion = parent::getConexion();
           
		}

        public function Add($data): bool{
            $query = self::$conexion->prepare('INSERT INTO empleado(nombre,apellidos,direccion,fkmunicipio,fkestado,cp,curp,rfc,nss,foto,fkpuesto,fksucursal) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ');

            if($query->execute(array($data['nombre'],$data['apellidos'],$data['direccion'],$data['municipio'],$data['estado'],$data['cp'],$data['curp'],$data['rfc'],$data['nss'],$data['foto'],$data['puesto'],$data['sucursal'])) > 0){
                $this->pkemployee  = self::$conexion->lastInsertId();
                
                if(!empty($data['ingreso'])){
                    $this->addHistorial($data['ingreso'],$this->pkemployee);
                }
                
                return true;
            }

            return false;
        }

        public function updateUser($user,$employee) : bool{
           
            $query = self::$conexion->prepare('UPDATE empleado SET fkusuario = ? WHERE pkempleado = ?');
            if($query->execute(array($user,$employee))){
                return true;
            }
            return false;
        }

        //Obtiene varias filas de resultados dependiendo la sucursal
        public function GetDataAll($sucursal){
           
            $query = self::$conexion->prepare('SELECT * FROM empleado WHERE fksucursal = ? ORDER BY nombre');
            $query->execute(array("{$sucursal}"));
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        //Obtiene empleados activos por sucursal segun su historial en hemployee
        public function GetDataActiveBySucursal($sucursal){
            $query = self::$conexion->prepare('SELECT empleado.*
                                               FROM empleado
                                               INNER JOIN hemployee
                                                   ON hemployee.fkempleado = empleado.pkempleado
                                                  AND hemployee.pkhemployee = (
                                                        SELECT h2.pkhemployee
                                                        FROM hemployee h2
                                                        WHERE h2.fkempleado = empleado.pkempleado
                                                        ORDER BY DATE(h2.alta) DESC, h2.pkhemployee DESC
                                                        LIMIT 1
                                                   )
                                               WHERE empleado.fksucursal = ?
                                                 AND hemployee.baja = "0000-00-00"
                                               ORDER BY empleado.nombre');
            $query->execute(array("{$sucursal}"));
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        //Obtiene un solo resultado
        public function GetData($pkempleado){
           
            $query = self::$conexion->prepare('SELECT * FROM empleado WHERE pkempleado = ?');
            $query->execute(array("{$pkempleado}"));
            return $query->fetch(PDO::FETCH_ASSOC);
        }


        //Consulta con relación a la tabla puesto y usuario
        public function GetDataJoin($sucursal){
           
            $query = self::$conexion->prepare('SELECT empleado.*,puesto.pkpuesto, puesto.nombre AS npuesto,usuario.* FROM empleado LEFT JOIN puesto ON empleado.fkpuesto = puesto.pkpuesto LEFT JOIN usuario ON empleado.fkusuario = usuario.pkusuario WHERE empleado.fksucursal = ? ORDER BY empleado.nombre');
            $query->execute(array("{$sucursal}"));
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }



        public function UpdateData($data) : bool{
            $query = self::$conexion->prepare('UPDATE 
                                                        empleado 
                                                SET 
                                                    nombre = ?, 
                                                    apellidos = ?,
                                                    direccion = ?,
                                                    fkmunicipio = ?,
                                                    fkestado = ?,
                                                    cp = ?,
                                                    curp = ?,
                                                    rfc = ?,
                                                    nss = ?,
                                                    foto = ?,
                                                    fkpuesto = ?,
                                                    fksucursal = ?
                                                WHERE pkcliente = ?');

           if( $query->execute(array($data['nombre'],$data['apellidos'],$data['direccion'],$data['municipio'],$data['estado'],$data['cp'],$data['curp'],$data['rfc'],$data['nss'],$data['foto'],$data['fkpuesto'],$data['fksucursal'],$data['sucursal'])) > 0 ){
                return true;
           }
           return false;

        }

        //Agrega un registro
        function addHistorial($valor,$pkempleado)
        {
            $query = self::$conexion->prepare('INSERT INTO hemployee (alta,fkempleado) VALUES (?,?)');
            $query->execute(array("{$valor}","{$pkempleado}"));
        }

        function updateHistorial(array $data)
        {
            $query = self::$conexion->prepare('UPDATE hemployee SET alta = ?, baja = ? WHERE pkhemployee = ?');
            $query->execute(array($data['alta'],$data['baja'],$data['pkhemployee']));
        }

        //Obtiene un unico resultado para el empleado que se consulta
        function getHistorial($pkemployee)
        {
            $query = self::$conexion->prepare('SELECT * FROM hemployee WHERE pkempleado = ?');
            $query->execute(array($pkemployee));

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }


        //Obtiene la ultima alta del empleado
        public function checkState($empleado)
        {
            $query = self::$conexion->prepare('SELECT * FROM hemployee WHERE fkempleado = ? ORDER BY DATE(alta) DESC, pkhemployee DESC LIMIT 1');
            $query->execute(array($empleado));
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function setStatus(int $empleado, int $sucursal, int $activo): bool
        {
            $query = self::$conexion->prepare('SELECT pkempleado, fkusuario, curp, rfc, nss, nombre, apellidos FROM empleado WHERE pkempleado = ? AND fksucursal = ? LIMIT 1');
            $query->execute(array($empleado, $sucursal));
            $empleadoBase = $query->fetch(PDO::FETCH_ASSOC);
            if(!$empleadoBase){
                return false;
            }

            $baja = $activo === 1 ? '0000-00-00' : date('Y-m-d');

            self::$conexion->beginTransaction();

            try {
                $idsEmpleado = array((int) $empleadoBase['pkempleado']);

                if(!empty($empleadoBase['fkusuario'])){
                    $queryEmpleados = self::$conexion->prepare('SELECT pkempleado FROM empleado WHERE fkusuario = ?');
                    $queryEmpleados->execute(array($empleadoBase['fkusuario']));
                    foreach($queryEmpleados->fetchAll(PDO::FETCH_ASSOC) as $rowEmpleado){
                        $idsEmpleado[] = (int) $rowEmpleado['pkempleado'];
                    }
                }

                foreach(array('curp', 'rfc', 'nss') as $campo){
                    if(!empty($empleadoBase[$campo])){
                        $queryCampo = self::$conexion->prepare("SELECT pkempleado FROM empleado WHERE {$campo} = ?");
                        $queryCampo->execute(array($empleadoBase[$campo]));
                        foreach($queryCampo->fetchAll(PDO::FETCH_ASSOC) as $rowEmpleado){
                            $idsEmpleado[] = (int) $rowEmpleado['pkempleado'];
                        }
                    }
                }

                if(!empty($empleadoBase['nombre']) && !empty($empleadoBase['apellidos'])){
                    $queryNombre = self::$conexion->prepare('SELECT pkempleado FROM empleado WHERE nombre = ? AND apellidos = ?');
                    $queryNombre->execute(array($empleadoBase['nombre'], $empleadoBase['apellidos']));
                    foreach($queryNombre->fetchAll(PDO::FETCH_ASSOC) as $rowEmpleado){
                        $idsEmpleado[] = (int) $rowEmpleado['pkempleado'];
                    }
                }

                $idsEmpleado = array_values(array_unique($idsEmpleado));
                $updatedRows = 0;

                foreach($idsEmpleado as $idEmpleado){
                    $queryHistorial = self::$conexion->prepare('SELECT pkhemployee FROM hemployee WHERE fkempleado = ? ORDER BY DATE(alta) DESC, pkhemployee DESC LIMIT 1');
                    $queryHistorial->execute(array($idEmpleado));
                    $historial = $queryHistorial->fetch(PDO::FETCH_ASSOC);

                    if(!$historial){
                        continue;
                    }

                    $update = self::$conexion->prepare('UPDATE hemployee SET baja = ? WHERE pkhemployee = ?');
                    if(!$update->execute(array($baja, $historial['pkhemployee']))){
                        self::$conexion->rollBack();
                        return false;
                    }

                    $updatedRows += 1;
                }

                if($updatedRows === 0){
                    self::$conexion->rollBack();
                    return false;
                }

                self::$conexion->commit();
                return true;
            } catch (Throwable $e) {
                if (self::$conexion->inTransaction()) {
                    self::$conexion->rollBack();
                }
                return false;
            }
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }

    }

?>
