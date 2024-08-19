<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class VehiculeRepository
{

    public function getAllVehicule()
    {
        $vehicule = selectDB("VEHICULES", "*");
        if(!$vehicule){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        $vehiculetArray = [];

        for ($i=0; $i < count($vehicule) ; $i++) {
            $vehiculetArray[$i] = new VehiculeModel($vehicule[$i]["id_vehicule"], $vehicule[$i]["capacite"], $vehicule[$i]["nom_du_vehicules"],
                $vehicule[$i]["nombre_de_place"],$vehicule[$i]["id_entrepot"]);
        }

            exit_with_content($vehiculetArray);
    }
//----------------------------------------------------------------------------------
    public function getVehiculeById($int)
    {
        $vehicule = selectDB("VEHICULES", "*", "id_vehicule = ".$int);
        if(!$vehicule){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        $vehiculetArray = [];

        for ($i=0; $i < count($vehicule) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($vehicule, $i);
            //$vehiculetArray[$i] = new VehiculeModel($vehicule[$i]["id_vehicule"], $vehicule[$i]["capacite"], $vehicule[$i]["nom_du_vehicules"], $vehicule[$i]["nombre_de_place"],$vehicule[$i]["id_entrepot"]);
        }

        exit_with_content($vehiculetArray);
    }

    //----------------------------------------------------------------------------------

    public function getVehiculeAvailable($debut, $fin){
        $columns = "v.*";
        $join = "LEFT JOIN SERVICE s ON v.id_service = s.id_service";
        $condition = "s.id_service IS NULL 
                        OR NOT (
                            s.service_date_debut BETWEEN '2024-08-19 00:00:00' AND '2024-08-19 23:59:59'
                        OR s.service_date_fin BETWEEN '2024-08-19 00:00:00' AND '2024-08-19 23:59:59'
                        OR (s.service_date_debut < '2024-08-19 00:00:00' AND s.service_date_fin > '2024-08-19 23:59:59')
                        );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);

        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
            //$vehiculetArray[$i] = new VehiculeModel($vehicule[$i]["id_vehicule"], $vehicule[$i]["capacite"], $vehicule[$i]["nom_du_vehicules"], $vehicule[$i]["nombre_de_place"],$vehicule[$i]["id_entrepot"]);
        }

        exit_with_content($vehiculetArray);
    }
    //----------------------------------------------------------------------------------
    public function createVehicule(VehiculeModel $vehicule)
    {
        $string = "nom_du_vehicules='" . $vehicule->nom_du_vehicules ."'";

        $Select = selectDB("VEHICULES", "*", $string, "bool");

        if($Select){
            exit_with_message("Y'a déjà une même vehicule", 403);
        }

        $create = insertDB("VEHICULES", ["capacite","nom_du_vehicules","nombre_de_place","id_entrepot"]
            ,[$vehicule->capacite ,$vehicule->nom_du_vehicules,$vehicule->nombre_de_place,$vehicule->id_entrepot]);

        if(!$create){
            exit_with_message("Error, the vehicule can't be created, plz try again", 500);
        }

        exit_with_message("vehicule created", 200);
    }
//------------------------------------------------------------------------------------------
    public function deleteVehicule($id)
    {
        $deleted = deleteDB("VEHICULES", "id_vehicule=".$id ,"bool");

        if(!$deleted){
            exit_with_message("Error, the activite can't be deleted, plz try again", 500);
        }
        exit_with_message("Vehicule deleted", 200);
    }
}