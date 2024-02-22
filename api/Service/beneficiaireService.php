<?php

include_once './Repository/BeneficiaireRepository.php'; 
include_once './Models/ActiviteModel.php';

class BeneficiaireService {
    
    public $beneficiaireRepository;

    /*
     *  dépendance pour le repository
    */
    public function __construct() {
        $this->beneficiaireRepository = new BeneficiaireRepository();
    }


    /*
     *  cree une activité
    */
    public function createActivite(ActiviteModel $activiteModel) {
        return $this->beneficiaireRepository->createActivite($activiteModel);
    }

    /*
     * recuper toute les activités
    */
     public function getAllActivite(ActiviteModel) {
        return $this->beneficiaireRepository->getActivite();
    }

    
}

?>
