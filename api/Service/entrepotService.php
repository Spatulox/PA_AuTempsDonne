<?php
include_once './Repository/entrepotRepository.php'; 
include_once './Models/entrepotModel.php';

class EntrepotService {
    
    public $uri;

    /*
     *  Récupère tous les utilisateurs
    */
    public function getAllEntrepot() {
        $entrepotRepository = new EntrepotRepository();
        return $entrepotRepository->getEntrepots();
    }

    /*
     *  Récupère un utilisateur par son id
    */

    public function getEntrepotById($id) {
        $entrepotRepository = new EntrepotRepository();
        return $entrepotRepository->getEntrepot($id, $apiKey);
    }

    /*
     *  Créer un utilisateur
    */

    public function createEntrepot(EntrepotModel $user, $apikey) {
        $entrepotRepository = new EntrepotRepository();
        return $entrepotRepository->createEntrepot($entrepot, $password);
    }

    
    /*
     *  Met à jour un utilisateur
    */

    
    public function updateEntrepot($id_entrepot, $nom, $localisation, $apikey) {
        $entrepotRepository = new EntrepotRepository();
        return $entrepotRepository->updateEntrepot($id_entrepot, $nom, $localisation);
    }


    /*
     *  Supprime un utilisateur
    */

    
    public function deleteEntrepotById($id, $apiKey) {
        $entrepotRepository = new EntrepotRepository();
        if ($entrepotRepository->unreferenceEntrepotById($id, $apiKey)){
            exit_with_message("Unreference Succeed !", 200);
        }
        else{
            exit_with_message("Error when unreferencing entrepot ".$id);
        }
    }
    
    
}
?>
