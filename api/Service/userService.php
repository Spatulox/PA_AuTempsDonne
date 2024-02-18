<?php
include_once './Repository/userRepository.php'; 
include_once './Models/userModel.php';

class UserService {
    
    public $uri;

    // public function __construct($uri)
    // {       
    //     $this->uri = $uri;
    // }

    /*
     *  Récupère tous les utilisateurs
    */
    public function getAllUsers() {
        $userRepository = new UserRepository();
        return $userRepository->getUsers();
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
        return $userRepository->getUser($id, $apiKey);
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

    /*
    public function updateUser($id_users, $apiKey, $role, $pseudo, $user_index) {
        $userRepository = new UserRepository();
        $newUser = new UserModel($id_users, $role, $pseudo, '1234', $user_index, null);
        return $userRepository->updateUser($newUser, $apiKey);
    }


    /*
     *  Supprime un utilisateur
    */

    
    public function deleteUser($id, $apiKey) {
        $userRepository = new UserRepository();
        if ($userRepository->unreferenceUser($id, $apiKey)){
            exit_with_message("Unreference Succeed !", 200);
        }
    }
    
    
    
}
?>
