<?php
include_once './Repository/planningRepository.php'; 
include_once './Models/organisationModel.php';

class PlanningService {
    
    /*
     *  Récupère tous les planning
    */
    public function getAllPlanning($apiKey) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->getAllPlanning($apiKey);
    }

    /*
     *  Récupère un planning par son id
    */
    public function getPlanningById($id) {
        $planningRepository = new PlanningRepository();
        return $planningRepository->getPlanningById($id);
    }


}
?>