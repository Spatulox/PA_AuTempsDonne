<?php

include_once('./Service/historiqueService.php');
include_once('./Models/HistoriqueModel.php');

function historiqueController($uri,$apiKey) {



    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $HistoriserService = new historiqueService();
            if(!$uri[3]){
                exit_with_content($HistoriserService->getAllHistory($apiKey));
            }


            break;
        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $HistoriserService = new historiqueService();
            var_dump('te');


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