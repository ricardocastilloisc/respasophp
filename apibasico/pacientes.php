<?php

require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;


if ($_SERVER['REQUEST_METHOD'] == "GET") {

    //los del get van hacer query params
    //importante
    //el $_GET[XXXXX] se va poder usar en todos los metodos del api rest
    //[PUT,GET,DELETE, POST]
    //ten encuenta que esto es lo que sale en la url
    //end de ahi lo estas sacando
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
        $listapacientes  = $_pacientes->listaPaciente($page);
        header('Content-Type: application/json');
        echo json_encode($listapacientes);
        http_response_code(200);
    } else if (isset($_GET["id"])) {
        $pacienteID = $_GET["id"];
        $datospaciente = $_pacientes->obtenerpaciente($pacienteID);
        header('Content-Type: application/json');
        echo json_encode($datospaciente);
        http_response_code(200);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos esto al manejador
    $res = $_pacientes->post($postBody);
    header('Content-Type: application/json');
    //esatdo de lo que estamos respondiendo
    if (isset($res["result"]["error_id"])) {
        $responseCode = $res["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    //enviamos la respuesta de la peticion
    echo json_encode($res);
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");

    //enviamos esto al manejador
    $res = $_pacientes->put($postBody);
    header('Content-Type: application/json');
    //esatdo de lo que estamos respondiendo
    if (isset($res["result"]["error_id"])) {
        $responseCode = $res["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    //enviamos la respuesta de la peticion
    echo json_encode($res);

} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    echo "hola delete";
} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
}
