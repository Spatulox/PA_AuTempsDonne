<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class TrajetRepository {
    public function __construct() {

    }

    public function getAllTrajet(){
        $trajetArray = selectDB("TRAJETS", "id_trajets", -1,"-@");

        $trajet = [];

        for ($i=0; $i < count($trajetArray); $i++) {

            $test = new TrajetModel(
                $trajetArray[$i]['id_trajets']
            );
            $trajet[$i] = $test;
        }
        return $trajet;
    }

    public function getTrajetById($id){
        $trajet = selectDB("TRAJETS", "*", "id_='".$id."'")[0];

        $trajetModel = new TrajetModel(
            $trajet['id_trajets']
        );


        return $trajetModel;
    }

    public function createTrajet($addressIds) {

        if (empty($addressIds) || !is_array($addressIds)) {
            throw new Exception("Entrée des id_adresse invalide.");
        }

        $allCreates = [];

        foreach ($addressIds as $addressId) {
            $create = insertDB("UTILISER", ["id_trajets", "id_adresse"], [$trajetIds, $addressId], "-@");

            if ($create) {
                $allCreates = $create;
            }

        }

        return $allCreates;
    }

    /*
    public function updateTrajet(TrajetModel $trajet) {
        $trajetRepository = new TrajetRepository();
        $updated = $trajetRepository->updateDB(
            "UTILISER",
            ["id_trajets"],
            [
                $trajet->id_trajet

            ],
            "id_trajets=" . $trajet->id_trajet
        );

        if (!$updated) {
            exit_with_message("Erreur, le activite n'a pas pu être mis à jour. Veuillez réessayer.", 500);
        }

        return $trajet;
    }
    */


}

?>