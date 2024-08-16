<?php

use App\Models\User;

include_once './Repository/BDD.php';
include_once './Models/userModel.php';
include_once './Models/DispoModel.php';
include_once './exceptions.php';

class UserRepository {


    //-------------------------------------

    public function getUsers($index = 2,$apiKey){
        $usersArray = selectDB("UTILISATEUR", "*", "id_index='".$index."'");

        $user = [];

        for ($i=0; $i < count($usersArray); $i++) {
            $address = selectDB("ADRESSE", "*", "id_adresse='".$usersArray[0]['id_adresse']."'")[0]["adresse"];

            $user[$i] = new UserModel($usersArray[$i]['id_user'], $usersArray[$i]['nom'], $usersArray[$i]['prenom'], $usersArray[$i]['date_inscription'], $usersArray[$i]['email'], $address, $usersArray[$i]['telephone'], $usersArray[$i]['id_role'], $usersArray[$i]['apikey'], $usersArray[$i]['id_index'], $usersArray[$i]['id_entrepot']);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $user;
    }

    //-------------------------------------

    public function getUser($id,$apiKey){
        $user = selectDB("UTILISATEUR", "*", "id_user='".$id."'");

        $address = selectDB("ADRESSE", "*", "id_adresse='".$user[0]['id_adresse']."'")[0]["adresse"];

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $address, $user[0]['telephone'], $user[0]['id_role'], "hidden", $user[0]['id_index'], $user[0]['id_entrepot']);
    }

    //-------------------------------------

    public function getWaitUsers($apiKey){
        $usersArray = selectDB("UTILISATEUR", "*", "id_index=3", "bool");
        if(!$usersArray){
            exit_with_message("No users waiting user to validate", 200);
        }

        if($usersArray == false){
            exit_with_message("No waiting user", 200);
        }

        for ($i=0; $i < count($usersArray); $i++) {
            $address = selectDB("ADRESSE", "*", "id_adresse='".$usersArray[0]['id_adresse']."'")[0]["adresse"];

            $user[$i] = new UserModel($usersArray[$i]['id_user'], $usersArray[$i]['nom'], $usersArray[$i]['prenom'], $usersArray[$i]['date_inscription'], $usersArray[$i]['email'], $address, $usersArray[$i]['telephone'], $usersArray[$i]['id_role'], $usersArray[$i]['apikey'], $usersArray[$i]['id_index'], $usersArray[$i]['id_entrepot']);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $user;
    }

    //-------------------------------------

    public function getUserApi($api,$apiKey){

        $user = selectDB("UTILISATEUR", "*", "apikey='".$api."'", "bool");
        if(!$user){
            exit_with_message('Wrong apikey or no data');
        }

        $address = selectDB("ADRESSE", "*", "id_adresse='".$user[0]['id_adresse']."'")[0]["adresse"];

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $address, $user[0]['telephone'], $user[0]['id_role'], "hidden", $user[0]['id_index'], $user[0]['id_entrepot']);
    }

    //-------------------------------------
    
    public function createUser(UserModel $user, $password, $addressToInsert){

        $tmp = selectDB('UTILISATEUR', '*', 'email="'.$user->email.'"', "bool");
        if($tmp){
            exit_with_message("Error, email already exist, plz chose another one", 403);
        }

        $add = new adresseRepository();
        $resp = $add->CreateAdresse($addressToInsert);

        if($resp instanceof adresseModel){
            $user->address = $resp->id_adresse;
        } else {
            exit_with_message($resp["message"]);
        }

        $index_user = 1;
        if($user->role == 3){
            $index_user = 2;
        }

        $user->email = strtolower($user->email);

        $address = insertDB("ADRESSE", ["adresse"], [$user->address]);

        if(!$address){
            exit_with_message("Error when insert id_adresse");
        }

        $address = selectDB('ADRESSE', '*', "adresse='".$user->address."'", "bool");
        if(!$address){
            exit_with_message("Error, address not found");
        }

        $address = selectDB('ADRESSE', '*', "adresse='".$user->address."'");
        $addresse = $address[0]["id_adresse"];


        $user = insertDB("UTILISATEUR", ["nom", "prenom", "email", "id_adresse", "telephone", "id_index", "date_inscription", "id_role", "apikey", "mdp", "id_entrepot"], [$user->nom, $user->prenom, $user->email, $addresse, $user->telephone, $user->id_index, date('Y-m-d'), $user->id_role, "NULL", strtoupper(hash('sha256', $password)), "NULL"], "email='".$user->email."'");//

        if(!$user){
            exit_with_message("Error, your account don't exist, plz try again", 500);
        }


        $apiKey = hash('sha256', $user[0]["id_user"] . $user[0]["nom"] . $user[0]["prenom"] . $password . $user[0]["email"]);
        updateDB("UTILISATEUR", ["apikey"], [$apiKey], "email='".$user[0]["email"]."'");


        $user = selectDB('UTILISATEUR', '*', 'email="'.$user[0]['email'].'"');

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =$user[0]['id_user'];

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['adresse'], $user[0]['telephone'], $user[0]['id_role'], $user[0]['apikey'], $user[0]['id_index'], $user[0]['id_entrepot']);
    }

    //-------------------------------------
    /*
    public function updateUser(UserModel $user, $apiKey){

        $idUSer = selectDB("UTILISATEUR", 'id_users', "apikey='".$apiKey."'")[0]["id_users"];
        if ($idUSer != $user->id_users){
            exit_with_message("You can't update an user which is not you");
        }

        updateDB("UTILISATEUR", ["role", "pseudo", "user_index"], [$user->role, $user->pseudo, $user->user_index], 'id_users='.$user->id_users." AND apikey='".$apiKey."'");

        return $this->getUser($user->id_users, null);
    }


    */

    public function getUserEmail($email){

        $user = selectDB('UTILISATEUR', '*', 'email="'.$email.'"');

        if(count($user) > 0){
            return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['adresse'], $user[0]['telephone'], $user[0]['id_role'], $user[0]['apikey'], $user[0]['id_index'], $user[0]['id_entrepot']);
        }
        exit_with_message('Email not exist');


    }

    public function updateUser($id_user, $cle, $data){
        $data = updateDB("UTILISATEUR", [$cle], [$data] , "id_user='".$id_user."'");

        if($data){
            exit_with_content($this->getUser($id_user));
        }
        exit_with_message("User was not updated");
    }

    public function updateUserValidate($id_user, $id_index,$apiKey){
        $debug = updateDB("UTILISATEUR", ["id_index"], [$id_index], "id_user='".$id_user."'");

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        if(!$debug){

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit not deleted .";
            $id_secteur = 1;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            exit_with_message("Error while update user");
        }
    }

    //-------------------------------------

    public function unreferenceUserById($id, $apiKey){

        $role = getRoleFromApiKey($apiKey);

        $user = selectDB("UTILISATEUR", "id_user, id_index", "apikey='".$apiKey."'", "bool")[0];

        $userToDelete = $this->getUser($id);

        //var_dump($userToDelete);

        if($userToDelete->role == 1){
            exit_with_message("You can't unrefence an admin", 403);
        }

        if($userToDelete->role == 2 && $role == 2){
            if($id != $user["id_user"]){
                exit_with_message("You can't unrefence a modo if you're a modo, unless if it's you :/", 403);
            }
        }

        if ($id != $user['id_user'] && $role > 2 ){
            exit_with_message("You can't unrefence a user wich is not you", 403);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return updateDB("UTILISATEUR", ['id_index'], [1], "id_user=".$id);
    }

    public function unreferenceUserByApikey($apiKey){

        $role = getRoleFromApiKey($apiKey);

        $user = selectDB("UTILISATEUR", "id_user, id_index", "apikey='".$apiKey."'", "bool")[0];

        $id = $user["id_user"];

        $userToDelete = $this->getUser($id);

        //var_dump($userToDelete);

        if($userToDelete->role == 1){
            exit_with_message("You can't unrefence an admin", 403);
        }

        if($userToDelete->role == 2 && $role == 2){
            if($id != $user["id_user"]){
                exit_with_message("You can't unrefence a modo if you're a modo, unless if it's you :/", 403);
            }
        }

        if ($id != $user['id_user'] && $role > 2 ){
            exit_with_message("You can't unrefence a user wich is not you", 403);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return updateDB("UTILISATEUR", ['id_index'], [1], "id_user=".$id);
    }

//--------------------------------------------------------------------------------

    public function dispoUser($id_dispo, $id,$apiKey)
    {
        $check =selectDB("DISPONIBILITE" , "*", "id_user=".$id, "bool");
        if ($check){
            exit_with_message("User already dispoted",500);
        }
        for ($i = 0; $i <count($id_dispo) ; $i++) {

            $res=insertDB("DISPONIBILITE" ,["id_dispo", "id_user"], [$id_dispo[$i], $id]);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $this->getDispoUserMe($id);
    }

    //--------------------------------------------------------------------------------

    public function getAllDispoUsers($apiKey)
    {
        $conditions = "id_index= 2 AND id_role=3 ";
        $columns = "UTILISATEUR.id_user, DISPONIBILITE.id_dispo, SEMAINE.dispo";
        $join = "INNER JOIN UTILISATEUR ON DISPONIBILITE.id_user = UTILISATEUR.id_user INNER JOIN SEMAINE ON SEMAINE.id_dispo = DISPONIBILITE.id_dispo ";
        $usersArray= selectJoinDB("DISPONIBILITE", $columns, $join, $conditions );

        $uniqueUsers = [];

        foreach ($usersArray as $user) {
            $id_user = $user["id_user"];
            if (!in_array($id_user, $uniqueUsers)) {
                $uniqueUsers[] = $id_user;
            }
        }

        for ($i = 0; $i <count($uniqueUsers); $i++) {
            $res = selectJoinDB("DISPONIBILITE", $columns, $join,"UTILISATEUR.id_user=".$uniqueUsers[$i],"bool");

            for ($j = 0; $j <count($res); $j++) {

                $dispoArray[] = [
                    "id_dispo" => $res[$j]["id_dispo"],
                    "dispo" => $res[$j]["dispo"],
                ];
            }

            $dispos[] = new DispoModel( $uniqueUsers[$i],$dispoArray);
            unset($res);
            unset($dispoArray);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $dispos;
    }

    //-------------------------------------------------------------------------------------------------------

    public function updatedispoUser($id_dispo, $id,$apiKey)
    {
        deleteDB("DISPONIBILITE", "id_user=".$id);
        $dispos = [];

        for ($i = 0; $i < count($id_dispo); $i++) {
            insertDB("DISPONIBILITE", ["id_dispo", "id_user"], [$id_dispo[$i], $id]);
        }

        $res = selectDB("DISPONIBILITE", "*", "id_user=".$id);

        foreach ($res as $dispo) {
            $dispos[] = new DispoModel($dispo["id_user"], $dispo["id_dispo"]);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return($dispos);
    }

    //-----------------------------------------------------------------------------------------------

    public function updateentrepotUser($id_entrepot, $id,$apiKey)
    {
        $res=updateDB("UTILISATEUR", ["id_entrepot"], [$id_entrepot],"id_user=".$id);

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $res;
    }

    //-----------------------------------------------------------------------------------

    public function getDispoUserMe($id,$apiKey)
    {
        $conditions = "UTILISATEUR.id_user=".$id;
        $columns = "UTILISATEUR.id_user, DISPONIBILITE.id_dispo, SEMAINE.dispo";
        $join = "INNER JOIN UTILISATEUR ON DISPONIBILITE.id_user = UTILISATEUR.id_user INNER JOIN SEMAINE ON SEMAINE.id_dispo = DISPONIBILITE.id_dispo ";
        $usersArray= selectJoinDB("DISPONIBILITE", $columns, $join, $conditions );

        $uniqueUsers = [];

        foreach ($usersArray as $user) {
            $id_user = $user["id_user"];
            if (!in_array($id_user, $uniqueUsers)) {
                $uniqueUsers[] = $id_user;
            }
        }

        for ($i = 0; $i <count($uniqueUsers); $i++) {
            $res = selectJoinDB("DISPONIBILITE", $columns, $join,"UTILISATEUR.id_user=".$uniqueUsers[$i]);

            for ($j = 0; $j <count($res); $j++) {

                $dispoArray[] = [
                    "id_dispo" => $res[$j]["id_dispo"],
                    "dispo" => $res[$j]["dispo"],
                ];
            }

            $dispos[] = new DispoModel( $uniqueUsers[$i],$dispoArray);
            unset($res);
            unset($dispoArray);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $dispos;
    }

    //-----------------------------------------------------------------------------------------

    public function updateRoleUser($role, $id,$apiKey)
    {
        updateDB("UTILISATEUR", ["id_role"], [$role],"id_user=".$id);

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_content($this->getUser($id));
       
    }

    //-----------------------------------------------------------------------------------------

    public function GetAllUserDate($date,$apiKey)
    {

        $conditions = "SEMAINE.id_dispo=".$date;
        $columns = "UTILISATEUR.id_user, DISPONIBILITE.id_dispo, SEMAINE.dispo";
        $join = "INNER JOIN UTILISATEUR ON DISPONIBILITE.id_user = UTILISATEUR.id_user INNER JOIN SEMAINE ON SEMAINE.id_dispo = DISPONIBILITE.id_dispo ";
        $usersArray= selectJoinDB("DISPONIBILITE", $columns, $join, $conditions);

        $uniqueUsers = [];

        foreach ($usersArray as $user) {
            $id_user = $user["id_user"];
            if (!in_array($id_user, $uniqueUsers)) {
                $uniqueUsers[] = $id_user;
            }
        }

        $all=[];

        for ($i = 0; $i <count($usersArray) ; $i++) {

            $all[]=$this->getUser($usersArray[$i]["id_user"]);

        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        return $all;
    }


}
?>