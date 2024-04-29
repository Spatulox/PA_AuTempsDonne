<?php
include_once './Service/loginService.php';
include_once './exceptions.php';



function loginController($uri) {
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':

            $loginClass = new LoginService($uri);
            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if (!isset($json['email']) || !isset($json['mdp']))
            {
                exit_with_message("Plz give the email and the password of the user");
            }

            exit_with_content($loginClass->getApiKey($json['email'], $json['mdp']));
            
            break;


        
        case 'GET':
         	exit_with_message('you don t have the right to make a POST request', 500);
            break;


        
        case 'PUT':
        	exit_with_message('You don t have the right to make a PUT request', 500);
            break;


        case 'DELETE':
            exit_with_message('You don t have the right to make a DELETE request', 500);
            break;

        default:
            // Gestion des requêtes OPTIONS pour le CORS
            header("HTTP/1.1 200 OK");
            exit();
    }
}

?>