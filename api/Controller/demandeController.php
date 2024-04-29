<?php

include_once('./Service/demandeService.php');
include_once('./Models/demandeModel.php');
function demandeController($uri, $apikey){

    $service = new DemandeService();

    switch ($_SERVER['REQUEST_METHOD']){
        case 'GET':

            if($uri[3] == "all"){
                $service->getAll($apikey);
            }
            elseif ($uri[3]){
                $service->getViaUser($uri[3], $apikey);
            }
            else{
                $service->getViaApikey($apikey);
            }

            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $service = new DemandeService();

            $service->createDemande($apikey, $json);

            break;

        case 'PUT':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(!isset($json["id_planning"]) || !isset($json["id_demande"])){
                exit_with_message("You need to specify the id of the planning");
            }

            $service = new DemandeService();
            $service->updateDemande($json["id_demande"], $json["id_planning"], $apikey);

            break;

        case 'DELETE':

            $role = getRoleFromApiKey($apikey);

            if($role > 2 && $role != 5){
                exit_with_message("You can't delete a demande");
            }

            $service = new DemandeService();
            $service->deleteDemande($uri[4], $apikey);
            break;

        default:
            header("HTTP/1.1 404 Not Found");
            echo "{\"message\": \"Not Found\"}";
            break;
    }
}