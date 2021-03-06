
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

    private $token = "";

    private $img = "";
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

        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscartoken();
            if ($arrayToken) {
                $this->actualizarToken($arrayToken[0]['TokenId']);
            } else {
                return $_respuestas->error_401("Token que envio es invalido o caducado");
            }
        }

        if (
            !isset($datos['nombre'])
            || !isset($datos['dni'])
            || !isset($datos['correo'])
        ) {
            return $_respuestas->error_400();
        } else {


            if(isset($datos['img']))
            {
                $this->img = $this->procesarImagen($datos['img']);
            }



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
            . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo,img) 
        VALUES 
        ('"
            . $this->dni . "','"
            . $this->nombre . "','"
            . $this->direccion . "','"
            . $this->codigoPostal . "','"
            . $this->telefono . "','"
            . $this->genero . "','"
            . $this->fechaNacimiento . "','"
            . $this->correo . "','"
            . $this->img . "')";
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



        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscartoken();
            if ($arrayToken) {
                $this->actualizarToken($arrayToken[0]['TokenId']);
            } else {
                return $_respuestas->error_401("Token que envio es invalido o caducado");
            }
        }

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

    public function delete($json, $token = Null)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if(isset($token)){
            $datos['token'] = $token;
        }

        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscartoken();
            if ($arrayToken) {
                $this->actualizarToken($arrayToken[0]['TokenId']);
            } else {
                return $_respuestas->error_401("Token que envio es invalido o caducado");
            }
        }

        if (
            !isset($datos['pacienteId'])
        ) {
            return $_respuestas->error_400();
        } else {
            $this->pacienteId = $datos['pacienteId'];

            $resp = $this->eliminarPaciente();

            if ($resp) {
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array("pacienteId" => $this->pacienteId);
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }


    private function eliminarPaciente()
    {
        $query = "DELETE FROM " . $this->table . " WHERE PacienteId = '" . $this->pacienteId . "'";
        $resp = parent::nonQuery($query);

        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }


    private function buscartoken()
    {
        $query = "SELECT TokenId, UsuarioId, Estado FROM usuarios_token WHERE Token = '" . $this->token . "' And Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }

    private function actualizarToken($tokenId)
    {

        //date sirve para que me transforme la fecha actual 
        //ahora dentro de parentesis es para saber de que formato va ser
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token  SET Fecha = '" . $date . "' WHERE TokenId = '" . $tokenId . "'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }

    private function procesarImagen($img){
        //aqui apuntamos a los que haya y nos da la direccion donde va estar la carpeta
        $direccion = dirname(__DIR__) . "\public\imagenes\\";
        //hacemos un array en dos o mas de un string quitando le el separador 
        $partes = explode(";base64,", $img);

        $extencion  = explode('/', mime_content_type($img))[1];

        $imagen_base64 = base64_decode($partes[1]);

        ///uniqid es para generar un string unico 
        $file = $direccion . uniqid() . "." .$extencion;
        //esto guarda el archivo en la carpeta del servidro donde asignamos
        file_put_contents($file, $imagen_base64);

        //esto pone las / en el nombre del archivo
        $nuevadireccion = str_replace('\\', '/', $file);

        return $nuevadireccion;

    }
}
