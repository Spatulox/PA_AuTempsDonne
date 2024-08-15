<?php

include_once("./Repository/produitRepository.php");

class ProduitService
{
    public function __construct()
    {
    }

    function getProduitByID($id,$apikey){


        $produitRepository = new ProduitRepository();
        $produitRepository->getProduitId($id,$apikey);
    }

    function getProduitAll($apikey){
        $produitRepository = new ProduitRepository();
        $produitRepository->getAllProduit($apikey);
    }

    function createProduit($nom_produit, $type,$apikey){
        $check=selectDB("TYPE","*","id_type=".$type);
        if (!$check)
        {
            exit_with_message("ce type nexiste pas",400);
        }

        $produitRepository = new ProduitRepository();
        $produitRepository->createProduit($nom_produit, $type,$apikey);
    }

    function deleteProduit($id,$apikey){
        $produitRepository = new ProduitRepository();
        $produitRepository->deleteProduitId($id,$apikey);
    }

    public function getType()
    {
        $produitRepository = new ProduitRepository();
        $produitRepository->getType();
    }
}