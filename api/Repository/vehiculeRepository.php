<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class VehiculeRepository
{

    public function getAllVehicule()
    {
        $vehicule = selectDB("VEHICULES", "*");
        if(!$vehicule){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        $vehiculetArray = [];

        for ($i=0; $i < count($vehicule) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($vehicule, $i);
        }

        exit_with_content($vehiculetArray);
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
                WHERE ( s.service_date_debut <= '".$fin." 23:59:59'
                AND s.service_date_fin >= '".$debut." 00:00:00')
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
                WHERE ( s.service_date_debut <= '".$fin." 23:59:59'
                AND s.service_date_fin >= '".$debut." 00:00:00')
                );";
        $data = selectJoinDB("VEHICULES v", $columns, $join, $condition);
        $this->returnVehicleForm($data);
    }

    //----------------------------------------------------------------------------------
    public function getMyVehicules($idUser){
        $data = selectDB("VEHICULES", "*", "id_user= ".$idUser);

        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
        }

        exit_with_content($vehiculetArray);
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
            exit_with_message("Y'a déjà un même vehicule", 403);
        }

        $create = insertDB("VEHICULES", ["capacite","nom_du_vehicules","nombre_de_place","id_ventrepot", "immatriculation", "appartenance", "id_user"]
            ,[$vehicule->capacite ,$vehicule->nom_du_vehicules,$vehicule->nombre_de_place,$vehicule->id_entrepot, $vehicule->immatriculation, $vehicule->appartenance, $id_user]);

        if(!$create){
            exit_with_message("Error, the vehicule can't be created, plz try again", 500);
        }

        exit_with_message("Vehicule created", 200);
    }
    //------------------------------------------------------------------------------------------
    public function deleteVehicule($id)
    {
        $deleted = deleteDB("VEHICULES", "id_vehicule=".$id ,"bool");

        if(!$deleted){
            exit_with_message("Error, the activite can't be deleted, plz try again", 500);
        }
        exit_with_message("Vehicule deleted", 200);
    }

    //------------------------------------------------------------------------------------------

    public function bookingAVehicle($apiKey){

        $id_user = getIdUserFromApiKey($apiKey);
        $role = getRoleFromApiKey($apiKey);
        //insertDB("SERVICE", ["description_service", "type_service", "service_date_debut", "service_date_fin", "id_user_booking"], ["Partage de vehicule", 1, "", "", $id_user]);
    }

    //------------------------------------------------------------------------------------------

    public function unBookingAVehicle($id_service, $apiKey){
        $id_user = getIdUserFromApiKey($apiKey);
        $role = getRoleFromApiKey($apiKey);

        if($role >= 3){
            $exist = selectDB("SERVICE", "*", "id_service=".$id_service." AND id_user_booking=".$id_user, "bool");
            if(!$exist) {
                exit_with_message("Error, this reservation don't exist for you", 403);
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

    private function returnVehicleForm($data){
        $vehiculetArray = [];
        for ($i=0; $i < count($data) ; $i++) {
            $vehiculetArray[$i] = returnVehicle($data, $i);
        }
        exit_with_content($vehiculetArray);
    }
}