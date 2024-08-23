<?php

//include_once './Repository/BDD.php';

class MiniUserModel {
    public $id_user;
    public $email;
    public $telephone;
    public $id_role;

    public function __construct($id_user, $email, $telephone, $id_role) {
        $this->id_user = $id_user;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->id_role = $id_role;
    }
}

?>
