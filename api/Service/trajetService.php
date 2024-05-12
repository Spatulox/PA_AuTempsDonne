<?php

include_once './Repository/trajetRepository.php';

class TrajetService {

    public function getAllTrajet() {
        $TrajetRepository = new TrajetRepository();
        return $TrajetRepository->getAllTrajet();
    }

    public function getTrajetById($id) {
        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->getTrajetById($id);
    }

    public function createTrajet($tab) {
        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->createTrajet($tab);
    }

    public function createTrajetInDB($tab){
        $TrajetRepository = new TrajetRepository();
        $TrajetRepository->createTrajetInDB($tab);
    }
}

?>