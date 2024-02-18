<?php
include_once './Service/userService.php';
include_once './Models/userModel.php';
include_once './exceptions.php';



function userController($uri, $apiKey) {
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

        	//$userService = new UserService($uri);
            $role = getRoleFromApiKey($apiKey);

            // If a number don't exist after the /user and it's not an admin/modo
            if(!$uri[3]){
                $userService = new UserService();
                exit_with_content($userService->getUserByApikey($apiKey));
            }

            // If a number exist after the /user
            elseif($uri[3] && $uri[3]!="all"){
                $userService = new UserService();
                exit_with_content($userService->getUserById($uri[3]));
            }

            // Take all the users if it's an admin/modo
            elseif($uri[3] == "all" && $role < 3){
                $userService = new UserService();
                exit_with_content($userService->getAllUsers());
            }
            else{
                exit_with_message("You need to be admin to see all the users", 403);
            }
            
            break;


        /*

        // Create the user
        case 'POST':
         	$userService = new UserService($uri);

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if ( !isset($json['role']) || !isset($json['pseudo']) || !isset($json['password']) )
            {
                exit_with_message("Plz give the role, the pseudo and the password of the user");
            }

            // Valider les données reçues ici
            exit_with_content($userService->createUser($json["role"], $json["pseudo"], $json["password"]));

            break;


        // update the user
        case 'PUT':
        	$userService = new UserService($uri);

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);
            if (!isset($json["role"]) || !isset($json["pseudo"]) || !isset($json["user_index"])){
                exit_with_message("Plz give, at least, the role, pseudo and the user_index");
            }
            // Valider les données reçues ici
            exit_with_content($userService->updateUser($uri[3], $apiKey, $json["role"], $json["pseudo"], $json["user_index"]));
            break;


        case 'DELETE':
            // Gestion des requêtes DELETE pour supprimer un utilisateur
            $userService = new UserService($uri);
            $userService->deleteUser($uri[3], $apiKey);
            break;

        */

        default:
            // Gestion des requêtes OPTIONS pour le CORS
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>