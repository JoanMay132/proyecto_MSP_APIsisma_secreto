<?php 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author Freddy Hernandez
 */
class Conexion{
    // private  $conect = [
    //     "server" => "localhost",
    //     "user" => "root",
    //     "password" => "",
    //     "database" => "msp_api",
    //     "port" =>""
    // ];

    private  $conect = [
        "server" => "localhost",
        "user" => "root",
        "password" => "",
        "database" => "mspetrol_msp_api",
        "port" =>""
    ];
    private $conexion;

    private function conexion(){
        
        try{
            $this->conexion = new PDO('mysql:host='.$this->conect["server"].';dbname='.$this->conect["database"].';charset=utf8mb4', $this->conect["user"], $this->conect["password"]);
            date_default_timezone_set("America/Mexico_City");
        }
        catch(PDOException $e){
            echo "Error de conexion ".$e->getMessage();
        }
    }

    protected function getConexion(){
        $this->conexion();
        return $this->conexion;
    }


}//Fin de la clase


