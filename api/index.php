<?php

//include_once './Service/globalFunctions.php';
include_once './Repository/BDD.php';
include_once './Controller/loginController.php';
include_once './Controller/userController.php';
include_once './Controller/entrepotController.php';
include_once './Controller/planningController.php';
include_once './Controller/activiteController.php';
include_once './Controller/trajetController.php';
include_once './Controller/adresseController.php';
include_once './Controller/produitController.php';
include_once './Controller/demandeController.php';
include_once './Controller/stockController.php';
include_once './Controller/vehiculeController.php';
include_once './Controller/donController.php';
include_once './Controller/etagereController.php';
include_once './Controller/stripeController.php';
include_once './Controller/mailController.php';


// Skipper les warnings, pour la production (vos exceptions devront être gérées proprement)
error_reporting(E_ERROR | E_PARSE);

// le contenu renvoyé par le serveur sera du JSON
header("Content-Type: application/json; charset=utf8");

// Autorise les requêtes depuis local host
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, apikey, Accept');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


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
    if($apiKey == null){
        exit_with_message("The apikey is empty", 403);
    }

    $role = selectDB("UTILISATEUR", 'id_role', "apikey='".$apiKey."'", "bool");
    if($role){
        $role = $role[0]["id_role"];
    }
    else{
        exit_with_message("No one with this apikey", 403);
    }
    return $role;
}

function getEmailFromApiKey($apiKey){
    if($apiKey == null){
        exit_with_message("The apikey is empty", 403);
    }

    $id = selectDB("UTILISATEUR", 'email', "apikey='".$apiKey."'", "bool");
    if($id){
        $id = $id[0]["email"];
        return $id;
    }
    return false;
}

function getIdUSerFromEmail($email){

    $id = selectDB("UTILISATEUR", 'id_user', "email='".$email."'", "bool");
    if($id){
        $id = $id[0]["id_user"];
    }
    else{
        exit_with_message("No one with this email", 403);
    }
    return $id;
}

function getEmailFromIdUser($id){

    $email = selectDB("UTILISATEUR", 'email', "id_user='".$id."'", "bool");
    if($email){
        $email = $email[0]["email"];
    }
    else{
        exit_with_message("No one with this id", 404);
    }
    return $email;

}

function getIdUserFromApiKey($apiKey){
    if($apiKey == null){
        exit_with_message("The apikey is empty", 403);
    }

    $id = selectDB("UTILISATEUR", 'id_user', "apikey='".$apiKey."'", "bool");
    if($id){
        $id = $id[0]["id_user"];
    }

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

    // Check if the apikey exist
    // To create a user, the apikey always null
    if($uri[2] != "login" && $uri[2] != "user" && $uri[2] != "etagere" && $uri[2] != "don" && $uri[2] != "entrepot" && $uri[3] != "qr"){
        if($apiKey == null){
            exit_with_message("Unauthorized, need the apikey", 403);
        }
        $role = getRoleFromApiKey($apiKey);
        if(!$role){
            exit_with_message("Wrong APIKEY");
        }
    }

    switch ($uri[2]) {
        case 'login':
            loginController($uri);
            break;
        case 'user':
            userController($uri, $apiKey);
            break;
        case 'entrepot':
            entrepotController($uri, $apiKey);
            break;
        case 'planning':
            planningController($uri, $apiKey);
            break;
        case 'activite':
            activiteController($uri, $apiKey);
            break;
        case 'trajet':
            trajetController($uri, $apiKey);
            break;
        case 'adresse':
            adresseController($uri, $apiKey);
            break;
        case 'produit':
            collectController($uri, $apiKey);
            break;
        case 'demande':
            demandeController($uri, $apiKey);
            break;
        case 'stock':
            StockController($uri, $apiKey);
            break;
        case 'vehicule':
            vehiculeController($uri, $apiKey);
            break;
        case 'don':
            donController($uri, $apiKey);
            break;
        case 'etagere':
            etagereController($uri);
            break;
        case 'stripe':
            stripeController($uri, $apiKey);
            break;

        case 'mail':
            mailController($uri, $apiKey);
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