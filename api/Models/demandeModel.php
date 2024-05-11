<?php

class DemandeModel {

    public $id_demande;
    public $desc_demande;
    public $activite;
    public $etat;
    public $date_act;
    public $id_activite;
    public $id_planning;
    public $id_user;
    public $collecte;



    public function __construct($id_demande, $desc_demande,$activite,$etat,$date_act, $id_activite,$id_planning, $id_user, $collecte)
    {
        $this->id_demande = $id_demande;
        $this->desc_demande = $desc_demande;
        $this->activite = $activite;
        $this->etat = $etat;
        $this->date_act = $date_act;
        $this->id_activite = $id_activite;
        $this->id_planning = $id_planning;
        $this->id_user = $id_user;
        $this->collecte = $collecte;
    }
}
