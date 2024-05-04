<?php

include_once './Repository/adresseRepository.php';
include_once './Repository/BDD.php';
include_once './exceptions.php';

class TrajetRepository {
    public function __construct() {

    }

    public function getAllTrajet(){
        $trajetArray = selectDB("TRAJETS", "id_trajets", -1,"bool");

        if(!$trajetArray){
            exit_with_message("huh1");
        }

        $trajet = [];

        for ($i=0; $i < count($trajetArray); $i++) {

            $test = new TrajetModel(
                $trajetArray[$i]['id_trajets'],
                "N/A"
            );
            $trajet[$i] = $test;
        }
        return $trajet;
    }

    public function getTrajetById($id){
        $rows = selectDB("UTILISER", "id_trajets, id_adresse", "id_trajets=".$id);

        if (!$rows) {
            exit_with_message("huh2");
        }

        $trajet = [];

        foreach ($rows as $row) {
            $trajetModel = new TrajetModel(
                $row['id_trajets'],
                $row['id_adresse']
            );

            $trajet[] = $trajetModel;
        }

        return $trajet;
    }

    public function createTrajet($route){

        $string = "INNER JOIN UTILISER U ON U.id_adresse = ADRESSE.id_adresse INNER JOIN TRAJETS T ON U.id_trajets = T.id_trajets";

        $rows = selectJoinDB("ADRESSE", "adresse",$string ,-1);

        //$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . urlencode($origin) . "&destinations=" . urlencode($destination) . "&key=" . $apiKey;

        //$nbEtape = count($route);

        foreach ($route as $id) {
            $repo = new adresseRepository();
            $address = $repo->getAdresseById($id)[0];

            if ($address) {
                $addresses[] = $address->adresse;
            }
        }

        $origin = reset($addresses);
        $end = end($addresses);
        $data = [
            "addresse" => $addresses
        ];
        exit_with_content($data);
    }

}

?>