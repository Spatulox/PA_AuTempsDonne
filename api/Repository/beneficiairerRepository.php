<?php

include_once './Repository/BDD.php';
include_once './Models/ActiviteModel.php';
include_once './exceptions.php';

class BeneficiaireRepository {
    //private $connection = null;

    public function __construct() {
        // Initialiser la connexion à la base de données
        $this->connection = new BDD();
    }

    //-------------------------------------
    public function createActivity(ActiviteModel $activiteModel) {
        $activite = insertDB(
            "ACTIVITES",
            ["nom_activite", "type_activite", "date_activite", "index_activite"],
            [$activiteModel->nom_activite, $activiteModel->type_activite, $activiteModel->date_activite, 2]
        );

        if(!$activite){
            exit_with_message("Error, failed to create the activity, please try again", 403);
        }

        $activite = selectDB('ACTIVITES', '*', 'nom_activite="'.$activiteModel->nom_activite.'"');

        return new ActiviteModel(
            $activite[0]['id_activite'],
            $activite[0]['nom_activite'],
            $activite[0]['type_activite'],
            $activite[0]['date_activite'],
            $activite[0]['index_activite']);
    }
	//-------------------------------------

}
?>
