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
                    'line_items' => [$line_items/*[
                        'quantity' => 1,
                        'price_data' => [
                            'currency' => 'EUR',
                            'product_data' => [
                                'name' => $name
                            ],
                            'unit_amount' => $amount * 100, // Faut le mettre en centimes
                        ]
                    ]*/],
                    'mode' => $mode,
                    'success_url' => "http://localhost:8083/HTML/moncompte.php?message=Votre payement a ete realise avec succes",
                    'cancel_url' => "http://localhost:8083/HTML/moncompte.php?message=Votre payement n'a pas abouti",
                    'billing_address_collection' => 'required',
                    'metadata' => [
                        'cart_id' => 1
                    ]
                ]

            );

        /*    header("HTTP/1.1 303 See Other");
            header('Location: ' . $session->url);
        } catch (Exception $e) {
            exit_with_message($e->getMessage());
        }*/

        // Retourner l'URL de la session au lieu de rediriger
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