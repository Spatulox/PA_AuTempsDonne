<?php
include_once './Repository/entrepotRepository.php'; 
include_once './Models/entrepotModel.php';

class EntrepotService {


    /*
     *  Récupère tous les entrepots
    */
    public function getAllEntrepot() {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->getEntrepots();
    }

    /*
     *  Récupère un entrepot par son id
    */

    public function getEntrepotById($id) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->getEntrepot($id);
    }

    /*
     *  Créer un entrepot
    */

    public function createEntrepot($apiKey,$entrepot,$etageres) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->createEntrepot($entrepot,$etageres);
    }

    
    /*
     *  Met à jour un entrepot
    */

    
    public function updateEntrepot($id_entrepot, $nom, $localisation) {
        $entrepotRepository = new EntrepotRepository();
        $enterpot = new EntrepotModel($id_entrepot, $nom, $localisation);
        $entrepotRepository->updateEntrepot($enterpot);
    }


    /*
     *  Supprime un entrepot
    */

    
    public function deleteEntrepotById($id) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->unreferenceEntrepotById($id);
    }

    public function createEtageres($apiKey, $entrepot, $etageres_place)
    {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->createEtageres($entrepot,$etageres_place);
    }

    public function deleteEtageretById($id)
    {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->DeleteEtagere($id);
    }


}
?>
