<?php
include_once './Service/adresseService.php';
include_once './Models/adresseModel.php';
include_once './exceptions.php';

function adresseController($uri, $apiKey){
    switch ($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if($apiKey == null){
                exit_with_message("Unauthorized, need the apikey", 403);
            }

            $AdresseService = new adresseService();
            if(!$uri[3]){
                exit_with_content($AdresseService->getAllAdresse());
            }


            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($AdresseService->getAdresseById($uri[3]));
            }


            else{
                exit_with_message("You need to be admin to see all the trips", 403);
            }

            break;

        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            $AdresseService = new adresseService();

            if($uri[3]=="create"){
                if (!isset($json['address']))
                {
                    exit_with_message("Plz give the address ", 403);
                }
                $check=isValidAddress($json['address']);
                if ($check==false){
                    exit_with_message("Le format d'adresse attendu est le suivant : [Numéro de rue] [Nom de rue], [Code postal] [Ville]. Le code postal doit être composé de 5 chiffres, et le nom de la ville ne doit contenir que des lettres, des espaces et des traits d'union.", 400);
                }

                exit_with_content($AdresseService->CreateAdresse($json['address']));
            }

            break;

        case 'PUT':
            break;

        case 'DELETE':
            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }

    function isValidAddress($address)
    {

        $pattern = '/^(\d+\s+(?:[A-Za-z\s\-\']+(?:\s+[A-Za-z\s\-\']*)?))\s*,\s*(\d{5})\s+([A-Za-z\s\-\']+)$/';


        if (preg_match($pattern, $address, $matches)) {

            $streetAddress = $matches[1];
            $postalCode = $matches[2];
            $city = $matches[3];

            if (strlen($postalCode) !== 5 || !ctype_digit($postalCode)) {
                return false;
            }

            if (!preg_match('/^[A-Za-z\s\-\']+$/', $city)) {
                return false;
            }

            return true;
        }

        return false;
    }
}

?>