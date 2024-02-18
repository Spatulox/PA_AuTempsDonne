<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';

include_once './Models/userModel.php';

class LoginRepository {
    private $connection = null;

    // I'm not sure about this function lol (unuse)
    function __construct() {
    }

    //-------------------------------------

    public function getPersonnalApiKey($nom, $prenom, $mdp){
		$hashedString = hash('sha256', $mdp);
        //WHERE LOWER(nom) = LOWER('marc') AND LOWER(prenom) = LOWER('jean')
        $user = selectDB("UTILISATEUR", "*", "(mdp='".strtoupper($hashedString)."' OR mdp='". strtolower($hashedString) ."') AND (LOWER(nom) = LOWER('". $nom ."') ) AND (LOWER(prenom) = LOWER('". $prenom ."') )", "bool");
        if(!$user){ 
        	exit_with_message("Wrong username or password", 500);
        }

        return new UserModel($user[0]['id_user'], $user[0]['nom'], $user[0]['prenom'], $user[0]['date_inscription'], $user[0]['email'], $user[0]['telephone'], $user[0]['type'], $user[0]['role'], $user[0]['apikey']);
    }   

}

?>