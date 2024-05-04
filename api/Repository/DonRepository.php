<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';
include_once './index.php';

class DonRepository
{
    function __construct() {

    }

    public function getDonById($id)
    {
       $arrayDon= selectDB("DON", "*","id_don=". $id,"-@");
       $don=[];
        for ($i=0; $i < count($arrayDon); $i++) {
            $don[$i] = new DonModels(
                $arrayDon[$i]["id_don"],
                $arrayDon[$i]["prix"],
                $arrayDon[$i]["date_don"],
                $arrayDon[$i]["id_user"],
            );
        }
        return $don;
    }
    //--------------------------------------------------------------------------------
    public function getDonEntrepot()
    {
        $arrayDon= selectDB("DON", "*");
        $don=[];
        for ($i=0; $i < count($arrayDon); $i++) {
            $don[$i] = new DonModels(
                $arrayDon[$i]["id_don"],
                $arrayDon[$i]["prix"],
                $arrayDon[$i]["date_don"],
                $arrayDon[$i]["id_user"],
            );
        }
        return $don;
    }
    //--------------------------------------------------------------------------------
    public function getDonAnnuel($date)
    {
        $string="YEAR(date_don)= ".$date;
        $arrayDon= selectDB("DON", "*",$string,"-@");
        $don=[];
        for ($i=0; $i < count($arrayDon); $i++) {
            $don[$i] = new DonModels(
                $arrayDon[$i]["id_don"],
                $arrayDon[$i]["prix"],
                $arrayDon[$i]["date_don"],
                $arrayDon[$i]["id_user"],
            );
        }
        return $don;

    }
    //--------------------------------------------------------------------------------
    public function getDonMensuel($date)
    {
        
        $parts = explode("-", $date);
        $year = $parts[0];
        $month = $parts[1];

        $string="YEAR(date_don)= ".$year. " AND MONTH(date_don)= ".$month;
        $arrayDon= selectDB("DON", "*",$string,"-@");
        $don=[];
        for ($i=0; $i < count($arrayDon); $i++) {
            $don[$i] = new DonModels(
                $arrayDon[$i]["id_don"],
                $arrayDon[$i]["prix"],
                $arrayDon[$i]["date_don"],
                $arrayDon[$i]["id_user"],
            );
        }
        return $don;
    }
    //--------------------------------------------------------------------------------
}
?>