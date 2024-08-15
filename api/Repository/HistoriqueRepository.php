<?php

class HistoriqueRepository
{

    public function __construct()
    {
    }

    //-------------------------------------------------------------------------------------------------------------------------------

    function getAllHistorique()
    {
        $request = selectDB("HISTORIQUE", "*", "-1", "bool");
        if(!$request){
            exit_with_message("Nothing to show", 200);
        }

        $array = [];

        for ($i = 0; $i<count($request);$i++){

            $secteur = selectDB("id_secteur", "*", "id_secteur=".$request[$i]["id_secteur"])[0]["secteur"];
            $model = new HistoriqueModel($request[$i]["id_historique"], $request[$i]["description_hist"],
                $request[$i]["heure_historisation"],$request[$i]["id_secteur"],$request[$i]["id_user"], $secteur);
            $array[$i] = $model;
        }

        exit_with_content($array);
    }

    //-------------------------------------------------------------------------------------------------------------------------------

    function createHistorique($description_hist, $id_secteur,$id_user)
    {

        $res= insertDB("HISTORIQUE", ["description_hist","heure_historisation","id_secteur", "id_user"], [$description_hist, getdate() ,$id_secteur,$id_user]);

        if($res==true) {
            exit_with_message("HISTORIQUE has been created", 200);
        }else{
            exit_with_message("HISTORIQUE not created", 500);

        }


    }


//--------------------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------------------


    function createSecteur($secteur)
    {

        $res= insertDB("SECTEUR", ["nom_secteur"], [$secteur]);

        if($res==true) {
            exit_with_message("secteur has been created", 200);
        }else{
            exit_with_message("secteur not created", 500);

        }


    }

}