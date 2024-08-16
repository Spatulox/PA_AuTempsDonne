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
    public function startPayement($amounts, $names, $mode = "payment", $returnPath, $mailMetadata, $userid)
    {

        $json_file = file_get_contents('/var/www/html/env.json');
        $data = json_decode($json_file, true);

        $leReturn = "moncompte.php";
        if($returnPath != null){
            $leReturn = $returnPath;
        }

        if(!is_array($amounts) || !is_array($names) || empty($amounts) || empty($names)){
            exit_with_message("Amount and object of the transaction need to be an array of value, and not empty");
        }

        $total = "";
        $allName = "";
        $line_items = [];

        try {
            if($mode == "payment"){
                foreach ($amounts as $index => $amount) {
                    $total .= $amount.",";
                    $allName .= $names[$index].",";
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

                $session = Session::create([
                        'line_items' => $line_items,
                        'mode' => $mode,
                        'success_url' => $data["apiAddress"]."paymentsuccess.php?amount=".$total . "?name=".$allName . ($returnPath != null ? "?return_path=".$returnPath : "") . "?subject=" . $mailMetadata['subject'] . "?htmlString=" . $mailMetadata['htmlString'],
                        'cancel_url' => $data["apiAddress"]."paymentfailed.php?amount=".$total . "?name=".$allName . ($returnPath != null ? "?return_path=".$returnPath : ""),
                        'billing_address_collection' => 'required',
                        'metadata' => [
                            'userID' => $userid
                        ],
                        'payment_intent_data' => [
                            'metadata' => [
                                'userID' => $userid
                            ]
                        ],
                    ]

                );

                // Retourner l'URL de la session et l'id
                exit_with_content([
                    'sessionId' => $session->id,
                    'url' => $session->url
                ]);
            } else if($mode == "subscription"){

                $dataTmp = [
                    "price_1PoQ9wFP4zc2O5WMdRpyuFaq" => "1",
                    "price_1PoQAHFP4zc2O5WMetg7EbRx" => "3",
                    "price_1PoQAfFP4zc2O5WMWbSl8bPa" => "12"
                ];

                $line_items[] = [
                    [
                        'price' => $amounts[0],
                        'quantity' => 1,
                    ],
                ];

                $session = Session::create([
                        'line_items' => $line_items,
                        'mode' => $mode,
                        'success_url' => $data["apiAddress"]."paymentsuccess.php?amount=".$total . "?name=".$allName . ($returnPath != null ? "?return_path=".$returnPath : "") . "?subject=" . $mailMetadata['subject'] . "?htmlString=" . $mailMetadata['htmlString'] . "?subscription=" . $dataTmp[$amounts[0]],
                        'cancel_url' => $data["apiAddress"]."paymentfailed.php?amount=".$total . "?name=".$allName . ($returnPath != null ? "?return_path=".$returnPath : ""),
                        'billing_address_collection' => 'required',
                        'metadata' => [
                            'userID' => $userid
                        ],
                        'subscription_data' => [
                            'metadata' => [
                                'userID' => $userid
                            ]
                        ]
                    ]

                );

                // Retourner l'URL de la session et l'id
                exit_with_content([
                    'sessionId' => $session->id,
                    'url' => $session->url
                ]);
            }

        } catch (Exception $e) {
            exit_with_message($e->getMessage());
        }

    }

    /*
     * Can't test it, cause the server is in localhost
     */
    public function addSubscriptionToBdd($payload, $sig_header){

        $endpoint_secret = 'whsec_gZdWLby1AN7fO7XLYjjmlXoTm1N3jkUB';

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Gérer l'événement
        if ($event['type'] === 'checkout.session.completed') {
            $session = $event['data']['object'];

            if (isset($session['subscription'])) {
                $subscriptionId = $session['subscription'];
                $metadata = $session['metadata'];
                $userId = $metadata['userID'] ?? null;

                updateDB("UTILISATEUR", ["premium_stripe_id"], [$subscriptionId], "id_user=".$userId);
            }
        }

        // Répondre à Stripe
        http_response_code(200);

    }

    /*
     * Can't test it, cause the server is in localhost
     */
    function stopSubscription($userId){

        $id = selectDB("UTILISATEUR", "premium_stripe_id", "id_user=".$userId);

        $subscription = \Stripe\Subscription::retrieve($id);
        $subscription->cancel();

    }
}


?>