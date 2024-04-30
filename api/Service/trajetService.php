<?php

include_once './Repository/trajetRepository.php';

class TrajetService {

    /*
     *  Récupère tous les Activite
    */
    public function getAllTrajet() {
        $TrajetRepository = new TrajetRepository();
        return $TrajetRepository->getAllTrajet();
    }
}

?>