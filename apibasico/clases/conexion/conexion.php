<?php
class conexion
{

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;


    function __construct()
    {
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if ($this->conexion->connect_errno) {
            echo "algo va mal con la conexion";
            die();
        }
    }

    private function datosConexion()
    {
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config.json");
        return json_decode($jsondata, true);
    }


    //para poder identificar los carrecteres especiales
    private function convertirUTF8($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    //obtener datos como arrays con keys para poder convertrir a json
    public function obtenerDatos($sqlstr)
    {
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }


    //retrono el numero de elementos afectados
    //uno global
    public function nonQuery($sqlstr)
    {
        $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }


    //INSERT 
    //retorna el ID 
    public function nonQueryId($sqlstr)
    {
        $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if ($filas >= 1) {
            return $this->conexion->insert_id;
        } else {
            return 0;
        }
    }
    //esto solo se puede usar si se hereda
    protected function encriptar($string){
        return md5($string);
    }

}
