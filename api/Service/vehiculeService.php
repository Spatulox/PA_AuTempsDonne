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
            $vehiculeRepository->getAllVehicule();
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    public function getVehiculeById($int)
    {
        $vehiculeRepository = new VehiculeRepository();
        $vehiculeRepository->getVehiculeById($int);
    }

    public function getMyVehicule($apikey){
        $userRole = getRoleFromApiKey($apikey);
        $id_user = getIdUserFromApiKey($apikey);
        if($userRole[0] == 3){
            $vehiculeRepository = new VehiculeRepository();
            $vehiculeRepository->getMyVehicules($id_user);
        } else {
            exit_with_message("You didn't have access to this command");
        }
    }

    public function getAvailableVehicule($apikey, $debut, $fin){

        $debut = explode(".", implode(" ", explode("T", $debut)))[0];
        $fin = explode(".", implode(" ", explode("T", $fin)))[0];

        $vehiculeRepository = new VehiculeRepository();
        $vehiculeRepository->getVehiculeAvailable($debut, $fin);

        // Get all available vehicle
        /*$userRole = getRoleFromApiKey($apikey);
        if($userRole[0] <= 2 || $userRole[0] == 4){
            $vehiculeRepository = new VehiculeRepository();
            $vehiculeRepository->getVehiculeAvailable($debut, $fin);
        } elseif($userRole[0] == 3){
            $id_user = getIdUserFromApiKey($apikey);
            $vehiculeRepository = new VehiculeRepository();
            $vehiculeRepository->getMyVehiculeAvailable($debut, $fin, $id_user);
        } else {
            exit_with_message("You didn't have access to this command");
        }*/
    }

    public function createVehicule(VehiculeModel $vehicule, $apiKey)
    {
        if(!isValidLicensePlate($vehicule->immatriculation)){
            exit_with_message("License Plate invalide, plz use the 'AB-321-BC' pattern");
        }

        $userRole = getRoleFromApiKey($apiKey);
        $id_user = getIdUserFromApiKey($apiKey);
        if ($userRole[0] <= 3) {
            $vehiculeRepository = new VehiculeRepository();
            $vehiculeRepository->createVehicule($vehicule, $id_user);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    public function deleteVehicule($int, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0] <= 2) {
            $vehiculeRepository = new VehiculeRepository();
            $vehiculeRepository->deleteVehicule($int);
        } elseif($userRole[0] == 3) {
            // Verifier si c'est bien son vehicule
            $vehiculeRepository = new VehiculeRepository();
            $vehiculeRepository->deleteVehicule($int);
        }
        else{
            exit_with_message("You didn't have access to this command");
        }
    }

    // Gett all booked vehicle, even if the booked is over
    public function getBookedVehicle($apiKey, $addtionnalData = null){
        $role = getRoleFromApiKey($apiKey);

        $vehiculeRepository = new VehiculeRepository();

        if($role <= 2){

            $vehiculeRepository->getAllBookedVehicle();

        } elseif($role == 3 && $addtionnalData == null){
            $vehiculeRepository->getMyVehicleWichAreBooked($apiKey);
            // List all my vehicle which are booked
            exit();

        } elseif (($role == 3 && $addtionnalData != null) || $role == 4) {
            // List my current booked vehicle
            $vehiculeRepository->getMyBookedVehicle($apiKey);
            exit();
        }
    }

    public function bookingVehicle($apiKey, $id_vehicule, $date_start_init, $date_end_init) {

        $date_start = new DateTime($date_start_init);
        $date_end = new DateTime($date_end_init);

        if ($date_end <= $date_start) {
            exit_with_message("The end date cannot be before the start date");
        }

        $today = new DateTime('today');
        if ($date_start->format('Y-m-d') == $today->format('Y-m-d')) {
            exit_with_message("You can't book a vehicle for today");
        }


        $deltaDate = $date_end->diff($date_start);
        if ($deltaDate->h < 1 && $deltaDate->days == 0) {
            exit_with_message("You need to book the vehicle for at least 1 hour");
        }


        $userRole = getRoleFromApiKey($apiKey);

        // In the model, the id_service is the id_vehicule, to avoid a lot of param in the next function
        $service = new ServiceModel($id_vehicule, "Partage de vehicule", 1, $date_start_init, $date_end_init);
        $vehiculeRepository = new VehiculeRepository();
        $vehiculeRepository->bookingAVehicle($apiKey, $service);


    }


    public function unBookingVehicle($id_service, $apiKey){
        $vehiculeRepository = new VehiculeRepository();
        $vehiculeRepository->unBookingAVehicle($id_service, $apiKey);
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