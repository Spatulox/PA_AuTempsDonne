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

    public function createUser(UserModel $user, $password) {
        $userRepository = new UserRepository();
        return $userRepository->createUser($user, $password);
    }

    
    /*
     *  Met à jour un utilisateur
    */

    
    public function updateUser($apiKey, $dataArray){
        
        if (!is_array($dataArray) && empty($dataArray)){
            exit_with_message("Not an array / json data", 500);
        }

        $id_user = getIdUserFromApiKey($apiKey);
        $cles = array_keys($dataArray);
        $userRepo = new UserRepository;

        foreach ($cles as $cle) {
            if (!empty($dataArray[$cle]) && ( $cle != "role" && $cle != "mdp" && $cle != "type" ) ) {

                $userRepo->updateUser($id_user, $cle, $dataArray[$cle]);
            }
            else{
                exit_with_message("Unauthorized key : ".$cle, 403);
            }
        }

        exit_with_content($userRepo->getUserApi($apiKey));

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

    public function dispoUser($id_dispo ,$apikey)
    {
        $id= getIdUserFromApiKey($apikey);

         for ($i = 0; $i <count($id_dispo) ; $i++) {
            if ($id_dispo[$i] >8) {
                exit_with_message("mauvais selection", 200);
            }
         }


        $userRepository = new UserRepository();
        return $userRepository->dispoUser($id_dispo, $id);
    }


}
?>
