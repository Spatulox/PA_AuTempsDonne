<?php
include_once './Service/ActiviteService.php';
include_once './Models/ActiviteModel.php';
include_once './exceptions.php';

function activiteController($uri, $apiKey) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $ActiviteService = new activiteService();

           
            if(!$uri[3]){
                exit_with_content($ActiviteService->getAllActivite($apiKey));
            }

            
            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($ActiviteService->getActiviteById($uri[3],$apiKey));
            }

            else{
                exit_with_message("You need to be admin to see all the activite", 403);
            }
            
            break;

        case 'POST':
            $ActiviteService = new ActiviteService();

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);


                
                    if (!isset($json['nom_activite']) ) {
                        exit_with_message("Please provide all required fields to create a new activite", 400);
                    }

                    $activite = new ActiviteModels(
                        1,
                        $json['nom_activite']
                    );

                    $ActiviteService->createActivite($activite, $apiKey);

//                    if ($createdPlanning) {
//                        exit_with_content($createdActivite);
//                    } else {
//                        exit_with_message("Error while creating the activite.", 500);
//                    }
            
            break;




        case 'PUT':
                $ActiviteService = new ActiviteService();

                $body = file_get_contents("php://input");
                $json = json_decode($body, true);
                if (!isset($json["id_activite"])  || !isset($json["nom_activite"])){
                    exit_with_message("Plz give, at least id_activite and nom_activite");
                }
                // Valider les données reçues ici
                exit_with_content($ActiviteService->updateActivite($json["id_planning"], $json["nom_activite"] ,$apiKey));
                break;

        case 'DELETE':
                $ActiviteService = new ActiviteService();

                if(!$uri[3]){
                    exit_with_message("No activite specified", 400);
                }
                $ActiviteService->deleteActivite($uri[3],$apiKey);
                break;

        
        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}
?>