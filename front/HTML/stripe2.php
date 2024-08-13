<?php

require_once('../stripe/stripe-php-15.6.0/init.php');
require_once('../stripe/StripePayement.php');

//require_once('../vendor/autoload.php');

// Public :
// pk_test_51PmzntFP4zc2O5WMI4vIV6PJuCwgI98zC5bvSjUoBFkBjQ32vONiDvEw4wWezAbeNYiIVI4MuF52OiI5v7xzmTBf00KmHRrRPt

// Private :
// sk_test_51PmzntFP4zc2O5WMKGFGEQ4tO5uzrr8BYgGUDHMUZnzdSOpO0Cpc4PJ3F0hQXJ8ORnY2i1VZSEGmvdnqTtexxLSa005Qm34kR7

$payement = new StripePayementFront("sk_test_51PmzntFP4zc2O5WMKGFGEQ4tO5uzrr8BYgGUDHMUZnzdSOpO0Cpc4PJ3F0hQXJ8ORnY2i1VZSEGmvdnqTtexxLSa005Qm34kR7");


/*if(!isset($_POST['amount']) || empty($_POST['amount']) || !isset($_POST['name']) || empty($_POST['name'])) {

    $message = "?message=Error, le montant et le nom sont obligatoire";
    header('Location: actualites.php' . $message);
    exit();
}*/

$payement->startPayementFront(12000, "Trucs");
?>