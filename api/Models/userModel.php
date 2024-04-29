<?php

//include_once './Repository/BDD.php';

class UserModel {
    public $id_user;
    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $date_inscription;
    public $id_role;
    public $apikey;
    public $id_index;
    public $id_entrepot;

    public function __construct($id_user, $nom, $prenom, $date_inscription, $email, $telephone = null, $id_role = null, $apikey = null, $id_index = 3, $id_entrepot = 1) {
        $this->id_user = $id_user;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->date_inscription = $date_inscription;
        $this->id_role = $id_role;
        $this->apikey = $apikey;
        $this->id_index = $id_index;
        $this->id_entrepot = $id_entrepot;
    }
}

?>
