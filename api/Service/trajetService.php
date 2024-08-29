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

    public function createTrajetInDB($tab,$apiKey){
        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->createTrajetInDB($tab,$apiKey);
    }
}

?>