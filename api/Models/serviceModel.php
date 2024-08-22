<?php

class ServiceModel
{
    public $id_service;
    public $description;
    public $type_service;
    public $date_debut;
    public $date_fin;
    public $user;

    public function __construct($id_service, $description, $type_service, $date_debut, $date_fin)
    {
        $this->id_service = $id_service;
        $this->description = $description;
        $this->type_service = $type_service;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

    public function addUser(UserModel $user){
        $this->user = $user;
    }
}