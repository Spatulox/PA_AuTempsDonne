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
    public function startPayement($amounts, $names, $mode = "payment", $returnPath, $mailMetadata)
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

        try {
            $session = Session::create([
                    'line_items' => $line_items,
                    'mode' => $mode,
                    'success_url' => $data["apiAddress"]."paymentsuccess.php?amount=".$total . "?name=".$allName . ($returnPath != null ? "?return_path=".$returnPath : "") . "?subject=" . $mailMetadata['subject'] . "?htmlString=" . $mailMetadata['htmlString'],
                    'cancel_url' => $data["apiAddress"]."paymentfailed.php?amount=".$total . "?name=".$allName . ($returnPath != null ? "?return_path=".$returnPath : ""),
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