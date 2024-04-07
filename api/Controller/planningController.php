<?php
include_once './Service/planningService.php';
include_once './Models/organisationModel.php';
include_once './exceptions.php';

function planningController($uri, $apiKey) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $planningService = new PlanningService();

           
            if(!$uri[3]){
                exit_with_content($planningService->getAllPlanning());
            }

            
            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($planningService->getPlanningById($uri[3]));
            }

            else{
                exit_with_message("You need to be admin to see all the planning", 403);
            }
            
            break;
        case 'POST':
            $planningService = new PlanningService();

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if (isset($json['action'])) {
                // Si l'action est de créer une nouvelle planification
                if ($json['action'] === 'create_planning') {
                    if (!isset($json['id_user']) || !isset($json['id_activite']) || !isset($json['description']) || !isset($json['lieux']) || !isset($json['date_activite']) || !isset($json['id_activite'])) {
                        exit_with_message("Please provide all required fields to create a new planning", 400);
                    }

                    $planning = new PlanningModel(
                        $json['id_user'],
                        $json['id_activite'],
                        $json['description'],
                        $json['lieux'],
                        $json['date_activite'],
                        $json['id_activite']
                    );

                    $createdPlanning = $planningService->createPlanning($planning);

                    if ($createdPlanning) {
                        exit_with_content($createdPlanning);
                    } else {
                        exit_with_message("Error while creating the planning.", 500);
                    }
                }
                // Si l'action est de rejoindre une activité
                elseif ($json['action'] === 'join_activity') {
                    if (!isset($json['user_id']) || !isset($json['id_planning'])) {
                        exit_with_message("Please provide both user_id and id_planning to join an activity", 400);
                    }
                    
                    // Appel à la méthode pour rejoindre une activité dans le service de planification
                    $result = $planningService->joinActivity($json['user_id'], $json['id_planning']);
                    
                    if ($result) {
                        exit_with_content("User joined activity successfully");
                    } else {
                        exit_with_message("Failed to join activity", 500);
                    }
                } else {
                    exit_with_message("Invalid action specified", 400);
                }
            } else {
                exit_with_message("No action specified", 400);
            }
            break;




            case 'PUT':
                $planningService = new PlanningService();

                $body = file_get_contents("php://input");
                $json = json_decode($body, true);
                if (!isset($json["id_user"]) || !isset($json["id_activite"]) || !isset($json["description"]) || !isset($json["lieux"]) || !isset($json["date_activite"]) || !isset($json["index_activite"])){
                    exit_with_message("Plz give, at least, the id_user, id_activite, description, lieux, date_activite and index_activite");
                }
                // Valider les données reçues ici
                exit_with_content($planningService->updatePlanning($uri[3], $json["id_user"], $json["id_activite"], $json["description"], $json["lieux"], $json["date_activite"], $json["index_activite"]));
                break;

            case 'DELETE':
                $planningService = new PlanningService();

                if(!$uri[3]){
                    exit_with_message("No planning specified", 400);
                }
                $planningService->deletePlanning($uri[3]);
                break;

        
        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}
?>