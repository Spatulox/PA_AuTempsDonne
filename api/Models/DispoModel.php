<?php

class DispoModel
{
    public $id_user;
    public $dispos;


    public function __construct($id_user, $dispos)
    {
        $this->id_user = $id_user;
        $this->dispos = $dispos;
    }


}