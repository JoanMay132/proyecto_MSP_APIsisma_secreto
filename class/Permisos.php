<?php  
  class Permisos extends Conexion{

    private static $conexion;

    private array $operacion = [];
    private array $sucursal = [];

    public array $result = [];

    public function existPermission($idUsuario) : bool{
        self::$conexion = parent::getConexion();
        //Se consulta la tabla permiso
        $query = self::$conexion->prepare("SELECT * FROM permiso WHERE fkusuario = :id ");
        $query->bindParam("id",$idUsuario);
        if($query->execute() > 0){
            $this->result = $query->fetchAll(PDO::FETCH_ASSOC);
            return true;
        }
        return false;
    }

    //Ejecuta la lista de las sucursales con permisos en el control seleccionado
    public function getBranchInPermission(array $result, int $control) : bool{
        $contador = 0;

        if($result == []){
            return false;
        }
        foreach ($result as $value) {
                if($value['fkcontrol'] == $control){
                    $this->sucursal[] = $value['fksucursal'];
                    $contador++;        
                }
        }

        return $contador > 0 ? true : false;
    }

    //Ejecuta la lista y verifica las operaciones en el control y la sucursal que se especifique
    public function getPermissionControl(array $resul, int $control, int $branch) : bool {
        $contador = 0;

        if($resul == []){
            return false;
        }

        foreach ($resul as $value) {
                if($value['fkcontrol'] === $control && $value['fksucursal'] === $branch){
                    $this->operacion[] = $value['fkoperacion'];
                    $contador++;
                }     
        }

        return $contador > 0 ? true : false;

    }
    //Ejecuta la lista de las sucursales con el permiso que se le especifique
    public function listBranchInPermission(array $result, int $operacion, int $control ) : void{

        foreach ($result as $value) {
            if($value['fkoperacion'] == $operacion && $value['fkcontrol'] == $control){
                $this->sucursal[] = $value['fksucursal'];
            }
        }

    }

    public function getOperacion(){
        return $this->operacion;
    }

    public function getBranch(){
        $sucursal = array_unique($this->sucursal);
        return $sucursal;
    }

    public function __destruct() {
        self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
    }

    

     
 }


//  $rol = new Permisos();

//  if($rol->existPermission(4)){
//         foreach ($rol->getBranches() as $sucursal){
//             echo $sucursal;
//         }
//  }

 