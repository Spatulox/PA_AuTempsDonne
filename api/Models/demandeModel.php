<?php

class DemandeModel {

    public $id_demande;
    public $desc_demande;
    public $id_planning;
    public $id_user;
    public $collecte;



    public function __construct($id_demande, $desc_demande, $id_planning, $id_user, $collecte)
    {
        $this->id_demande = $id_demande;
        $this->desc_demande = $desc_demande;
        $this->id_planning = $id_planning;
        $this->id_user = $id_user;
        $this->collecte = $collecte; 
    }
}
