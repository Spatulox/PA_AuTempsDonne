<?php

include_once('./Service/historiqueService.php');
include_once('./Models/historiqueModel.php');
function historiqueController($uri) {

    $historiqueService = new HistoriqueService();

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':

            exit_with_message("Wtf, why are you here bro", 500);

            break;
        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $HistoriserService = new historiserService();
            echo 'te';


            break;
        case 'PUT':

            exit_with_message("Impossible to do this request", 403);
            break;

        case 'DELETE':

            break;

        default:
            header("HTTP/1.1 404 Not Found");
            echo "{\"message\": \"Not Found\"}";
            break;
    }
}

?>