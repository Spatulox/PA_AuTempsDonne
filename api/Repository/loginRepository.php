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

    public function getPersonnalApiKey($email, $mdp){
		$hashedString = hash('sha256', $mdp);
        $user = selectDB("UTILISATEUR", "*", "(mdp='".strtoupper($hashedString)."' OR mdp='". strtolower($hashedString) ."') AND (LOWER(email) = LOWER('". $email ."') )", "bool");
        if(!$user){ 
        	exit_with_message("Wrong username or password", 403);
        }
        return returnUser($user);
    }   

}

?>