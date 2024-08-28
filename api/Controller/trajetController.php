<?php
include_once './Service/trajetService.php';
include_once './Models/trajetModel.php';
include_once './exceptions.php';

function trajetController($uri, $apiKey){

    switch ($_SERVER['REQUEST_METHOD']){

        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $TrajetService = new trajetService();
            if(!$uri[3]){
                exit_with_message("Pas de trajets selectionné");
            }


            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                $TrajetService->getTrajetById($uri[3]);
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

            if(!isset($json["address"]) || empty($json["address"])){
                exit_with_message("You need to give valid addresses", 403);
            }

            $TrajetService = new trajetService();
            if(!$uri[3]){
                $TrajetService->createTrajet($json["address"]);
            }

            elseif($uri[3] && $uri[3] == "create"){
                if(!isset($json["id_vehicule"]) || empty($json["id_vehicule"])){
                    exit_with_message("You need to give a vehicule", 403);
                }
                $TrajetService->createTrajetInDB($json["address"], $json["id_vehicule"]);
            }

            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                $TrajetService->getTrajetById($uri[3]);
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