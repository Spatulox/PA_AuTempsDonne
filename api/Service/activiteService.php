<?php

include_once './Repository/activiteRepository.php';
include_once './index.php';

class activiteService {

    /*
     *  Récupère tous les Activite
    */
    public function getAllActivite($apikey) {
        $userRole = getRoleFromApiKey($apikey);
        if ($userRole==1 || $userRole==2 || $userRole==4 || $userRole==5) {
            $ActiviteRepository = new ActiviteRepository();
            return $ActiviteRepository->getAllActivite();
        }else{
            exit_with_message("Vous n'avez pas accès a cette commande");
        }
    }

    /*
     *  Récupère un Activite par son id
    */
    public function getActiviteById($id,$apikey) {
        $userRole = getRoleFromApiKey($apikey);
        $ActiviteRepository = new ActiviteRepository();
        return $ActiviteRepository->getActiviteById($id);
    }

    /*
     *  Créer un Activite
    */
    public function createActivite(ActiviteModels $Activite, $apikey) {
        $userRole = getRoleFromApiKey($apikey);
        if ($userRole==1 || $userRole==2 || $userRole==4) {
            $ActiviteRepository = new ActiviteRepository();
            return $ActiviteRepository->createActivite($Activite);
        }else{
            exit_with_message("Vous n'avez pas accès a cette commande");
        }
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
    public function deleteActivite($id,$apikey) {
        $userRole = getRoleFromApiKey($apikey);
        if ($userRole==1 || $userRole==2) {
            $ActiviteRepository = new ActiviteRepository();
            return $ActiviteRepository->deleteActivite($id);
        }else{
            exit_with_message("Vous n'avez pas accès a cette commande");
        }
    }
}
?>