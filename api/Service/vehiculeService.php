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
            exit_with_message("You didn't have access to this command");
        }
    }

    public function getVehiculeById($int,$apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->getVehiculeById($int);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    public function getMyVehicule($apikey){
        $userRole = getRoleFromApiKey($apikey);
        $id_user = getIdUserFromApiKey($apikey);
        if($userRole[0] == 3){
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->getMyVehicules($id_user);
        } else {
            exit_with_message("You didn't have access to this command");
        }
    }

    public function getAllAvailableVehicule($apikey, $debut, $fin){
        $userRole = getRoleFromApiKey($apikey);
        if($userRole[0] <= 2 || $userRole[0] == 4){
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->getVehiculeAvailable($debut, $fin);
        } else {
            exit_with_message("You didn't have access to this command");
        }
    }

    public function createVehicule(VehiculeModel $vehicule, $apiKey)
    {
        if(!isValidLicensePlate($vehicule->immatriculation)){
            exit_with_message("License Plate invalide, plz use the 'AB-321-BC' pattern");
        }

        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0] <= 3) {
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->createVehicule($vehicule);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    public function deleteVehicule($int, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0] <= 2) {
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->deleteVehicule($int);
        } elseif($userRole[0] == 3) {
            // Verifier si c'est bien son vehicule
            $vehiculeRepository = new VehiculeRepository();
            return $vehiculeRepository->deleteVehicule($int);
        }
        else{
            exit_with_message("You didn't have access to this command");
        }
    }
}

function isValidLicensePlate($plate) {
    // Définir l'expression régulière pour le format de la plaque
    $regex = '/^[A-Z]{2}-\d{3}-[A-Z]{2}$/';

    // Utiliser preg_match pour vérifier si la chaîne correspond au format
    if (preg_match($regex, $plate)) {
        return true;
    } else {
        return false;
    }
}