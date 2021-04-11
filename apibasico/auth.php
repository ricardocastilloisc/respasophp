<?php
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';


$_auth = new auth;
$_respuestas = new respuestas;


if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    //recibir datos
    //esto sirve para extraer lo que le enviamos y sale como json
    $postBody = file_get_contents("php://input");

    //enviamos estados datos al manejador
    $datosArray = $_auth->login($postBody);

    //json_encode convierte un array con keys en un json entendible
    //importante en php siempre transformar el json en array que se entienda
    //devolvemos una respuesta 
    //esto es para decir que tipo de datos va ser
    header('Content-Type: application/json');
    //esatdo de lo que estamos respondiendo
    if(isset($datosArray["result"]["error_id"]))
    {
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    //enviamos la respuesta de la peticion
    echo json_encode($datosArray);
}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
}
