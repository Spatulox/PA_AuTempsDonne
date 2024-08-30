<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class ActiviteRepository {
    function __construct() {

    }

    public function getAllActivite($apiKey){
        $activiteArray = selectDB("ACTIVITES", "*",-1);

        $activite = [];

        for ($i=0; $i < count($activiteArray); $i++) {

            $test = new ActiviteModels(
                $activiteArray[$i]['id_activite'],
                $activiteArray[$i]['nom_activite']
            );
            $activite[$i] = $test;
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Récupération de la liste des activités ";

        $id_secteur = 7;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $activite;
    }

    //-------------------------------------

    public function getActiviteById($id,$apiKey){
        $activite = selectDB("ACTIVITES", "*", "id_activite='".$id."'")[0];

        $activiteModel = new ActiviteModels(
            $activite['id_activite'],
            $activite['nom_activite']

        );

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Consultation de l'activité ID " . $id .".";
        $id_secteur = 7;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $activiteModel;
    }

    //-------------------------------------

    public function createActivite(ActiviteModels $activite,$apiKey){

        $string = "nom_activite='" . $activite->nom_activite ."'";

        $Select = selectDB("ACTIVITES", "*", $string, "bool");

        if($Select){
            exit_with_message("Y'a déjà une même activité", 403);
        }

        $create = insertDB("ACTIVITES", ["nom_activite"] ,[$activite->nom_activite]);

        if(!$create){
            exit_with_message("Error, the activite can't be created, plz try again", 500);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Création d'une nouvelle activité : '" . $activite->nom_activite .".";
        $id_secteur = 7;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Activity muy created", 200);
    }

    //-------------------------------------

    public function updateActivite(ActiviteModels $activite,$apiKey) {
        $activiteRepository = new ActiviteRepository();
        $updated = $activiteRepository->updateDB(
            "ACTIVITES",
            ["id_activite", "nom_activite"],
            [
                $activite->id_activite,
                $activite->nom_activite

            ],
            "id_activite=" . $activite->id_activite
        );

        if (!$updated) {
            exit_with_message("Erreur, l'activité n'a pas pu être mis à jour. Veuillez réessayer.", 500);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "mise a jour d'une activité .";
        $id_secteur = 7;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $activite;
    }


    //-------------------------------------

    public function deleteActivite($id,$apiKey){
        $deleted = deleteDB("ACTIVITES", "id_activite=".$id ,"bool");

        if(!$deleted){
            exit_with_message("Error, the activite can't be deleted, plz try again", 500);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "supprime une activité  .";
        $id_secteur = 7;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Activite deleted", 200);
    }
}
?>