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

}

?>