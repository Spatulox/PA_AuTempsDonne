<?php

include_once './Repository/activiteRepository.php';

class activiteService {

    /*
     *  Récupère tous les Activite
    */
    public function getAllActivite() {
        $ActiviteRepository = new ActiviteRepository();
        return $ActiviteRepository->getAllActivite();
    }

    /*
     *  Récupère un Activite par son id
    */
    public function getActiviteById($id) {
        $ActiviteRepository = new ActiviteRepository();
        return $ActiviteRepository->getActiviteById($id);
    }

    /*
     *  Créer un Activite
    */
    public function createActivite(ActiviteModels $Activite) {
        $ActiviteRepository = new ActiviteRepository();
        return $ActiviteRepository->createActivite($Activite);
    }

    /*
     *  Met à jour un Activite
    */
//    public function updateActivite( $id_planning, $nom_activite) {
//        $ActiviteRepository = new ActiviteRepository();
//        $updatedPlanning = new ActiviteModel(
//            $id_activite,
//            $nom_activite
//        );
//        $updatedPlanning->setId($id);
//        return $ActiviteRepository->updateActivite($updatedActivite);
//    }

    /*
     *  Supprimer un Activite
    */
    public function deleteActivite($id) {
        $ActiviteRepository = new ActiviteRepository();
        return $ActiviteRepository->deleteActivite($id);
    }
}
?>