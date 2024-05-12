<?php

include_once './Repository/adresseRepository.php';

class adresseService {

    public function getAllAdresse() {
        $AdresseRepository = new AdresseRepository();
        return $AdresseRepository->getAllAdresse();
    }

    public function getAdresseById($id) {
        $AdresseRepository = new AdresseRepository();
        return $AdresseRepository->getAdresseById($id);
    }

    public function CreateAdresse($address)
    {
        $AdresseRepository = new AdresseRepository();
        exit_with_content($AdresseRepository->CreateAdresse($address));

    }

    public function DeleteAdresse($id){
        $AdresseRepository = new AdresseRepository();
        $AdresseRepository->DeleteAdresse($id);
    }
}

?>