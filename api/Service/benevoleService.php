<?php
include_once './Repository/BenevoleRepository.php'; 
//include_once './Models/userModel.php';

class BenevoleService {
    
    public $uri;

    // public function __construct($uri)
    // {       
    //     $this->uri = $uri;
    // }

    /*
     *  Récupère tous les utilisateurs
    */
    public function getUserPlanning($apikey) {
        $benevoleRepository = new BenevoleRepository();

        return $benevoleRepository->getPlanning($apikey);
    } 

    //-------------------------------------

    public function getUserFormation($apikey) {
        $benevoleRepository = new BenevoleRepository();

        return $benevoleRepository->getFormation($apikey);
    } 

    //-------------------------------------

    public function getAllActivities() {
        $benevoleRepository = new BenevoleRepository();

        return $benevoleRepository->getActivities();
    } 
    
    
}
?>
