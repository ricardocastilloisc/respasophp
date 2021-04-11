<?php
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';


$_auth = new auth;
$_respuestas = new respuestas;


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //esto sirve para extraer lo que le enviamos y sale como json
    $postBody = file_get_contents("php://input");
    $datosArray = $_auth->login($postBody);

    //json_encode convierte un array con keys en un json entendible
    //importante en php siempre transformar el json en array que se entienda
    print_r(json_encode($datosArray));
}else{
    echo "metodo no permitido";
}

?>