<?php
class SubFolio extends Conexion
{
    
    private $contador = 1;
    private $folio;
    private static $conexion;
    private $tabla;
    private $campo;
    private $reinicio;
    private $query;
    private $compara;
    private $nclatura;
    function __construct($tabla, $campo, $compara = "" ,$reinicio = "", $nclatura = "",$query = ""){
        self::$conexion = parent::getConexion();
        $this->tabla = $tabla;
        $this->campo = $campo;
        $this->query = $query;
        $this->reinicio = $reinicio;
        $this->compara = $compara;
        $this->nclatura = $nclatura;

        $this->setFolio();
    }

    private function Check() : bool{
        $query = self::$conexion->query("SELECT MAX($this->campo) as $this->campo  FROM $this->tabla WHERE $this->compara = $this->reinicio ");
        $resul = $query->fetch(PDO::FETCH_ASSOC);
        if($resul['folio'] == null ){
            
            $this->folio = $this->nclatura;
            return false;
        }
        $getFolio = explode("-",$resul["$this->campo"]);
        $this->folio = $getFolio[0]; 
        $this->contador = (int) $getFolio[1];
        return true;
    }

    private function setFolio(){
        if($this->Check()){
            $this->contador++;
        }
        $this->folio = $this->folio."-". $this->contador;
    }

    public function getFolio() : string{
        return $this->folio; 

    }

    public function __destruct() {
        self::$conexion = null; // Cierra la conexión cuando el objeto se destruye
    }
   
}


?>