<?php
include_once './Service/trajetService.php';
include_once './Models/trajetModel.php';
include_once './exceptions.php';

function trajetController($uri, $apiKey){

    //exit_with_message($_SERVER['REQUEST_METHOD']);

    switch ($_SERVER['REQUEST_METHOD']){

        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $TrajetService = new trajetService();
            if(!$uri[3]){
                exit_with_message("pas de trajets selectionner");
            }


            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($TrajetService->getTrajetById($uri[3]));
            }


            else{
                exit_with_message("You need to be admin to see all the trips", 403);
            }

            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $TrajetService = new trajetService();
            if(!$uri[3]){
                $TrajetService->createTrajet($json["address"]);
            }

            elseif($uri[3] && $uri[3] == "create"){
                $TrajetService->createTrajetInDB($json["address"]);
            }

            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($TrajetService->getTrajetById($uri[3]));
            }


            else{
                exit_with_message("You need to be admin to see all the trips", 403);
            }

            break;


        case 'PUT':
            break;

        case 'DELETE':
            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>