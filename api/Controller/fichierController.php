<?php

include_once './returnFunctions.php';

function fichierController($uri, $apiKey){
    $body = file_get_contents("php://input");
    $json = json_decode($body, true);
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $id_role = getRoleFromApiKey($apiKey);

            // Admin File view
            $uri3_int = intval($uri[3]);
            if(filter_var($uri[3], FILTER_VALIDATE_INT) && $id_role <= 2){
                $fileService = new FichierService();
                $fileService->getAllFileName($uri[3]);
                exit();
            }
            // My file
            else if($uri[3] == "me"){
                $id_user = getIdUserFromApiKey($apiKey);
                $fileService = new FichierService();
                $fileService->getAllFileName($id_user);
                exit();
            } else {
                exit_with_message("Vous devez mettre '/me' ou /'[IDUTILISATEUR]' pour voir les fichiers");
            }

            break;
        case 'POST':
            $id_role = getRoleFromApiKey($apiKey);

            // Admin File view
            $uri3_int = intval($uri[3]);
            if(filter_var($uri[3], FILTER_VALIDATE_INT) && $id_role <= 2){
                if(!isset($json["name_file"]) || empty($json["name_file"])){
                    exit_with_message("You need to specifie the name of the file");
                }

                $fileService = new FichierService();
                $fileService->sendFile($json["name_file"]);
                exit();
            }
            // My File
            else if($uri[3] == "me"){
                if(!isset($json["name_file"]) || empty($json["name_file"])){
                    exit_with_message("You need to specifie the name of the file");
                }

                $fileService = new FichierService();
                $fileService->sendFile($json["name_file"]);
                exit();
            }

            else if($uri[3] == "upload"){
                $filePath = prepareCheckFile($_FILES, $_POST, $apiKey);

                $id_user = getIdUserFromApiKey($apiKey);

                if($filePath != null){
                    $fichier = new FichierService();
                    if($fichier->registerFile($filePath, $id_user)){
                        exit_with_message("File added with success", 200);
                    }
                    exit_with_message("Error when adding the file", 500);
                }
                exit();
                break;
            }
            else {
                exit_with_message("Vous devez mettre '/me' ou /'[IDUTILISATEUR]' pour voir les fichiers");
            }

            break;
        case 'PUT':
            break;
        case 'DELETE':
            $fileService = new FichierService();
            $fileService->removeFile($json["name_file"], $apiKey);
            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
            break;
    }
};



function prepareCheckFile($FILES, $post, $apikey){

    $okArray = ["permis"];

    $name = json_decode($post["data"], true)["file_type"];

    if(!in_array($name, $okArray)){
        exit_with_content(["message" => "Seulement les types suivant sont acceptés", "type" => $okArray]);
    }

    if (isset($FILES['file'])) {
        $file = $FILES['file'];

        // Vérifiez si le fichier a été téléchargé sans erreur
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Déplacez le fichier téléchargé vers un répertoire spécifique
            $uploadDir = 'files/'.$name.'/';

            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    exit_with_message("Impossible de créer le répertoire de destination");
                }
            }

            $extension = explode(".", basename($file['name']));
            $extension = ".".end($extension);

            // Rename the file
            $nameFile = $apikey;
            $uploadFile = $uploadDir .  "permis_".$nameFile.$extension;

            $counter = 1;
            while (file_exists($uploadFile)) {
                $uploadFile = $uploadDir .  "permis_".$nameFile . "_" . $counter . $extension;
                $counter++;
            }

            if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
                exit_with_message("Erreur lors de l'enregistrement du fichier check if the folder 'api/files/permis/' exist on your computer / server");
            }

            return $uploadFile;
        } else {
            exit_with_message("Erreur lors de l'upload du fichier");
        }
    }
}