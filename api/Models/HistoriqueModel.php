<?php

class HistoriqueModel
{
    public $id_historique;
    public $description_hist;
    public $heure_historisation;
    public $id_secteur;
    public $id_user;
    public $secteur;

    public function __construct($id_historique, $description_hist, $heure_historisation, $id_secteur,$id_user,$secteur) {
        $this->id_historique = $id_historique;
        $this->description_hist = $description_hist;
        $this->heure_historisation = $heure_historisation;
        $this->id_secteur = $id_secteur;
        $this->id_user =$id_user;
        $this->secteur =$secteur;
    }

}