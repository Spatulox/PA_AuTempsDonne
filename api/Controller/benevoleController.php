<?php
include_once './Service/userService.php';
include_once './Models/userModel.php';

include_once './Service/benevoleService.php';
include_once './Models/benevoleModel.php';

include_once './exceptions.php';



function benevoleController($uri, $apiKey) {
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

        	//$userService = new UserService($uri);
            $role = getRoleFromApiKey($apiKey);

            if($role != 3){
                exit_with_message("Unauthorized", 403);
            }

            // If there is nothing after the /benevole
            if(!$uri[3]){
                $userService = new UserService();
                exit_with_content($userService->getUserByApikey($apiKey));
            }

            // Retrieve the planning
            elseif($uri[3] == "planning"){
                
                $benevoleService = new BenevoleService();
                exit_with_content($benevoleService->getUserPlanning($apiKey));
            }


            // Retrieve the available activities
            elseif($uri[3] == "activity"){

                if($uri[4] == "add"){
                    
                    exit_with_message("you can't add you to an activity right now, under construction", 403);
                }
                
                $benevoleService = new BenevoleService();
                exit_with_content($benevoleService->getAllActivities());
            }
            
            // Retrieve the subscribed formation
            elseif($uri[3] == "formation"){

                if($uri[4] == "add"){
                    
                    exit_with_message("you can't add you to a formation right now, under construction", 403);
                }

                $benevoleService = new BenevoleService();
                exit_with_content($benevoleService->getUserFormation($apiKey));
            }

            else{
                exit_with_message("Mmmmmh, unexpected path");
            }
            
            break;

        
        //To Delete an action in the planning of idk what
        //case 'DELETE':
           

        default:
            // Gestion des requêtes OPTIONS pour le CORS
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>