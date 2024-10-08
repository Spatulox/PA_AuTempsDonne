<?php
include_once('./Service/vehiculeService.php');
include_once('./Models/vehiculeModel.php');
include_once('./returnFunctions.php');

function vehiculeController($uri, $apiKey){

    switch ($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $VehiculeService = new vehiculeService();
            if(!$uri[3]){
                $VehiculeService->getAllVehicule($apiKey);
            }


            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                $VehiculeService->getVehiculeById($uri[3]);
            }

            elseif($uri[3] == "association"){
                $VehiculeService->getAllVehiculeAssoc($apiKey);
            }

            elseif ($uri[3] == "me"){
                $VehiculeService->getMyVehicule($apiKey);
            }

            elseif($uri[3] == "booked"){
                // Gett all booked vehicle, even if the booked is over
                if($uri[4] == "me"){
                    $VehiculeService->getBookedVehicle($apiKey, "1");
                    exit();
                }
                $VehiculeService->getBookedVehicle($apiKey);
                exit();
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
                    exit_with_message("Missing required parameters date_start and date_end", 403);
                }

                if($uri[4] == "assoc"){
                    $VehiculeService->getVehiculeAssocAvailable($apiKey, $json["date_start"], $json["date_end"]);
                    exit();
                }

                $VehiculeService->getAvailableVehicule($json["date_start"], $json["date_end"]);
                exit();
            }

            if($uri[3] == "booked"){
                if(!isset($json["id_vehicule"]) || empty($json["id_vehicule"]) || !isset($json["date_start"]) || empty($json["date_start"]) || !isset($json["date_end"]) || empty($json["date_end"])){
                    exit_with_message("Missing required parameters id_vehicule, date_start and date_end", 403);
                }
                $VehiculeService->bookingVehicle($apiKey, $json["id_vehicule"], $json["date_start"], $json["date_end"]);
                exit();
            }

            // Create a vehicle
            if ( !isset($json['capacite']) || !isset($json['nom_du_vehicules']) || !isset($json['nombre_de_place']) || !isset($json['id_entrepot']) || !isset($json['immatriculation']) || !isset($json['appartenance']))
            {
                exit_with_message("Plz give the name, the capacite the nom_du_vehicules, the nombre_de_place, the id_entrepot, the immatriculation, and the appartenance", 403);
            }
            $vehicule = new VehiculeModel(1, $json['capacite'], $json['nom_du_vehicules'], $json['nombre_de_place'], $json['id_entrepot'], $json['appartenance'], -1, $json['immatriculation']);

            $VehiculeService->createVehicule($vehicule,$apiKey);

            break;

        case 'PUT':
            exit_with_message("Unauthorized to do a PUT request", 403);
            break;

        case 'DELETE':$VehiculeService = new vehiculeService();

            if($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                $VehiculeService->deleteVehicule($uri[3],$apiKey);
            }

            if($uri[3] == "unbooked"){
                if(!$uri[4]){
                    exit_with_message("Missing required GET parameters id_service", 403);
                }
                $VehiculeService->unBookingVehicle($uri[4], $apiKey);
                exit();
            }

            exit_with_message("Nothing specified", 400);

            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>