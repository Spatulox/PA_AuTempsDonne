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

    function getProduitAll(){
        $produitRepository = new ProduitRepository();
        $produitRepository->getAllProduit();
    }

    function createProduit($nom_produit, $type){
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