<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class adresseRepository {
    public function __construct() {

    }

    public function getAllAdresse(){
        $adresseArray = selectDB("ADRESSE", "*", -1);

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
        $rows = selectDB("ADRESSE", "id_adresse, adresse", "id_adresse=".$id);

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

    public function CreateAdresse($address)
    {
        $add = selectDB("ADRESSE", "*", "adresse='".$address."'", "bool");

        if($add != false){
            exit_with_message("This address already exist");
        }

        $res=insertDB("ADRESSE", ["adresse"],[$address]);
        if (!$res) {
            exit_with_message("Erreur: creation addresse",500);
        }else{
            $add = selectDB("ADRESSE", "*", "adresse='".$address."'", "bool")[0];
            exit_with_content(new adresseModel($add["id_adresse"], $add["adresse"]));
        }

    }

    public function DeleteAdresse($id){
        $add = selectDB("ADRESSE", "*", "id_adresse=".$id, "bool");

        if(count($add) == 0 || $add === false){
            exit_with_message("L'adresse ".$id." n'existe pas");
        }
        if(deleteDB("ADRESSE", "id_adresse=".$id, "bool")){
            exit_with_message("Address successfully deleted", 200);
        }
        exit_with_message("Erreur: delete addresse");

    }
}

?>