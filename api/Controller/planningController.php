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
                exit_with_content($planningService->getAllPlanning($apiKey));
            }

            
            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($planningService->getPlanningById($uri[3]));
            }

            else{
                exit_with_message("You need to be admin to see all the planning", 403);
            }
            
            break;

        
        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}
?>