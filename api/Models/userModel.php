<?php

include_once './Repository/BDD.php';

class UserModel {
    public $id_user;
    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $date_inscription;
    public $type;
    public $role;
    public $apikey;
    public $index;

    public function __construct($id_user, $nom, $prenom, $date_inscription, $email = null, $telephone = null, $type = null, $role = null, $apikey = null, $index = null) {
        $this->id_user = $id_user;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->date_inscription = $date_inscription;
        $this->type = $type;
        $this->role = $role;
        $this->apikey = $apikey;
        $this->index = $index;
    }
}

?>
