<?php
class fichierRepository
{
    public function __construct()
    {
    }

    public function registerFile($id_user, $path, $name){
        var_dump("Repo fichier");
        var_dump($id_user);
        var_dump($path);
        var_dump($name);
        $data = insertDB("FICHIER", ["nom_fichier", "chemin_fichier", "id_user"], [$name, $path, $id_user], "-@");

        if($data){
            return true;
        }
        return false;
    }

    public function getFichiers($path, $id_user){
        // to Rework
        $data = selectDB("FICHIER", "*", "chemin_fichier=".$path." AND id_user=".$id_user);
        return $data[0];

    }
}