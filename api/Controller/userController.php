<?php
include_once './Service/fichierService.php';
include_once './Service/userService.php';
include_once './Models/userModel.php';
include_once './exceptions.php';
include_once('./returnFunctions.php');



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
            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                $userService = new UserService();
                exit_with_content($userService->getUserById($uri[3],$apiKey));
            }

            // Take all the users if it's an admin/modo
            elseif($uri[3] == "all" && $role < 3){
                $userService = new UserService();
                exit_with_content($userService->getAllUsers($apiKey));
            }

            // Take all the users if it's an admin/modo
            elseif($uri[3] == "email" && filter_var($uri[4], FILTER_VALIDATE_EMAIL )){
                $userService = new UserService();
                exit_with_content($userService->getUserByEmail($uri[4]));
            }

            elseif($uri[3] == "id" && filter_var($uri[4], FILTER_VALIDATE_INT )){
                $userService = new UserService();
                $email = getEmailFromIdUser($uri[4]);
                exit_with_content(["email" => $email]);
            }

            elseif($uri[3] == "validate" && $role < 3){
                $userService = new UserService();
                exit_with_content($userService->getAllWaitingUsers($apiKey));
            }
            elseif ($uri[3] == "dispoall") {
                $userService = new UserService();
                exit_with_content($userService->getAllDispoUsers($apiKey));
            }elseif ($uri[3] == "dispome") {
                $userService = new UserService();
                exit_with_content($userService->getDispoUserMe($apiKey));
            }elseif ($uri[3] == "dispo" && filter_var($uri[4], FILTER_VALIDATE_INT)) {
                $userService = new UserService();
                exit_with_content($userService->getDispoUserById($apiKey,$uri[4]));
            }
            else{
                exit_with_message("You need to be admin to see all the users", 403);
            }
            
            break;


        

        // Create the user
        case 'POST':
         	$userService = new UserService($uri);

            $json = null;

            if(!isset($_POST["data"])){
                exit_with_message("You need to send the data to the formData with the keyname 'data'.");
            }
            $json = json_decode($_POST["data"], true);

            $uploadFile = "";
            // File uploaded with success to the server
            if (isset($_FILES['file'])) {
                $file = $_FILES['file'];

                // Vérifiez si le fichier a été téléchargé sans erreur
                if ($file['error'] === UPLOAD_ERR_OK) {
                    // Déplacez le fichier téléchargé vers un répertoire spécifique
                    $uploadDir = 'files/permis/';

                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            exit_with_message("Impossible de créer le répertoire de destination");
                        }
                    }

                    $extension = explode(".", basename($file['name']));
                    $extension = ".".end($extension);

                    // Rename the file
                    $name = hash('sha256', $json["nom"] . $json["prenom"] . $json["password"] . $json["email"]);
                    $uploadFile = $uploadDir .  "permis_".$name.$extension;

                    $counter = 1;
                    while (file_exists($uploadFile)) {
                        $uploadFile = $uploadDir .  "permis_".$name . "_" . $counter . $extension;
                        $counter++;
                    }

                    if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
                        exit_with_message("Erreur lors de l'enregistrement du fichier check if the folder 'api/files/permis/' exist on your computer / server");
                    }
                } else {
                    exit_with_message("Erreur lors de l'upload du fichier");
                }
            }


            if (!$uri[3]){
                if ( !isset($json['nom']) || !isset($json['prenom']) || !isset($json['email']) || !isset($json['mdp']) || !isset($json['role']) || !isset($json['address']))
                {
                    exit_with_message("Plz give the name, the lastname the password, the email, the role and the address of the futur user. You can add extra(s) = [telephone]", 403);
                }

                if(isset($json["role"]) && filter_var($json["role"], FILTER_VALIDATE_INT) == false){
                    exit_with_message("The role need to be an integer between 1 and 3", 403);
                }

                if($json['role'] < 3){
                    exit_with_message("You can't register you as an Admin or modo...", 403);
                }

                $pattern = '/^\+?[0-9]{5,15}$/';
                if (isset($json['telephone']) && $json['telephone'] != "" && !preg_match($pattern, $json['telephone'])){
                    exit_with_message("Le numéro de téléphone" . $json['telephone'] ." n'est pas valide.", 403);
                }



                $user = new UserModel(1, $json['nom'], $json['prenom'], null, $json['email'], -1 ,isset($json['telephone']) ? $json['telephone'] : "no_phone", $json['role'], null, 3, 1, -1, -1, -1);
                if (isset($_FILES['file'])){
                    exit_with_content($userService->createUser($user, $json["mdp"], $json["address"], $uploadFile));
                }
                exit_with_content($userService->createUser($user, $json["mdp"], $json["address"], null));

            }
            elseif ($uri[3] == "dispo") {

                if(!isset($json["id_dispo"]) ){
                    exit_with_message("", 403);
                }

                exit_with_content($userService->createdispoUser($json["id_dispo"],$apiKey));
            }
            elseif ($uri[3] == "date") {

                if(!isset($json["id_jour"]) || !isset($json["date"])){
                    exit_with_message("", 403);
                }

                if(isset($json["role"]) && $json["role"] != null){
                    exit_with_content($userService->GetAllRoleUserDate($apiKey, $json["id_jour"], $json["role"]));
                }

                exit_with_content($userService->GetAllUserDate($apiKey, $json["id_jour"], $json["date"]));
            }

            break;

        
        // update the user
        case 'PUT':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $userService = new UserService();

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $email = getEmailFromApiKey($apiKey);
            $role = getRoleFromApiKey($apiKey);



            if(isset($uri[3]) && $uri[3] == "validate"){

                if($email != $json['email'] && $role > 2){
                    exit_with_message("Vous ne pouvez pas update un utilisateur qui n'est pas vous", 403);
                }
                if($role > 3){
                    exit_with_message("Vous n'avez pas les permissions requises", 403);
                }

                $userService->updateUserValidate($json["id_user"], $json["id_index"]);
            }elseif (!$uri[3]) {

                if (!isset($json["nom"]) || !isset($json["prenom"]) || !isset($json["telephone"]) || !isset($json["email"])) {
                    exit_with_message("Plz give the firstname, lastname, the phone and the email");
                }

                // Valider les données reçues ici
                $userService->updateUser($apiKey, ["nom" => $json["nom"], "prenom" => $json["prenom"], "telephone" => $json["telephone"], "email" => $json["email"]]);
            }
            elseif ($uri[3] && $uri[3] == "dispo"){
                if(!isset($json["id_dispo"]) ) {
                    exit_with_message("Erreur il manque vos jour de disponibilité", 403);
                }

                exit_with_content( $userService->updatedispoUser($apiKey, ($json["id_dispo"])));
        }
            elseif ($uri[3] && $uri[3] == "entrepot"){
                if(!isset($json["id_entrepot"]) ) {
                    exit_with_message("Erreur il manque votre entrepot attribué ", 403);
                }

                exit_with_content( $userService->updateentrepotUser($apiKey, ($json["id_entrepot"])));
            }
            elseif ($uri[3] && $uri[3] == "role" && filter_var($uri[4], FILTER_VALIDATE_INT)){
                if(!isset($json["id_role"]) ) {
                    exit_with_message("Erreur il manque le changement de role ", 403);
                }
                if(isset($json["role"]) && filter_var($json["role"], FILTER_VALIDATE_INT) == false){
                    exit_with_message("The role need to be an integer between 1 and 5", 403);
                }

                exit_with_content( $userService->updateRoleUser($apiKey, ($json["id_role"]),$uri[4]));
            }
            break;

        case 'DELETE':

            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            // Gestion des requêtes DELETE pour supprimer un utilisateur
            $userService = new UserService();
            $role = getRoleFromApiKey($apiKey);

            // If admin and no id specified
            if (!isset($uri[3]) && $role == 1) {
                exit_with_message("No user specified or, you can't unreferenced you as an admin", 403);
            }

            $userId = getIdUserFromApiKey($apiKey);
            
            //If normal user and id specified
            if($uri[3] != $userId && $role != 1){
                exit_with_message("You can't delete a user which is not you :/", 403);
            }

            if(isset($uri[3])){
                $userService->deleteUserById($uri[3], $apiKey);
            }
            else{
                $userService->deleteUserByApikey($apiKey);
            }

            break;

        default:
            // Gestion des requêtes OPTIONS pour le CORS
            exit_with_message("Not Found", 404); 
            exit();
    }
}

?>