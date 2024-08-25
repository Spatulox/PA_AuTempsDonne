<?php
include_once './Repository/fichierRepository.php';

class fichierService
{
    public function __construct()
    {

    }

    public function registerFile($pathPlusName, $id_user){
        $path = explode("/" ,$pathPlusName);

        $name = end($path);

        array_pop($path);
        $path = implode("/", $path) . "/";

        $repo = new FichierRepository();
        return $repo->registerFile($id_user, $path, $name);

    }

}