<?php

require("conexion.php");

class claseBD extends PDO
{
    //varuables
    private $user 	  = "root";
    private $pass 	  = "";
    private $conexion = "";
    //constructor
    public function __construct(){
        try{
            //indicar moto y base de datos
            $this->conexion = SGBD.":host=".HOST.";dbname=".BD;
            //crear conexion
            parent::__construct($this->conexion, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        //excepcion
        catch (PDOException $e){
            //si falla la conexion
            echo "Ha surgido un error y no se puede conectar a la base de datos<br>" . $e->getMessage();
        }
    }
}