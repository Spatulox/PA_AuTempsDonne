<?php
include_once './Repository/BDD.php';
include_once './Models/userModel.php';
include_once './exceptions.php';

class UserRepository {

    //-------------------------------------

    public function getUsers($index = 1){
        $usersArray = selectDB("UTILISATEUR", "*", "index_user='".$index."'");

        $user = [];
        $usersTest = [];

        for ($i=0; $i < count($usersArray); $i++) {
            $user[$i] = new UserModel($usersArray[$i]['id_user'], $usersArray[$i]['nom'], $usersArray[$i]['prenom'], $usersArray[$i]['date_inscription'], $usersArray[$i]['email'], $usersArray[$i]['telephone'], $usersArray[$i]['type'], $usersArray[$i]['role'], $usersArray[$i]['apikey'], $usersArray[$i]['index_user']);
        }
        return $user;
    }

    //-------------------------------------

    public function getUser($id){
        $user = selectDB("UTILISATEUR", "*", "id_user='".$id."'");
        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], "hidden", $user[0]['index_user']);
    }

    //-------------------------------------

    public function getWaitUsers(){
        $usersArray = selectDB("UTILISATEUR", "*", "index_user=2", "bool");
        if(!$usersArray){
            exit_with_message("No users waiting user to validate", 200);
        }

        for ($i=0; $i < count($usersArray); $i++) {
            $user[$i] = new UserModel($usersArray[$i]['id_user'], $usersArray[$i]['nom'], $usersArray[$i]['prenom'], $usersArray[$i]['date_inscription'], $usersArray[$i]['email'], $usersArray[$i]['telephone'], $usersArray[$i]['type'], $usersArray[$i]['role'], $usersArray[$i]['apikey'], $usersArray[$i]['index_user']);
        }

        return $user;
    }

    //-------------------------------------

    public function getUserApi($api){

        $user = selectDB("UTILISATEUR", "*", "apikey='".$api."'", "bool");
        if(!$user){
            exit_with_message('Wrong apikey or no data');
        }

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], "hidden", $user[0]['index_user']);
    }

    //-------------------------------------
    
    public function createUser(UserModel $user, $password){
        
        $index_user = 1;
        if($user->role == 3){
            $index_user = 2;
        }

        $tmp = selectDB('UTILISATEUR', '*', 'email="'.$user->email.'"', "bool");
        if($tmp){
            exit_with_message("Error, email already exist, plz chose another one", 403);
        }
        

        $user = insertDB("UTILISATEUR", ["nom", "prenom", "email", "telephone", "index_user", "date_inscription", "type", "role", "apikey", "mdp"], [$user->nom, $user->prenom, $user->email, $user->telephone, $index_user, date('Y-m-d'), $user->type, $user->role, "null", strtoupper(hash('sha256', $password))], "email='".$user->email."'");

        if(!$user){
            exit_with_message("Error, your account don't exist, plz try again", 500);
        }

        $apiKey = hash('sha256', $user[0]["id_user"] . $user[0]["nom"] . $user[0]["prenom"] . $password . $user[0]["email"]);
        updateDB("UTILISATEUR", ["apikey"], [$apiKey], "email='".$user[0]["email"]."'");


        $user = selectDB('UTILISATEUR', '*', 'email="'.$user[0]['email'].'"');

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], $user[0]['apikey'], $user[0]['index_user']);
    }

    //-------------------------------------
    
    public function updateUser($id_user, $key, $data){

        $tmp = updateDB("UTILISATEUR", [$key], [$data], "id_user=" . $id_user);

        if(!$tmp){
            exit_with_message("Error");
        }

        return $this->getUser($id_user);
    }


    //-------------------------------------

    public function unreferenceUser($id, $apiKey){

        $role = getRoleFromApiKey($apiKey);

        $user = selectDB("UTILISATEUR", "id_user, index_user", "apikey='".$apiKey."'", "bool")[0];

        $userToDelete = $this->getUser($id);

        var_dump($userToDelete);

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

        return updateDB("UTILISATEUR", ['index_user'], [-1], "id_user=".$id);
    }
    
}


?>