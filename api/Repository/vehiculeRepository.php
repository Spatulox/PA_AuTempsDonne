<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class VehiculeRepository
{

    public function getAllVehicule()
    {
        $vehicule = selectDB("VEHICULES", "*", "-1");
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
            $vehiculetArray[$i] = new VehiculeModel($vehicule[$i]["id_vehicule"], $vehicule[$i]["capacite"], $vehicule[$i]["nom_du_vehicules"],
                $vehicule[$i]["nombre_de_place"],$vehicule[$i]["id_entrepot"]);
        }

        exit_with_content($vehiculetArray);
    }
}