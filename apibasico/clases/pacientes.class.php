
<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion
{

    private $table = "pacientes";
    private $pacienteid = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";
    //cuando pongo = significa que es opcional
    //instancio desde que calor va ser mi variable
    public function listaPaciente($pagina = 1)
    {

        //esto es la operacion de paginado
        $inicio = 0;
        //esto es lo unico que se va poder cambiar;
        $cantidad = 100;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1) + 1);
            $cantidad = $cantidad * $pagina;
        }

        $query = "select PacienteId,Nombre, DNI, Telefono, Correo from " . $this->table . " limit $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

    public function obtenerpaciente($id)
    {
        $query = "select * from " . $this->table . " where  PacienteId = '$id'";
        return parent::obtenerDatos($query);
    }

    public function post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (
            !isset($datos['nombre'])
            || !isset($datos['dni'])
            || !isset($datos['correo'])
        ) {
            return $_respuestas->error_400();
        } else {
            $this->nombre = $datos['nombre'];
            $this->dni = $datos['dni'];
            $this->correo = $datos['correo'];
            if (isset($datos['telefono'])) {
                $this->telefono = $datos['telefono'];
            }
            if (isset($datos['direccion'])) {
                $this->direccion = $datos['direccion'];
            }
            if (isset($datos['codigoPostal'])) {
                $this->codigoPostal = $datos['codigoPostal'];
            }
            if (isset($datos['genero'])) {
                $this->genero = $datos['genero'];
            }
            if (isset($datos['fechaNacimiento'])) {
                $this->fechaNacimiento = $datos['fechaNacimiento'];
            }

            $resp = $this->insertarPaciente();
            if ($resp) {
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array("pacienteId" => $resp);
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function insertarPaciente()
    {
        $query = "INSERT INTO " . $this->table
            . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo) 
        VALUES 
        ('"
            . $this->dni . "','"
            . $this->nombre . "','"
            . $this->direccion . "','"
            . $this->codigoPostal . "','"
            . $this->telefono . "','"
            . $this->genero . "','"
            . $this->fechaNacimiento . "','"
            . $this->correo . "')";
        $resp = parent::nonQueryId($query);
        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }

    public function put($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (
            !isset($datos['pacienteId'])
        ) {
            return $_respuestas->error_400();
        } else {
            $this->pacienteId = $datos['pacienteId'];
            if (isset($datos['dni'])) {
                $this->dni = $datos['dni'];
            }
            if (isset($datos['nombre'])) {
                $this->nombre = $datos['nombre'];
            }
            if (isset($datos['correo'])) {
                $this->correo = $datos['correo'];
            }
            if (isset($datos['telefono'])) {
                $this->telefono = $datos['telefono'];
            }
            if (isset($datos['direccion'])) {
                $this->direccion = $datos['direccion'];
            }
            if (isset($datos['codigoPostal'])) {
                $this->codigoPostal = $datos['codigoPostal'];
            }
            if (isset($datos['genero'])) {
                $this->genero = $datos['genero'];
            }
            if (isset($datos['fechaNacimiento'])) {
                $this->fechaNacimiento = $datos['fechaNacimiento'];
            }

            $resp = $this->modificarPaciente();
            
            if ($resp) {
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array("pacienteId" => $this->pacienteId);
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
            
        }
    }


    private function modificarPaciente()
    {
        $query = "UPDATE " . $this->table . " SET 
        DNI ='" . $this->dni . "',
        Direccion ='"    . $this->nombre . "',
        Nombre ='"    . $this->direccion . "',
        CodigoPostal ='"    . $this->codigoPostal . "',
        Telefono ='"    . $this->telefono . "',
        Genero ='"    . $this->genero . "',
        FechaNacimiento ='"    . $this->fechaNacimiento . "',
        Correo ='"    . $this->correo . "' WHERE PacienteId = '" . $this->pacienteId . "'";
        $resp = parent::nonQuery($query);
        
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }
}
