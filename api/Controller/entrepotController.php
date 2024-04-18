<?php
include_once './Service/entrepotService.php';
include_once './Models/entrepotModel.php';
include_once './exceptions.php';



function entrepotController($uri, $apiKey) {
    
    switch ($_SERVER['REQUEST_METHOD']) {

        // Get an entrepot
        case 'GET':
            
            if($uri[3]){
                $entepotService = new EntrepotService($uri);
                $entepotService->getEntrepotById($uri[3]);
            }
            else{
                $entepotService = new EntrepotService($uri);
                $entepotService->getAllEntrepot();
            }

            break;


        

        // Create an entrepot
        case 'POST':
            $entrepotService = new EntrepotService($uri);

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            

            break;

        
        // Update the entrepot
        case 'PUT':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            

            break;

        // Delete an entrepot
        case 'DELETE':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }
            

        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>