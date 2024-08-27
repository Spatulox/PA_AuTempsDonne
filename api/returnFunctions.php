<?php

include_once("./Models/miniUserModel.php");
include_once("./Models/userModel.php");
include_once("./Models/vehiculeModel.php");
include_once("./Models/serviceModel.php");
include_once("./Models/planningModel.php");

include_once("./Repository/planningRepository.php");

function returnUser($dataFromDb, $address = "yep@yep.com", $id = 0){
    $user = new UserModel($dataFromDb[$id]["id_user"], $dataFromDb[$id]["nom"], $dataFromDb[$id]["prenom"], $dataFromDb[$id]["date_inscription"], $dataFromDb[$id]["email"],  $address,  $dataFromDb[$id]["telephone"],  $dataFromDb[$id]["id_role"],  $dataFromDb[$id]["apikey"],  $dataFromDb[$id]["id_index"],  $dataFromDb[$id]["id_entrepot"],  $dataFromDb[$id]["date_premium"],  $dataFromDb[$id]["month_premium"], $dataFromDb[$id]["premium_stripe_id"]);
    return $user;
}

function returnMiniUser($dataFromDb, $id = 0){
    $user = new MiniUserModel($dataFromDb[$id]["id_user"], $dataFromDb[$id]["email"], $dataFromDb[$id]["telephone"],  $dataFromDb[$id]["id_role"]);
    return $user;
}
function returnVehicle($dataFromDb, $id = 0){
    $vehicle = new VehiculeModel($dataFromDb[$id]["id_vehicule"], $dataFromDb[$id]["capacite"], $dataFromDb[$id]["nom_du_vehicules"], $dataFromDb[$id]["nombre_de_place"], $dataFromDb[$id]["id_ventrepot"], $dataFromDb[$id]["appartenance"], $dataFromDb[$id]["immatriculation"], $dataFromDb[$id]["id_owner"]);
    return $vehicle;
}

function returnService($dataFromDb, $id = 0){
    $service = new ServiceModel($dataFromDb[$id]["id_service"], $dataFromDb[$id]["description_service"], $dataFromDb[$id]["type_service"], $dataFromDb[$id]["service_date_debut"], $dataFromDb[$id]["service_date_fin"]);
    return $service;
}

function returnPlanning($dataFromDb, $i = 0){

    $plannRepo = new PlanningRepository();

    $planning = new PlanningModel(
        $dataFromDb[$i]['id_planning'],
        $dataFromDb[$i]['description'],
        $dataFromDb[$i]['date_activite'],
        $dataFromDb[$i]['id_index_planning'],
        $dataFromDb[$i]['id_activite']
    );

    $planning->setId($dataFromDb[$i]['id_planning']);

    $planning->setAddress($plannRepo->getTrajetFromId($dataFromDb[$i]["id_trajets"]));

    $planning->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$dataFromDb[$i]['id_index_planning'])[0]);
    $planning->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$dataFromDb[$i]['id_activite'])[0]);

    $res = selectDB("PARTICIPE pa INNER JOIN UTILISATEUR u ON pa.id_user = u.id_user", "u.email", "pa.id_planning=" . $dataFromDb[$i]['id_planning'] . " AND u.id_role = 3" );

    $tab=[];
    foreach ($res as $row) {
        $tab[] = $row["email"];
    }
    $planning->setemailuser($tab);
    return $planning;
}