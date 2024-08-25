<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class ActiviteRepository {
    function __construct() {

    }

    public function getAllActivite(){
        $activiteArray = selectDB("ACTIVITES", "*",-1);

        $activite = [];

        for ($i=0; $i < count($activiteArray); $i++) {

            $test = new ActiviteModels(
                $activiteArray[$i]['id_activite'],
                $activiteArray[$i]['nom_activite']
            );
            $activite[$i] = $test;
        }
        return $activite;
    }

    //-------------------------------------

    public function getActiviteById($id){
        $activite = selectDB("ACTIVITES", "*", "id_activite='".$id."'")[0];

        $activiteModel = new ActiviteModels(
            $activite['id_activite'],
            $activite['nom_activite']

        );
        return $activiteModel;
    }

    //-------------------------------------

    public function createActivite(ActiviteModels $activite){

        $string = "nom_activite='" . $activite->nom_activite ."'";

        $Select = selectDB("ACTIVITES", "*", $string, "bool");

        if($Select){
            exit_with_message("Y'a déjà une même activité", 403);
        }

        $create = insertDB("ACTIVITES", ["nom_activite"] ,[$activite->nom_activite]);

        if(!$create){
            exit_with_message("Error, the activite can't be created, plz try again", 500);
        }

        exit_with_message("Activity muy created", 200);
    }

    //-------------------------------------

    public function updateActivite(ActiviteModels $activite) {
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

        return $activite;
    }


    //-------------------------------------

    public function deleteActivite($id){
        $deleted = deleteDB("ACTIVITES", "id_activite=".$id ,"bool");

        if(!$deleted){
            exit_with_message("Error, the activite can't be deleted, plz try again", 500);
        }
        exit_with_message("Activity deleted", 200);
    }
}
?>