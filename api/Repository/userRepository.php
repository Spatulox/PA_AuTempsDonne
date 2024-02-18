<?php
include_once './Repository/BDD.php';
include_once './Models/userModel.php';
include_once './exceptions.php';

class UserRepository {
    private $connection = null;

    // I'm not sure about this function lol (unuse)
    function __construct() {
       
    }
    
    //-------------------------------------

    public function getUsers($index = 1){
        $usersArray = selectDB("UTILISATEUR", "*", "index_user='".$index."'");

        $user = [];
        $usersTest = [];

        for ($i=0; $i < count($usersArray); $i++) {
            $user[$i] = new UserModel($usersArray[$i]['id_user'], $usersArray[$i]['nom'], $usersArray[$i]['prenom'], $usersArray[$i]['date_inscription'], $usersArray[$i]['email'], $usersArray[$i]['telephone'], $usersArray[$i]['type'], $usersArray[$i]['role'], $usersArray[$i]['apikey']);
        }
        return $user;
    }

    //-------------------------------------

    public function getUser($id){
        $user = selectDB("UTILISATEUR", "*", "id_user='".$id."'");
        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], "hidden");
    }

    //-------------------------------------

    public function getUserApi($api){

        $user = selectDB("UTILISATEUR", "*", "apikey='".$api."'");

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], "hidden");
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

        $apiKey = hash('sha256', $user[0]["id_user"] . $user[0]["nom"] . $user[0]["prenom"] . $password);
        updateDB("UTILISATEUR", ["apikey"], [$apiKey], "email='".$user[0]["email"]."'");


        $user = selectDB('UTILISATEUR', '*', 'email="'.$user[0]['email'].'"');

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], $user[0]['apikey']);
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

    //-------------------------------------

    public function unreferenceUser($id, $apiKey){

        $role = getRoleFromApiKey($apiKey);

        $apiToRole = selectDB("UTILISATEUR", "id_users", "apikey='".$apiKey."'")[0]['id_users'];
        // var_dump($apiKey, $apiToRole);
        // exit();

        if ($id != $id_users && $role != 1){
            exit_with_message("You can't unrefence a user wich is not you");
        }

        return updateDB("UTILISATEUR", ['user_index'], [-1], "id_users=".$id);
        //deleteDB("UTILISATEUR", "id_users=".$id);
    }
    
    */
}


?>