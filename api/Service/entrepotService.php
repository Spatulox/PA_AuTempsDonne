<?php
include_once './Repository/entrepotRepository.php'; 
include_once './Models/entrepotModel.php';

class EntrepotService {


    /*
     *  Récupère tous les entrepots
    */
    public function getAllEntrepot($apiKey) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->getEntrepots($apiKey);
    }

    /*
     *  Récupère un entrepot par son id
    */

    public function getEntrepotById($id,$apiKey) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->getEntrepot($id,$apiKey);
    }

    /*
     *  Créer un entrepot
    */

    public function createEntrepot($apiKey,$entrepot,$etageres) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->createEntrepot($entrepot,$etageres,$apiKey);
    }

    
    /*
     *  Met à jour un entrepot
    */

    
    public function updateEntrepot($id_entrepot, $nom, $localisation,$apiKey) {
        $entrepotRepository = new EntrepotRepository();
        $enterpot = new EntrepotModel($id_entrepot, $nom, $localisation);
        $entrepotRepository->updateEntrepot($enterpot,$apiKey);
    }


    /*
     *  Supprime un entrepot
    */

    
    public function deleteEntrepotById($id,$apiKey) {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->unreferenceEntrepotById($id,$apiKey);
    }

    //-------------------------------------------------------------------------------------------------------

    public function createEtageres($apiKey, $entrepot, $etageres_place)
    {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->createEtageres($entrepot,$etageres_place,$apiKey);
    }

    //-------------------------------------------------------------------------------------------------------

    public function deleteEtageretById($id,$apiKey)
    {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->DeleteEtagere($id,$apiKey);
    }

    //-------------------------------------------------------------------------------------------------------

    public function getEntrepotPlaceById($id,$apiKey)
    {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->getEntrepotPlaceById($id,$apiKey);
    }

    //-------------------------------------------------------------------------------------------------------

    public function getEtagereQR($id,$apiKey)
    {
        $entrepotRepository = new EntrepotRepository();
        $entrepotRepository->getEtagereQR($id,$apiKey);
    }


}
?>
