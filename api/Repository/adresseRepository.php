<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class adresseRepository {
    public function __construct() {

    }

    public function getAllAdresse(){
        $adresseArray = selectDB("ADRESSE", "*", -1,"-@");

        if(!$adresseArray){
            exit_with_message("huh1");
        }

        $adresse = [];

        for ($i=0; $i < count($adresseArray); $i++) {

            $test = new TrajetModel(
                $adresseArray[$i]['id_adresse'],
                $adresseArray[$i]['adresse']
            );
            $adresse[$i] = $test;
        }
        return $adresse;
    }

    public function getAdresseById($id){
        $rows = selectDB("ADRESSE", "id_adresse, adresse", "id_adresse=".$id, "-@");

        if (!$rows) {
            exit_with_message("huh2");
        }

        $trajet = [];

        foreach ($rows as $row) {
            $trajetModel = new adresseModel(
                $row['id_adresse'],
                $row['adresse']
            );

            $trajet[] = $trajetModel;
        }

        return $trajet;
    }
}

?>