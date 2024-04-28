<?php

//include_once './Service/globalFunctions.php';
include_once './Repository/BDD.php';
include_once './Controller/loginController.php';
include_once './Controller/userController.php';
include_once './Controller/planningController.php';
include_once './Controller/activiteController.php';

/*include_once './Controller/apartmentController.php';
include_once './Controller/reservationController.php';
*/

// Skipper les warnings, pour la production (vos exceptions devront être gérées proprement)
error_reporting(E_ERROR | E_PARSE);

// le contenu renvoyé par le serveur sera du JSON
header("Content-Type: application/json; charset=utf8");

// Autorise les requêtes depuis localhost
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// On récupère l'URI de la requête et on le découpe en fonction des / 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri); // On obtient un tableau de la forme ['index.php', 'todos', '1']

// Si on a moins de 3 éléments dans l'URI, c'est que l'on est sur l'index de l'API
if (sizeof($uri) < 3) {
    header("HTTP/1.1 200 OK");
    echo '{"message": "Welcome to the API"}';
    exit();
}

// Ces fonctions nous permettent de centraliser la gestion des headers et du body de la réponse HTTP
function exit_with_message($message = "Internal Server Error", $code = 500) {
    http_response_code($code);
    echo '{"message": "' . $message . '"}';
    exit();
}

function exit_with_content($content = null, $code = 200) {
    http_response_code($code);
    echo json_encode($content);
    exit();
}

function getRoleFromApiKey($apiKey){
    $role = selectDB("UTILISATEUR", 'id_role', "apikey='".$apiKey."'", "bool");
    if($role){
        $role = $role[0]["id_role"];
    }
    else{
        exit_with_message("No one with this apikey", 403);
    }
    return $role;
}

function getIdUserFromApiKey($apiKey){
    $id = selectDB("UTILISATEUR", 'id_user', "apikey='".$apiKey."'", "bool");
    if($id){
        $id = $id[0]["id_user"];
    }
    else{
        exit_with_message("No one with this apikey", 403);
    }
    return $id;
}


// Composant principal du controlleur: cette fonction agit comme un routeur en redirigeant les requêtes vers le bon controlleur
function controller($uri) {
    $headers = getallheaders();
    $apiKey = $headers['apikey'];

    switch ($uri[2]) {
        case 'login':
            loginController($uri);
            break;
        case 'user':
            userController($uri, $apiKey);
            break;

        case 'planning':
            planningController($uri, $apiKey);
            break;

      break;

        default:
            // Si la ressource demandée n'existe pas, alors on renvoie une erreur 404
            header("HTTP/1.1 404 Not Found");
            echo "{\"message\": \"Not Found\"}";
            break;
    }
}

// On appelle le controlleur principal
controller($uri);

?>