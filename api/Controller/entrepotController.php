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

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(getRoleFromApiKey($apiKey) > 2){
                exit_with_message("You can't do a POST request for entrepots", 403);
            }


            $entrepotService = new EntrepotService($uri);

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);
            

            break;

        
        // Update the entrepot
        case 'PUT':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(getRoleFromApiKey($apiKey) > 2){
                exit_with_message("You can't do a PUT request for entrepots", 403);
            }

            if(!isset($json["nom"]) && !isset($json["localisation"])){
                exit_with_message("Missing nom & localisation of the entrepot, can't update it (need one at least)", 403);
            }

            $id_entrepot = $json["id_entrepot"];
            $nom_entrepot = $json["nom"];
            $localisation = $json["localisation"];

            $entrepotService = new EntrepotService();
            $entrepotService->updateEntrepot($id_entrepot, $nom_entrepot, $localisation);
            

            break;

        // Delete an entrepot
        case 'DELETE':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(getRoleFromApiKey($apiKey) > 2){
                exit_with_message("You can't do a DELETE request for entrepots", 403);
            }

            if(!isset($json["id_entrepot"])){
                exit_with_message("Missing id_entrepot", 403);
            }

            $entrepotService = new EntrepotService();
            $entrepotService->deleteEntrepotById($json["id_entrepot"]);

            break;
            

        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>