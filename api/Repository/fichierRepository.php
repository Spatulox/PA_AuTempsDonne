<?php
class fichierRepository
{
    public function __construct()
    {
    }

    public function registerFile($id_user, $path, $name){
        $data = insertDB("FICHIER", ["nom_fichier", "chemin_fichier", "id_user"], [$name, $path, $id_user]);

        if($data){
            return true;
        }
        return false;
    }

    public function getFichiers($id_user){
        // to Rework
        $data = selectDB("FICHIER", "*", "id_user=".$id_user, 'bool');
        if(!$data){
            exit_with_message("No files", 400);
        }
        exit_with_content(returnFichier($data));

    }



    function sendFile($name) {

        $data = selectDB("FICHIER", "*", "nom_fichier='".$name."'", '-@');
        if(!$data){
            exit_with_message("No files", 400);
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
            http_response_code(404);
            exit_with_message("File not found", 404);
        }
    }

}