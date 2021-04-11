<?php

require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;


if($_SERVER['REQUEST_METHOD'] == "GET"){

    //los del get van hacer query params
    //importante
    //el $_GET[XXXXX] se va poder usar en todos los metodos del api rest
    //[PUT,GET,DELETE, POST]
    //ten encuenta que esto es lo que sale en la url
    //end de ahi lo estas sacando
    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $listapacientes  = $_pacientes->listaPaciente($page);
        echo json_encode($listapacientes);
    }else if(isset($_GET["id"])){
        $pacienteID = $_GET["id"];
        $datospaciente = $_pacientes->obtenerpaciente($pacienteID);
        echo json_encode($datospaciente);
    }
    
}
else if($_SERVER['REQUEST_METHOD'] == "POST"){
    echo "hola post";
    
}
else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    echo "hola put";
    
}
else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    echo "hola delete";

}else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
}