<?php
//consumo las clases de los archivos
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

//heredo la conexion
class auth extends conexion
{

    public function login($json)
    {
        $_respuestas = new respuestas();

        //convertir el json a un array con keys al final true para asociativo osea con keys
        $datos = json_decode($json, true);

        if (!isset($datos['usuario']) || !isset($datos['password'])) {
            //error campos 
            return $_respuestas->error_400();
        } else {
            //esto se consume
            $usuario = $datos['usuario'];
            $password = $datos['password'];

            $password = parent::encriptar($password);

            $datos = $this->obtenerDatosUsuarios($usuario);
            if ($datos) {
                //si existe el usuario
                //verificar si la contraseña es correcta
                if ($password === $datos[0]['Password']) {
                    if ($datos[0]['Estado'] === 'Activo') {
                        $verificar = $this->insertarToken($datos[0]['UsuarioId']);
                        if ($verificar) {
                            // si se guardo;

                            //accedo a los atribnutos publicos de una clase que este en php
                            $result = $_respuestas->response;
                            $result['result'] = array('token' => $verificar);
                            return $result;

                        } else {
                            return $_respuestas->error_500('Error interno, no hemos podido guardar');
                        }
                    } else {
                        return $_respuestas->error_200('El usuario esta temporalmente suspendido');
                    }
                } else {
                    return $_respuestas->error_200('El usuario o contraseña son incorrectos');
                }
            } else {
                // si no existe el usuario
                return $_respuestas->error_200("El usuario $usuario no existe");
            }
        }
    }

    private function obtenerDatosUsuarios($correo)
    {
        $query = "select UsuarioId, Password, Estado from usuarios where usuario = '$correo'";
        //para obtener metodos de los padres tienes que usar parent::
        $datos = parent::obtenerDatos($query);
        if (isset($datos[0]["UsuarioId"])) {
            return $datos;
        } else {
            return 0;
        }
    }

    private function insertarToken($usuarioid)
    {
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        //date sirve para que me transforme la fecha actual 
        //ahora dentro de parentesis es para saber de que formato va ser
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha) VALUES ('$usuarioid','$token','$estado','$date')";
        $verificar = parent::nonQuery($query);
        if ($verificar) {
            return $token;
        } else {
            return 0;
        }
    }
}
