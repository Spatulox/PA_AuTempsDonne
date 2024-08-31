<?php
class fichierRepository
{
    public function __construct()
    {
    }

    public function registerFile($id_user, $path, $name){
        $data = insertDB("FICHIER", ["nom_fichier", "chemin_fichier", "id_user"], [$name, $path, $id_user]);

        if($data){

            $debug = updateDB("UTILISATEUR", ["validate_files"], [0], "id_user='".$id_user."'", "bool");
            if(!$debug){
                // HISTORIQUE A RAJOUTER
            }

            return true;
        }
        return false;
    }

    public function getFichiers($id_user){
        // to Rework
        $data = selectDB("FICHIER", "*", "id_user=".$id_user, 'bool');
        if(!$data){
            exit_with_message("No files", 404);
        }

        $all = [];
        for ($i = 0; $i < count($data); $i++) {
            $all[$i] = returnFichier($data, $i);
        }
        
        exit_with_content($all);

    }

    function sendFile($name) {

        $data = selectDB("FICHIER", "*", "nom_fichier='".$name."'");
        if(!$data){
            exit_with_message("No files", 404);
        }
        $data = $data[0];

        $filePath = $data["chemin_fichier"] . $data["nom_fichier"];

        if (file_exists($filePath)) {
            // Définir les headers pour le téléchargement
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Lire et envoyer le fichier
            readfile($filePath);
            exit;
        } else {
            exit_with_message("File not found", 404);
        }
    }

    function removeFile($nom)
    {
        $data = selectDB("FICHIER", "*", "nom_fichier='" . $nom . "'", 'bool');
        if (!$data) {
            exit_with_message("No files", 404);
        }

        $filePath = $data[0]["chemin_fichier"] . $data[0]["nom_fichier"];
        $id_user = $data[0]["id_user"];

        if (!$filePath) {
            exit_with_message("File path not found", 400);
        }
        // Supprimer le fichier physique
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                exit_with_message("Failed to delete the file", 500);
            }
        }

        $returnMsg = deleteDB("FICHIER", "nom_fichier='" . $nom . "'", 'bool');
        if ($returnMsg) {

            $debug = updateDB("UTILISATEUR", ["validate_files"], [0], "id_user='".$id_user."'", "bool");
            if(!$debug){
                // HISTORIQUE A RAJOUTER
            }

            exit_with_message("File deleted", 200);
        }
        exit_with_message("Failed to delete the file inside the DB, you gonna have a phantom file :/", 500);
    }

}