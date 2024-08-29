<?php

class FichierModel
{
    public $name;
    public $path;

    public function __construct($id_fichier, $path = null)
    {
        $this->name = $id_fichier;
        $this->path = $path;
    }

}