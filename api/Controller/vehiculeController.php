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
                $VehiculeService->getVehiculeById($uri[3]);
            }

            elseif ($uri[3] == "me"){
                $VehiculeService->getMyVehicule($apiKey);
            }


            else{
                exit_with_message("You need to be admin to see all the trips", 403);
            }

            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);
            $VehiculeService = new vehiculeService();

            if ($uri[3] == "available"){
                if(!isset($json["date_start"]) || empty($json["date_start"]) || !isset($json["date_end"]) || empty($json["date_end"])){
                    exit_with_message("Missing required parameters date_start and date_end", 400);
                }
                $VehiculeService->getAvailableVehicule($apiKey, $json["date_start"], $json["date_end"]);
            }

            // Create a vehicle
            if ( !isset($json['capacite']) || !isset($json['nom_du_vehicules']) || !isset($json['nombre_de_place']) || !isset($json['id_entrepot']) || !isset($json['immatriculation']) || !isset($json['appartenance']))
            {
                exit_with_message("Plz give the name, the capacite the nom_du_vehicules, the nombre_de_place, the id_entrepot, the immatriculation, and the appartenance ", 403);
            }
            $vehicule = new VehiculeModel(1, $json['capacite'], $json['nom_du_vehicules'], $json['nombre_de_place'], $json['id_entrepot'], $json['appartenance'], -1, $json['immatriculation']);

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

function returnVehicle($dataFromDb, $id = 0){
    $vehicle = new VehiculeModel($dataFromDb[$id]["id_vehicule"], $dataFromDb[$id]["capacite"], $dataFromDb[$id]["nom_du_vehicules"], $dataFromDb[$id]["nombre_de_place"], $dataFromDb[$id]["id_entrepot"], $dataFromDb[$id]["appartenance"],  $dataFromDb[$id]["id_service"], $dataFromDb[$id]["immatriculation"]);
    return $vehicle;
    //$user[$i] = new UserModel($usersArray[$i]['id_user'], $usersArray[$i]['nom'], $usersArray[$i]['prenom'], $usersArray[$i]['date_inscription'], $usersArray[$i]['email'], $address, $usersArray[$i]['telephone'], $usersArray[$i]['id_role'], $usersArray[$i]['apikey'], $usersArray[$i]['id_index'], $usersArray[$i]['id_entrepot']);
}

?>