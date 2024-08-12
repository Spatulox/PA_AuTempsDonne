<?php

use \Stripe\Stripe;
use Stripe\Checkout\Session;
class StripePayement
{


    private string $clientToken;

    public function __construct(string $clientToken){
        $this->clientToken = $clientToken;
        Stripe::setApiKey($this->clientToken);
        Stripe::setApiVersion('2024-06-20');
    }

    public function startPayement($amount, $name){
        $session = Session::create([
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $name
                    ],
                    'unit_amount' => $amount*100, // Faut le mettre en centimes
                ]
            ]],
            'mode' => 'payment',
            'success_url' => "http://localhost:8083/HTML/accueil.php?message=Votre payement a été réalisé avec succès",
            'cancel_url' => "http://localhost:8083/HTML/accueil.php?message=Votre payement n'a pas abouti",
            'billing_address_collection' => 'required',
            'metadata' => [
                'cart_id' => 1
            ]
        ]

    );

        header("HTTP/1.1 303 See Other");
        header('Location: ' . $session->url);
    }
}


?>