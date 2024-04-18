<?php
include_once './Service/entrepotService.php';
include_once './Models/entrepotModel.php';
include_once './exceptions.php';



function userController($uri, $apiKey) {
    
    switch ($_SERVER['REQUEST_METHOD']) {

        // Get an entrepot
        case 'GET':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            //$userService = new UserService($uri);
            $role = getRoleFromApiKey($apiKey);

            
            break;


        

        // Create an entrepot
        case 'POST':
            $userService = new UserService($uri);

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            

            break;

        
        // Update the entrepot
        case 'PUT':

            

            break;

        // Delete an entrepot
        case 'DELETE':
            

        default:
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>