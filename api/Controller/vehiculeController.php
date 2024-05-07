<?php
include_once('./Service/vehiculeService.php');
include_once('./Models/vehiculeModel.php');

function vehiculeController($uri, $apiKey){

    switch ($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $VehiculeService = new vehiculeService();
            if(!$uri[3]){
                exit_with_content($VehiculeService->getAllVehicule($apiKey));
            }


            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($VehiculeService->getVehiculeById($uri[3],$apiKey));
            }


            else{
                exit_with_message("You need to be admin to see all the trips", 403);
            }

            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $VehiculeService = new vehiculeService();

            if ( !isset($json['capacite']) || !isset($json['nom_du_vehicules']) || !isset($json['nombre_de_place']) || !isset($json['id_entrepot']))
            {
                exit_with_message("Plz give the name, the capacite the nom_du_vehicules, the nombre_de_place and the id_entrepot ", 403);
            }
            $vehicule = new VehiculeModel(1, $json['capacite'], $json['nom_du_vehicules'], $json['nombre_de_place'], $json['id_entrepot']);

            exit_with_content($VehiculeService->createVehicule($vehicule,$apiKey));

            break;

        case 'PUT':
            break;

        case 'DELETE':$VehiculeService = new vehiculeService();

            if(!$uri[3]){
                exit_with_message("No Vehicule specified", 400);
            }
            $VehiculeService->deleteVehicule($uri[3],$apiKey);
            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>