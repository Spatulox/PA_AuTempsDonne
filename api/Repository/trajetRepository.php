<?php

include_once './Repository/adresseRepository.php';
include_once './Repository/BDD.php';
include_once './exceptions.php';

class TrajetRepository {
    public function __construct() {

    }

    private function getLastInsertId($table, $columnToSelect)
    {
        $string = "ORDER BY " . $columnToSelect . " DESC LIMIT 1";
        $envoie = selectDB($table, $columnToSelect, -1, $string);
        return $envoie;
    }


    private function affiche($request, $id_vehicle = null){

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
            $res= selectDB("ADRESSE","adresse","id_adresse=".$request["id_adresse"].$request[$num]["id_adresse"]);




            if($item["id_adresse"] == "1"){
                $addresses = [
                    "id_adresse" => $request[(count($item) + 1)]["id_adresse"]."",
                    "addresse" => selectDB("ADRESSE","adresse","id_adresse=".$request[(count($item) + 1)]["id_adresse"])[0]['adresse']
                ];
            } else {
                $addresses = [
                    "id_adresse" => $item["id_adresse"],
                    "addresse" => $res[0]["adresse"]
                ];
            }

            $trajets[$id_trajets]["addresses"][] = $addresses;

            $trajets[$id_trajets]["vehicule"] = "";

            if($id_vehicle != null) {
                $vehicle = selectDB("VEHICULES", "*", "id_vehicule=".$id_vehicle);
                if($vehicle){
                    $vehicleRepo = new VehiculeRepository();
                    $data = $vehicleRepo->returnVehicleForm($vehicle, false);
                    $trajets[$id_trajets]["vehicule"] = $data[0];
                }
            }


            $num++;
        }

        foreach ($trajets as $trajet) {
            $array[] = new TrajetModel(
                $trajet["id_trajets"],
                $trajet["addresses"],
                $trajet["vehicule"]
            );

        }
        return $array[0];
    }



    public function getAllTrajet($apiKey){
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

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 3;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $trajet;
    }

    //-------------------------------------------------------------------------------

    public function getTrajetById($id,$apiKey){
        $rows = selectDB("UTILISER", "id_trajets, id_adresse", "id_trajets=".$id);
        $vehicle = selectJoinDB("CONDUIT c", "v.id_vehicule","LEFT JOIN VEHICULES v ON c.id_vehicule = v.id_vehicule", "-1");

        if($vehicle){
            $vehicle = $vehicle[0]["id_vehicule"];
        } else {
            $vehicle = null;
        }

        if (!$rows) {
            exit_with_message("huh2");
        }

        exit_with_content($this->affiche($rows, $vehicle));
    }

    public function createTrajet($route,$apiKey){

        $string = "INNER JOIN UTILISER U ON U.id_adresse = ADRESSE.id_adresse INNER JOIN TRAJETS T ON U.id_trajets = T.id_trajets";

        $rows = selectJoinDB("ADRESSE", "adresse",$string ,-1);

        foreach ($route as $id) {
            $repo = new adresseRepository();
            $address = $repo->getAdresseById($id, $apiKey)[0];

            if ($address) {
                $addresses[] = $address->adresse;
            }
        }

        $origin = reset($addresses);
        $end = end($addresses);
        $data = [
            "addresse" => $addresses
        ];

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 3;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_content($data);
    }

    public function createTrajetInDB($tab,$apiKey){
        $lastID = $this->getLastInsertId("TRAJETS", "id_trajets");

        if(count($lastID) > 0){
            $lastID = $lastID[0]["id_trajets"];
        } else {
            exit_with_message("huh1");
        }

        $lastID = $lastID + 1;

        $test = insertDB("TRAJETS",["id_trajets"],[$lastID]);

        if($test === false){
            exit_with_message("Erreur lors de la création du trajet");
        }

        $tab1 = $tab[0];
        $count = 0;
        foreach ($tab as $trajet) {

            if($trajet == $tab1 && $count > 0){
                $trajet = 1;
            }

            $count++;

            $res = insertDB("UTILISER", ["id_trajets", "id_adresse"], [$lastID, $trajet], "bool");

            if( $res === false){
                $msg = "Erreur lors de l'enregistement du trajet, veuillez réessayer; si vous passez deux fois au même endroit (autre que les entrepots, c'est impossible)";
                deleteDB("UTILISER", "id_trajets=".$lastID);
                exit_with_message($msg);
            }
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 3;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Success", 200);

    }

}

?>