<?php

include_once './Repository/adresseRepository.php';
include_once './Repository/BDD.php';
include_once './exceptions.php';

class TrajetRepository {
    public function __construct() {

    }


    private function affiche($request){

        $array = [];

        $trajets = [];
        $num=0;
        foreach ($request as $item) {

            $id_trajets = $item["id_trajets"];

            if (!isset($trajets[$id_trajets])) {
                $trajets[$id_trajets] = [
                    "id_trajets" => $id_trajets,
                    "addresses" => []
                ];
            }
            //var_dump($request);

           $res= selectDB("ADRESSE","adresse","id_adresse=".$request["id_adresse"].$request[$num]["id_adresse"]);


            $addresses = [
                "id_adresse" => $item["id_adresse"],
                "addresse" => $res[0]["adresse"]
            ];


            $trajets[$id_trajets]["addresses"][] = $addresses;
            $num++;
        }

        foreach ($trajets as $trajet) {
            $array[] = new TrajetModel(
                $trajet["id_trajets"],
                $trajet["addresses"]
            );

        }
        return $array[0];
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
        exit_with_content($this->affiche($rows));
    }

    public function createTrajet(){

        $string = "INNER JOIN UTILISER U ON U.id_adresse = ADRESSE.id_adresse INNER JOIN TRAJETS T ON U.id_trajets = T.id_trajets";

        $rows = selectJoinDB("ADRESSE", "adresse",$string ,-1);

        //$googleApiKey = 'AIzaSyC9WzDphICufUy1vaD1xjwhK3cI7pWJi9c';

        //$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . urlencode($origin) . "&destinations=" . urlencode($destination) . "&key=" . $apiKey;

        $route = [2, 3, 4, 5, 9];

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

        exit_with_content($addresses);
        //return $addresses;

    }

}

?>