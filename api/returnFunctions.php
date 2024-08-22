<?php

function returnUser($dataFromDb, $address = "yep@yep.com", $id = 0){
    $user = new UserModel($dataFromDb[$id]["id_user"], $dataFromDb[$id]["nom"], $dataFromDb[$id]["prenom"], $dataFromDb[$id]["date_inscription"], $dataFromDb[$id]["email"],  $address,  $dataFromDb[$id]["telephone"],  $dataFromDb[$id]["id_role"],  $dataFromDb[$id]["apikey"],  $dataFromDb[$id]["id_index"],  $dataFromDb[$id]["id_entrepot"],  $dataFromDb[$id]["date_premium"],  $dataFromDb[$id]["month_premium"], $dataFromDb[$id]["premium_stripe_id"]);
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
