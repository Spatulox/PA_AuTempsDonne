<?php

class HistoriqueRepository
{

    public function __construct()
    {
    }

    //-------------------------------------------------------------------------------------------------------------------------------

    function getAllHistorique()
    {

        $request = selectJoinDB("HISTORIQUE hist", "*","inner join SECTEUR sec on sec.id_secteur = hist.id_secteur ", "-1");


        if (!$request) {
            exit_with_message("Nothing to show", 200);
        }


        $array = [];


        for ($i = 0; $i < count($request); $i++) {

           $email= getEmailFromIdUser($request[$i]["id_user"]);

            $historique = [
                "id_historique" => $request[$i]["id_historique"],
                "description_hist" => $request[$i]["description_hist"],
                "heure_historisation" => $request[$i]["heure_historisation"],
                "id_secteur" => $request[$i]["id_secteur"],
                "nom_secteur" => $request[$i]["nom_secteur"],
                "email" => $email
            ];

            $array[$i] = $historique;
        }

        usort($array, function($a, $b) {
            return strtotime($b['heure_historisation']) - strtotime($a['heure_historisation']);
        });

        exit_with_content($array);
    }

    //-------------------------------------------------------------------------------------------------------------------------------

    function Createhistorique($description_hist ,$id_secteur ,$id_user)
    {
        insertDB("HISTORIQUE", ["description_hist","heure_historisation","id_secteur", "id_user"], [$description_hist, date('Y-m-d H:i:s') ,$id_secteur,$id_user]);
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