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

    // Create th Checkout on stripe to the user to pay
    // Send the result to a JS for the js to "redirect"
    public function startPayement($amounts, $names, $mode = "payment")
    {
        foreach ($amounts as $index => $amount) {
            $line_items[] = [
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $names[$index]
                    ],
                    'unit_amount' => $amount * 100, // Conversion en centimes
                ]
            ];
        }

        try {
            $session = Session::create([
                    'line_items' => [$line_items],
                    'mode' => $mode,
                    'success_url' => "http://localhost:8083/HTML/moncompte.php?message=Votre payement a ete realise avec succes",
                    'cancel_url' => "http://localhost:8083/HTML/moncompte.php?message=Votre payement n'a pas abouti",
                    'billing_address_collection' => 'required',
                    'metadata' => [
                        'cart_id' => 1
                    ]
                ]

            );

            // Retourner l'URL de la session et l'id
            exit_with_content([
                'sessionId' => $session->id,
                'url' => $session->url
            ]);
        } catch (Exception $e) {
            exit_with_message($e->getMessage());
        }
    }
}


?>