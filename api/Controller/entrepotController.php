<?php
include_once './Service/entrepotService.php';
include_once './Models/entrepotModel.php';
include_once './exceptions.php';



function entrepotController($uri, $apiKey) {
    
    switch ($_SERVER['REQUEST_METHOD']) {

        // Get an entrepot
        case 'GET':
            
            if($uri[3]){
                $entepotService = new EntrepotService();
                $entepotService->getEntrepotById($uri[3]);
            }
            else{
                $entepotService = new EntrepotService();
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

            $entepotService = new EntrepotService();

            if ($uri[3]==="new"){
                if(!isset($json["nom_entrepot"]) || !isset($json["parking"])){
                    exit_with_message("vous n'avez pas mis le nom_entrepot,parking ");
                }

                $entrepot = array(
                    'nom_entrepot' => $json['nom_entrepot'],
                    'parking' => $json['parking'],
                    'id_adresse' => $json['id_adresse'],
                );

                $etageres = $json['etagere'];

                foreach ($etageres as $etagere) {
                    $nombre_de_place = $etagere['nombre_de_place'];
                }
                $entepotService->createEntrepot($apiKey,$entrepot,$etageres);
            }

            if ($uri[3]==="up"){
                if(!isset($json["id_entrepot"]) || !isset($json["etagere"])){
                    exit_with_message("vous n'avez pas mis le nom_entrepot,parking ");
                }

                $entrepot = $json['id_entrepot'];

                $etageres = $json['etagere'];

                $etageres_place = [];

                foreach ($etageres as $etagere) {
                    $etageres_place[] = $etagere['nombre_de_place'];
                }




                $entepotService->createEtageres($apiKey,$entrepot,$etageres_place);

            }
            break;

        
        // Update the entrepot
        case 'PUT':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(getRoleFromApiKey($apiKey) > 2){
                exit_with_message("You can't do a PUT request for entrepots", 403);
            }

            if(!isset($json["id_entrepot"])){
                exit_with_message("Missing id_entrepot", 403);
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

            if(!$uri[4]){
                exit_with_message("Missing id entrepot to delete", 403);
            }


            $entrepotService = new EntrepotService();
            $entrepotService->deleteEntrepotById($uri[4]);

            break;
            

        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>