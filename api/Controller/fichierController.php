<?php

include_once './returnFunctions.php';

function fichierController($uri, $apiKey){
    $body = file_get_contents("php://input");
    $json = json_decode($body, true);
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $id_role = getRoleFromApiKey($apiKey);

            // Admin File view
            $uri3_int = intval($uri[3]);
            if(filter_var($uri[3], FILTER_VALIDATE_INT) && $id_role <= 2){
                $fileService = new FichierService();
                $fileService->getAllFileName($uri[3]);
                exit();
            }
            // My file
            else if($uri[3] == "me"){
                $id_user = getIdUserFromApiKey($apiKey);
                $fileService = new FichierService();
                $fileService->getAllFileName($id_user);
                exit();
            } else {
                exit_with_message("Vous devez mettre '/me' ou /'[IDUTILISATEUR]' pour voir les fichiers");
            }

            break;
        case 'POST':
            if(!isset($json["name_file"]) || empty($json["name_file"])){
                exit_with_message("You need to specifie the name of the file");
            }
            break;
        case 'PUT':
            break;
        case 'DELETE':
            break;
        default:
            exit_with_message("Not Found", 404);
            exit();
            break;
    }
};