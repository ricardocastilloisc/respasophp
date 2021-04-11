
<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion
{

    private $table = "pacientes";

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
}
