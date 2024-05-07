<?php
include_once './Service/adresseService.php';
include_once './Models/adresseModel.php';
include_once './exceptions.php';

function adresseController($uri, $apiKey){
    switch ($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $AdresseService = new adresseService();
            if(!$uri[3]){
                exit_with_content($AdresseService->getAllAdresse());
            }


            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($AdresseService->getAdresseById($uri[3]));
            }


            else{
                exit_with_message("You need to be admin to see all the trips", 403);
            }

            break;

        case 'POST':

            break;

        case 'PUT':
            break;

        case 'DELETE':
            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>