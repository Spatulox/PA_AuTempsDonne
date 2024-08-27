<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class VehiculeRepository
{

    public function getAllVehicule()
    {
        $vehicule = selectDB("VEHICULES", "*");
        if(!$vehicule){
            exit_with_message("Impossible to select data for vehicle in the DB");
        }
        $this->returnVehicleForm($vehicule);
    }

    //----------------------------------------------------------------------------------

    public function getAllVehiculeAssoc(){
        $vehicule = selectDB("VEHICULES", "*", "appartenance=1");
        if(!$vehicule){
            exit_with_message("Impossible to select data for vehicle in the DB");
        }
        $this->returnVehicleForm($vehicule);
    }
    //----------------------------------------------------------------------------------

    public function getVehiculeById($int)
    {
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $condition = "lsv.id_vehicule = ". $int;
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join, $condition, "bool");
        if(!$data){
            $data = selectDB("VEHICULES", "*", "id_vehicule = ". $int);
        }
        $this->returnDesDataForBookedBookingVehicle($data);
    }

    //----------------------------------------------------------------------------------

    public function getVehiculeAvailable($debut, $fin){
        $columns = "DISTINCT v.*";
        $join = "LEFT JOIN LINKSERVICEVEHICLE lsv ON v.id_vehicule = lsv.id_vehicule LEFT JOIN SERVICE s ON lsv.id_service = s.id_service";
        $condition = "v.appartenance != 1 AND v.id_vehicule NOT IN (
                SELECT DISTINCT lsv.id_vehicule
                FROM LINKSERVICEVEHICLE lsv
                JOIN SERVICE s ON lsv.id_service = s.id_service
                WHERE ( s.service_date_debut <= '".$fin."'
                AND s.service_date_fin >= '".$debut."')
                );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);
        $this->returnVehicleForm($data);
    }

    //----------------------------------------------------------------------------------

    public function getVehiculeAssocAvailable($debut, $fin){
        $columns = "DISTINCT v.*";
        $join = "LEFT JOIN LINKSERVICEVEHICLE lsv ON v.id_vehicule = lsv.id_vehicule LEFT JOIN SERVICE s ON lsv.id_service = s.id_service";
        $condition = "v.appartenance = 1 AND v.id_vehicule NOT IN (
                SELECT DISTINCT lsv.id_vehicule
                FROM LINKSERVICEVEHICLE lsv
                JOIN SERVICE s ON lsv.id_service = s.id_service
                WHERE ( s.service_date_debut <= '".$fin."'
                AND s.service_date_fin >= '".$debut."')
                );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);
        $this->returnVehicleForm($data);
    }
    //----------------------------------------------------------------------------------

    public function getMyVehiculeAvailable($debut, $fin, $id_user)
    {
        $columns = "DISTINCT v.*";
        $join = "LEFT JOIN LINKSERVICEVEHICLE lsv ON v.id_vehicule = lsv.id_vehicule LEFT JOIN SERVICE s ON lsv.id_service = s.id_service";
        $condition = "v.id_owner= ".$id_user." AND v.id_vehicule NOT IN (
                SELECT DISTINCT lsv.id_vehicule
                FROM LINKSERVICEVEHICLE lsv
                JOIN SERVICE s ON lsv.id_service = s.id_service
                WHERE ( s.service_date_debut <= '".$fin."'
                AND s.service_date_fin >= '".$debut."')
                );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);
        $this->returnVehicleForm($data);
    }

    //----------------------------------------------------------------------------------
    public function getMyVehicules($idUser){
        $data = selectDB("VEHICULES", "*", "id_owner= ".$idUser);

        $this->returnVehicleForm($data);
    }

    //----------------------------------------------------------------------------------
    // User with the role 4
    public function getMyBookedVehicle($apiKey){
        $userId = getIdUserFromApiKey($apiKey);
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $condition = "s.id_user_booking=".$userId;
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join, $condition);
        $this->returnDesDataForBookedBookingVehicle($data);
    }

    //----------------------------------------------------------------------------------

    // User with the role 3, and had created a vehicle
    public function getMyVehicleWichAreBooked($apiKey){
        $userId = getIdUserFromApiKey($apiKey);
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $condition = "v.id_owner=".$userId;
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join, $condition);
        $this->returnDesDataForBookedBookingVehicle($data);

    }

    //----------------------------------------------------------------------------------

    public function getAllBookedVehicle(){
        $columns = "s.*, v.*, u.id_user, u.telephone, u.email, u.id_role";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule LEFT JOIN UTILISATEUR u ON s.id_user_booking = u.id_user";
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join);
        $this->returnDesDataForBookedBookingVehicle($data);
    }

    //----------------------------------------------------------------------------------
    public function createVehicule(VehiculeModel $vehicule, $id_user)
    {
        $string = "immatriculation='" . $vehicule->immatriculation ."'";

        $Select = selectDB("VEHICULES", "*", $string, "bool");

        if($Select){
            exit_with_message("La plaque d'immatriculation est déjà utilisé :/", 403);
        }

        $create = insertDB("VEHICULES", ["capacite","nom_du_vehicules","nombre_de_place","id_ventrepot", "immatriculation", "appartenance", "id_user"]
            ,[$vehicule->capacite ,$vehicule->nom_du_vehicules,$vehicule->nombre_de_place,$vehicule->id_entrepot, $vehicule->immatriculation, $vehicule->appartenance, $id_user]);

        if(!$create){
            exit_with_message("Error, the vehicle can't be created, plz try again", 500);
        }

        exit_with_message("Vehicle created", 200);
    }
    //------------------------------------------------------------------------------------------
    public function deleteVehicule($id, $id_owner)
    {

        // Verifier si c'est bien son vehicule
        if( $id_owner !== selectDB("VEHICULES", "id_owner", "id_vehicule= ".$id)[0]["id_owner"]){
            exit_with_message("You can't delete this vehicule, this isn't yours", 403);
        }

        $deleted = deleteDB("VEHICULES", "id_vehicule=".$id ,"bool");

        if(!$deleted){
            exit_with_message("Error, the vehicle can't be deleted, plz try again", 500);
        }
        exit_with_message("Vehicle deleted", 200);
    }

    //------------------------------------------------------------------------------------------

    public function bookingAVehicle($apiKey, ServiceModel $serviceModel){

        $id_user = getIdUserFromApiKey($apiKey);

        // Try to know if a reservation is on the same hour
        $columns = "s.*, v.*";
        $join = "LEFT JOIN SERVICE s ON lsv.id_service = s.id_service LEFT JOIN VEHICULES v ON lsv.id_vehicule = v.id_vehicule";
        $condition = "lsv.id_vehicule=".$serviceModel->id_service /*(C'est l'id du vehicule, c'est enregistré comme ça dans la fonction parente)*/."
                      AND s.service_date_debut < '".$serviceModel->date_fin."'
                      AND s.service_date_fin > '".$serviceModel->date_debut."';";
        $data = selectJoinDB("LINKSERVICEVEHICLE lsv", $columns, $join, $condition, 'bool');

        if($data){
            exit_with_message("The Vehicle is already booked at this time", 200);
        }

        $return = insertDB("SERVICE", ["description_service", "type_service", "service_date_debut", "service_date_fin", "id_user_booking"], [$serviceModel->description, $serviceModel->type_service, $serviceModel->date_debut, $serviceModel->date_fin, $id_user], "MAX(id_service)"); //

        /*(L'id du vehicule est enregistré comme ça dans la fonction parente)*/
        $result = insertDB("LINKSERVICEVEHICLE", ["id_service", "id_vehicule"], [$return[0][0], $serviceModel->id_service]);
        if(!$result){
            if(deleteDB("SERVICE", "id_service=".$serviceModel->id_service)){
                exit_with_message("Something went wrong :///", 200);
            }
        }

        $this->getMyBookedVehicle($apiKey);
    }

    //------------------------------------------------------------------------------------------

    public function unBookingAVehicle($id_service, $apiKey){
        $id_user = getIdUserFromApiKey($apiKey);
        $role = getRoleFromApiKey($apiKey);

        if($role >= 3){
            $exist = selectDB("SERVICE", "*", "id_service=".$id_service." AND id_user_booking=".$id_user, "bool");
            if(!$exist) {
                exit_with_message("Error, this reservation isn't yours", 403);
            }
        } elseif ($role <= 2){
            $exist = selectDB("SERVICE", "*", "id_service=".$id_service, "bool");
            if(!$exist) {
                exit_with_message("Error, this reservation don't exist", 500);
            }
        }

        $data = selectDB("LINKSERVICEVEHICLE", "*", "id_service=".$id_service)[0];

        if(deleteDB("LINKSERVICEVEHICLE", "id_service=".$id_service)){
            if(deleteDB("SERVICE", "id_service=".$id_service)){
                exit_with_message("Reservation canceled", 200);
            } else {
                insertDB("LINKSERVICEVEHICLE", ["id_service", "id_vehicule"], [$data["id_service"], $data["id_vehicule"]]);
            }
        }

        exit_with_message("Error when deleting your reservation");

    }

    //------------------------------------------------------------------------------------------

    private function returnDesDataForBookedBookingVehicle($data) {
        $vehicleArray = [];
        $vehicleMap = [];

        foreach ($data as $index => $item) {
            $vehicleId = $item['id_vehicule'];

            if (!isset($vehicleMap[$vehicleId])) {
                $user = selectDB("UTILISATEUR", "*", "id_user=".$data[$index]["id_owner"]);
                $vehicle = returnVehicle($data, $index);
                $vehicle->addContact(returnMiniUser($user));
                $vehicleMap[$vehicleId] = $vehicle;
                $vehicleArray[] = $vehicle;
            }

            $service = returnService($data, $index);
            $service->addMiniUser(returnMiniUser($data, $index));
            $vehicleMap[$vehicleId]->addService($service);
        }

        exit_with_content($vehicleArray);
    }

    public function returnVehicleForm($data, $exit = true){
        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
        }
        if($exit == true){
            exit_with_content($vehiculetArray);
        } else {
            return $vehiculetArray;
        }
    }
}