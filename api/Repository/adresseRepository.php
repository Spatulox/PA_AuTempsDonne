<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class adresseRepository {
    public function __construct() {

    }

    public function getAllAdresse($apiKey){
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

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 8;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $adresse;
    }

    public function getAdresseById($id, $apiKey){
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

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 8;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $trajet;
    }

    public function CreateAdresse($address,$apiKey)
    {

        if($address === ""){
            exit_with_message("Addres can't be null");
        }

        $add = selectDB("ADRESSE", "*", "adresse='".$address."'", "bool");
        if($add != false){
            exit_with_message("This address already exist");
        }

        $res=insertDB("ADRESSE", ["adresse"],[$address]);
        if (!$res) {
            exit_with_message("Erreur lors de la création de l'adresse");
        }else{
            $add = selectDB("ADRESSE", "*", "adresse='".$address."'", "bool")[0];

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit not deleted .";
            $id_secteur = 8;
            $id_user = ($apiKey != null ? getIdUserFromApiKey($apiKey) : 1);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            return new adresseModel($add["id_adresse"], $add["adresse"]);
        }

    }

    public function DeleteAdresse($id,$apiKey){
        $add = selectDB("ADRESSE", "*", "id_adresse=".$id, "bool");

        if(count($add) == 0 || $add === false){
            exit_with_message("L'adresse ".$id." n'existe pas");
        }
        if(deleteDB("ADRESSE", "id_adresse=".$id, "bool")){
            exit_with_message("Address successfully deleted", 200);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 8;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Erreur: delete addresse");

    }
}

?>