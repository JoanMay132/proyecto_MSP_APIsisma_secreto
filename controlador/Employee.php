
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
            $query = self::$conexion->prepare('SELECT * FROM hemployee WHERE DATE(alta) = (SELECT MAX(DATE(alta)) FROM hemployee WHERE fkempleado = ?) AND fkempleado = ?');
            $query->execute(array($empleado,$empleado));
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function __destruct() {
            self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
        }
    

    }

?>