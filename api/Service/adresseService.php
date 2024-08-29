<?php

include_once './Repository/adresseRepository.php';

class adresseService {

    public function getAllAdresse($apiKey) {
        $AdresseRepository = new AdresseRepository();
        return $AdresseRepository->getAllAdresse($apiKey);
    }

    public function getAdresseById($id,$apiKey) {
        $AdresseRepository = new AdresseRepository();
        return $AdresseRepository->getAdresseById($id,$apiKey);
    }

    public function CreateAdresse($address,$apiKey)
    {
        $AdresseRepository = new AdresseRepository();
        exit_with_content($AdresseRepository->CreateAdresse($address,$apiKey));

    }

    public function DeleteAdresse($id,$apiKey){
        $AdresseRepository = new AdresseRepository();
        $AdresseRepository->DeleteAdresse($id,$apiKey);
    }
}

?>