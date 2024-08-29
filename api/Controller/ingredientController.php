<?php

include_once './Service/ingredientService.php';
include_once './Models/ingredientModel.php';
include_once './exceptions.php';


function ingredientController($uri, $apikey)
{

    $service = new ingredientService();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if($uri[3] == "all"){
                $service->getAllingredients($apikey);
            }

            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $service = new ingredientService();
            if (!$uri[3]) {

                if(!isset($json["nom_ingredient"]) || !isset($json["unit_mesure"]) ){
                    exit_with_message("vous n'avez pas rempli la case ingredient");
                }

                $service->createingredient($json["nom_ingredient"],$json["unit_mesure"]);

            }

            break;

        case 'PUT':

            break;

        case 'DELETE':

            break;

        default:
            header("HTTP/1.1 404 Not Found");
            echo "{\"message\": \"Not Found\"}";
            break;
    }
}

?>