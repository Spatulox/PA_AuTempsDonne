<?php
include_once './Repository/planningRepository.php'; 
include_once './Models/planningModel.php';

class PlanningService {
    function getUserRoleFromApiKey($apiKey)
    {
        $sql =selectDB("UTILISATEUR", "id_role", "apikey='".$apiKey."'");
        return $sql[0];
    }


    /*
     *  Récupère tous les planning
    */
    public function getAllPlanning($apiKey) {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->getAllPlanning();
    }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }

    }

    /*
     *  Récupère un planning par son id
    */
    public function getPlanningByUser($apiKey) {


        $planningRepository = new PlanningRepository();
        return $planningRepository->getPlanningByUser($apiKey);
    }

    /*
     *  Créer un planning
    */
    public function createPlanning(PlanningModel $planning,$apiKey) {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==4) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->createPlanning($planning);
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }
    }

    /*
     *  Met à jour un planning
    */
    public function updatePlanning( $id_planning, $description, $date_activite, $id_index_planning, $id_activite) {
        $planningRepository = new PlanningRepository();
        $updatedPlanning = new PlanningModel(
            $id_planning,
            $description,
            $date_activite,
            $id_index_planning,
            $id_activite
        );
        $updatedPlanning->setId($id_planning);
        return $planningRepository->updatePlanning($updatedPlanning);
    }

    /*
     *  Supprimer un planning
    */
    public function deletePlanning($id) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->deletePlanning($id);
    }

    /*
     *  Rejoindre une activité
    */
    public function joinActivity($userId, $planningId, $confirme, $apiKey) {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->joinActivity($userId, $planningId,$confirme);
        }
    }

}
?>