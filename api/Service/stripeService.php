<?php

include_once('./stripe/stripe-php-15.6.0/init.php');
include_once('./stripe/StripePayement.php');

class stripeService
{
    private $stripeSecretKey;

    public function __construct()
    {
        $json_file = file_get_contents('/var/www/html/env.json');

        // Décoder le contenu JSON en un tableau PHP
        $data = json_decode($json_file, true);

        $this->stripeSecretKey = $data['STRIPE_SECRET'];
    }

    function startPayment($amount, $name, $returnPath, $mailMetadata, $userid){
        $payement = new StripePayement($this->stripeSecretKey);
        $payement->startPayement($amount, $name, "payment", $returnPath, $mailMetadata, $userid);
    }


    function startSubscription($amount, $name, $returnPath, $mailMetadata, $userid){
        $payement = new StripePayement($this->stripeSecretKey);
        $payement->startPayement($amount, $name, "subscription", $returnPath, $mailMetadata, $userid);
    }

    function addSubscriptionToBdd($payload, $sig_header){
        $payement = new StripePayement($this->stripeSecretKey);
        $payement->addSubscriptionToBdd($payload, $sig_header);
    }

    function stopSubscription($userId){
        $payement = new StripePayement($this->stripeSecretKey);
        $payement->stopSubscription($userId);
    }

}