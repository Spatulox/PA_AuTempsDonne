<?php

include_once './Repository/trajetRepository.php';

class TrajetService {

    public function getAllTrajet() {
        $TrajetRepository = new TrajetRepository();
        return $TrajetRepository->getAllTrajet();
    }

    public function getTrajetById($id) {
        $TrajetRepository = new TrajetRepository();
        return $TrajetRepository->getTrajetById($id);
    }
}

?>