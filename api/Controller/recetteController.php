<?php

include_once './Service/recetteService.php';
include_once './Models/recetteModel.php';
include_once './exceptions.php';


function recetteController($uri, $apikey)
{

    $service = new RecetteService();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if($uri[3] == "all"){
                $service->getAllRecette($apikey);
            }
            if(filter_var($uri[3], FILTER_VALIDATE_INT)){
                $service->getRecetteByid($apikey,$uri[3]);
            }

            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $service = new RecetteService();
            if (!$uri[3]) {

                if(!isset($json["nom_recette"]) || !isset($json["description_recette"]) ){
                    exit_with_message("vous n'avez pas mis tout ce qui faut pour la recette");
                }

                if (empty($json['ingredients'])) {
                    exit_with_message("La liste des ingredients ne peut pas être vide",500);
                }

                foreach ($json['ingredients'] as $ingredient) {
                    if (!isset($ingredient['id_ingredient']) || !isset($ingredient['quantite_ingredient']) || !isset($ingredient['unit_mesure_ingredient'])) {
                        exit_with_message("Les champs ne sont pas tous completer ",500);
                    }
                }

                $recette = array(
                    'nom_recette' => $json['nom_recette'],
                    'description_recette' => $json['description_recette'],
                );

                $ingredients = $json['ingredients'];

                foreach ($ingredients as $ingredient) {
                    $id_ingredient = $ingredient['id_ingredient'];
                    $quantite_ingredient = $ingredient['quantite_ingredient'];
                    $unit_mesure_ingredient = $ingredient['unit_mesure_ingredient'];
                }
                $service->createRecette($recette,$ingredients);

            }

            if ($uri[3] == "search"){

                if (empty($json['ingredients'])) {
                    exit_with_message("La liste des ingredients ne peut pas être vide",500);
                }

                foreach ($json['ingredients'] as $ingredient) {
                    if (!isset($ingredient['id_ingredient']) || !isset($ingredient['quantite_ingredient']) || !isset($ingredient['unit_mesure_ingredient'])) {
                        exit_with_message("Les champs ne sont pas tous completer ",500);
                    }
                }

                $ingredients = $json['ingredients'];

                foreach ($ingredients as $ingredient) {
                    $id_ingredient = $ingredient['id_ingredient'];
                    $quantite_ingredient = $ingredient['quantite_ingredient'];
                    $unit_mesure_ingredient = $ingredient['unit_mesure_ingredient'];
                }

                $service->SearchRecette($ingredients);

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