<?php
include_once './Service/loginService.php';
include_once './exceptions.php';



function loginController($uri) {
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':

            $loginClass = new LoginService($uri);
            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if (!isset($json['nom']) || !isset($json['prenom']) || !isset($json['mdp']))
            {
                exit_with_message("Plz give the name, lastname and the password of the user");
            }

            exit_with_content($loginClass->getApiKey($json['nom'], $json['prenom'], ($json['mdp'])));
            
            break;


        
        case 'POST':
         	exit_with_message('you don t have the right to make a POST request', 500);
            break;


        
        case 'PUT':
        	exit_with_message('you don t have the right to make a PUT request', 500);
            break;


        case 'DELETE':
            exit_with_message('you don t have the right to make a DELETE request', 500);
            break;

        default:
            // Gestion des requêtes OPTIONS pour le CORS
            header("HTTP/1.1 200 OK");
            exit();
    }
}

?>