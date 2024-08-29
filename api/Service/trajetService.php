<?php

include_once './Repository/trajetRepository.php';

class TrajetService {

    public function getAllTrajet($apiKey) {
        $TrajetRepository = new TrajetRepository();
        return $TrajetRepository->getAllTrajet($apiKey);
    }

    public function getTrajetById($id,$apiKey) {
        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->getTrajetById($id);
    }

    public function createTrajet($tab,$apiKey) {
        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->createTrajet($tab,$apiKey);
    }

    public function createTrajetInDB($tab, $id_vehicule,$apikey){

        $validVehicle = selectDB("VEHICULES", "*", "id_vehicule=".$id_vehicule . " AND appartenance = 1", "bool");
        if(!$validVehicle){
            exit_with_message("The vehicle doesn't exist, or isn't owned by the association");
        }

        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->createTrajetInDB($tab, $id_vehicule,$apikey);
    }
}

?>