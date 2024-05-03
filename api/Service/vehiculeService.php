<?php
include_once './Repository/vehiculeRepository.php';
include_once './Models/vehiculeModel.php';
include_once './index.php';

class VehiculeService
{

    public function getAllVehicule($apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->getAllVehicule();
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }
    }

    public function getVehiculeById($int,$apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->getVehiculeById($int);
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }
    }
}