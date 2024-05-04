<?php

class DonModels
{
    public $id_don;
    public $prix;
    public $date_don;
    public $id_user;

    public function __construct($id_don, $prix, $date_don, $id_user)
    {
        $this->id_don = $id_don;
        $this->prix = $prix;
        $this->date_don = $date_don;
        $this->id_user = $id_user;
    }
    
}
?>