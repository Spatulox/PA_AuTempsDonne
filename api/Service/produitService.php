<?php

include_once("./Repository/produitRepository.php");

class ProduitService
{
    public function __construct()
    {
    }

    function getProduitByID($id){


        $produitRepository = new ProduitRepository();
        $produitRepository->getProduitId($id);
    }

    function getProduitAll($apikey){
        $produitRepository = new ProduitRepository();
        $produitRepository->getAllProduit($apikey);
    }

    function createProduit($nom_produit, $type){
        $check=selectDB("TYPE","*","id_type=".$type);
        if (!$check)
        {
            exit_with_message("ce type nexiste pas",400);
        }

        $produitRepository = new ProduitRepository();
        $produitRepository->createProduit($nom_produit, $type);
    }

    function deleteProduit($id){
        $produitRepository = new ProduitRepository();
        $produitRepository->deleteProduitId($id);
    }

    public function getType()
    {
        $produitRepository = new ProduitRepository();
        $produitRepository->getType();
    }
}