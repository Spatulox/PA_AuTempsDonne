<?php
include_once './Repository/userRepository.php'; 
include_once './Models/userModel.php';

class UserService {
    
    public $uri;

    /*
     *  Récupère tous les utilisateurs
    */
    public function getAllUsers() {

        $userRepository = new UserRepository();
        return $userRepository->getUsers();
    }

    /*
     *  Récupère tous les utilisateurs en attente de validation
    */
    public function getAllWaitingUsers() {
        $userRepository = new UserRepository();
        return $userRepository->getWaitUsers();
    }

    /*
     *  Récupère un utilisateur avec son apikey
    */
    public function getUserByApikey($apiKey){
        $userRepository = new UserRepository();
        return $userRepository->getUserApi($apiKey);
    }

    /*
     *  Récupère un utilisateur par son id
    */

    public function getUserById($id) {
        $userRepository = new UserRepository();
        return $userRepository->getUser($id);
    }

    /*
     *  Créer un utilisateur
    */

    public function createUser(UserModel $user, $password, $address) {
        $userRepository = new UserRepository();
        return $userRepository->createUser($user, $password, $address);
    }

    
    /*
     *  Met à jour un utilisateur
    */

    
    public function updateUser($apiKey, $dataArray){
        
        if (!is_array($dataArray) && empty($dataArray)){
            exit_with_message("Not an array / json data", 500);
        }



        $role = getRoleFromApiKey($apiKey);
        $id_user = getIdUserFromApiKey($apiKey);


        $idUserUpdate = getIdUSerFromEmail($dataArray["email"]);

        if($role > 2 && $id_user != $idUserUpdate){
            exit_with_message("Vous ne pouvez pas update un utilisateur qui n'est pas vous");

        }

        $cles = array_keys($dataArray);
        $userRepo = new UserRepository;


        foreach ($cles as $cle) {
            if (!empty($dataArray[$cle]) && ( $cle != "role" && $cle != "mdp" && $cle != "type" ) ) {

                $userRepo->updateUser($idUserUpdate, $cle, $dataArray[$cle]);
            }
            else{
                exit_with_message("Unauthorized key : ".$cle, 403);
            }
        }

        exit_with_content($userRepo->getUser($idUserUpdate));

    }

    public function getUserByEmail($email) {

        $userRepository = new UserRepository();
        return $userRepository->getUserEmail($email);

    }

    public function updateUserValidate($id_user, $id_index){
        $userRepository = new UserRepository();
        $userRepository->updateUserValidate($id_user, $id_index);
        exit_with_content($this->getUserById($id_user));
    }


    /*
     *  Supprime un utilisateur
    */

    
    public function deleteUserById($id, $apiKey) {
        $userRepository = new UserRepository();
        if ($userRepository->unreferenceUserById($id, $apiKey)){
            exit_with_message("Unreference Succeed !", 200);
        }
        else{
            exit_with_message("Error when unreferencing user ".$id);
        }
    }


    public function deleteUserByApikey($apiKey) {
        $userRepository = new UserRepository();
        if ($userRepository->unreferenceUserByApikey($apiKey)){
            exit_with_message("Unreference Succeed !", 200);
        }
        else{
            exit_with_message("Error when unreferencing user ".$apiKey);
        }
    }

    public function createdispoUser($id_dispo ,$apikey)
    {
        $id= getIdUserFromApiKey($apikey);

         for ($i = 0; $i <count($id_dispo) ; $i++) {
            if ($id_dispo[$i] >8) {
                exit_with_message("mauvais selection", 403);
            }
         }


        $userRepository = new UserRepository();
        return $userRepository->dispoUser($id_dispo, $id);
    }

    public function getAllDispoUsers($apiKey)
    {
        $role = getRoleFromApiKey($apiKey);

        if ($role>3){
            exit_with_message("vous n'avez pas le droit update ", 403);
        }
        $userRepository = new UserRepository();
        return $userRepository->getAllDispoUsers();
    }

    public function updatedispoUser($apiKey, $id_dispo)
    {
        $id= getIdUserFromApiKey($apiKey);

        for ($i = 0; $i <count($id_dispo) ; $i++) {
            if ($id_dispo[$i] >8) {
                exit_with_message("mauvais selection", 403);
            }
        }
        $userRepository = new UserRepository();
        return $userRepository->updatedispoUser($id_dispo, $id);
    }

    public function updateentrepotUser($apiKey, $id_entrepot)
    {
        $role = getRoleFromApiKey($apiKey);
        if ($role>3){
            exit_with_message("vous n'avez pas le droit update ", 403);
        }

        $check=selectDB("ENTREPOTS", "*", "id_entrepot = ".$id_entrepot);
        if (!$check){
            exit_with_message("Entrepot not found", 403);
        }

        $id= getIdUserFromApiKey($apiKey);

        $userRepository = new UserRepository();
        return $userRepository->updateentrepotUser($id_entrepot, $id);
    }

    public function getDispoUserMe($apiKey)
    {
        $role = getRoleFromApiKey($apiKey);
        if ($role!=3){
            exit_with_message("vous n'avez pas de disposability");
        }
        $id= getIdUserFromApiKey($apiKey);
        $res=selectDB("DISPONIBILITE", "*", "id_user = ".$id,"bool");
        if (!$res){
            exit_with_message("error getting dispo user ");
        }
        $userRepository = new UserRepository();
        return $userRepository->getDispoUserMe($id);
    }

    //----------------------------------------------------------------------

    public function getDispoUserById($apiKey,$id)
    {
        $role = getRoleFromApiKey($apiKey);
        if ($role>3){
            exit_with_message("vous n'avez pas de disposability");
        }

        $res=selectDB("DISPONIBILITE", "*", "id_user = ".$id,"bool");
        if (!$res){
            exit_with_message("error getting dispo user ");
        }
        $userRepository = new UserRepository();
        return $userRepository->getDispoUserMe($id);
    }

    //-------------------------------------------------------------------------------------

    public function updateRoleUser($apiKey, $role, $id)
    {
        $role_check = getRoleFromApiKey($apiKey);
        if ($role_check>2){
            exit_with_message("Vous n'avez pas de le droit");
        }



        if ($role >5){
            exit_with_message("Mauvaise selection", 403);
        }

        $userRepository = new UserRepository();
        $userRepository->updateRoleUser($role,$id);

    }

    //--------------------------------------------------------------------------

    public function GetAllUserDate($apiKey,$id_jour,$date)
    {

        $role = getRoleFromApiKey($apiKey);
        if ($role>3){
            exit_with_message("vous n'avez pas de disposability");
        }

        $res=selectDB("SEMAINE", "*", "id_dispo = ".$id_jour,"bool");
        if (!$res){
            exit_with_message("error getting dispo ");
        }
        $userRepository = new UserRepository();
        return $userRepository->GetAllUserDate($id_jour,$date);
    }

}
?>