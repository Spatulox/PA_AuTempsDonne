<?php
include_once './Repository/entrepotRepository.php'; 
include_once './Models/entrepotModel.php';

class EntrepotService {
    
    public $uri;

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

    public function createEntrepot(EntrepotModel $entrepot) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->createEntrepot($entrepot);
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
    
    
}
?>
