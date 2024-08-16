<?php include("../includes/loadLang.php");?>


<!DOCTYPE html>
<html lang="fr">
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["template"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main style="background-color: #e1edf9;">
    <div class="containerPremium">
        <div class="product-box" onclick="payerPremium(event)">
            <h2>Premium 1 Month</h2>
            <p>Description du produit premium 1.</p>
            <input type="hidden" name="id" value="price_1PoQ9wFP4zc2O5WMdRpyuFaq">
            <input type="hidden" name="time" value="1">
        </div>
        <div class="product-box" onclick="payerPremium(event)">
            <h2>Premium 3 Month</h2>
            <p>Description du produit premium 2.</p>
            <input type="hidden" name="id" value="price_1PoQAHFP4zc2O5WMetg7EbRx">
            <input type="hidden" name="time" value="3">
        </div>
        <div class="product-box" onclick="payerPremium(event)">
            <h2>Premium 1 Year</h2>
            <p>Description du produit premium 3.</p>
            <input type="hidden" name="id" value="price_1PoQAfFP4zc2O5WMWbSl8bPa">
            <input type="hidden" name="time" value="12">
        </div>
    </div>
    <script src="https://js.stripe.com/v3/" data-js-isolation="on"></script>

</main>

<?php include("../includes/footer.php");?>

</body>
</html>

<script type="text/javascript">

    function payerPremium(event){


        const htmlString = `<h1>Nous vous remercions pour votre confiance</h1>
        <h2>Vous avez souscrit a loffre "${event.target.children[0].innerHTML}"</h2>
        <p>Chaque ${new Date().getDay()} du mois tous les ${event.target.children[3].value} mois votre premium vous sera facture</p>`
        
        const stripe = new GestionStripe()
        stripe.startStripeSubscriptionUseThisOne([event.target.children[2].value], [event.target.children[0].innerHTML], {"subject":event.target.children[0].innerHTML, "htmlString":htmlString}, "moncompte.php")
    }
</script>

<style>

    .containerPremium {
        width: 90vw;
        margin: auto;
        display: flex;
        justify-content: space-evenly;

        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        /*animation: fadeIn 1s forwards;*/
    }

    .product-box {
        background-color: white;
        border-radius: 15px; /* Coins arrondis */
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Ombre légère */
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transition pour l'animation */
    }

    .product-box:hover {
        transform: scale(1.05); /* Grossissement au survol */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Ombre plus prononcée au survol */
        cursor: pointer;
    }

    .product-box > h2, .product-box > p{
        pointer-events: none;
    }

    @keyframes fadeIn {
        to {
            opacity: 1; /* Rendre visible */
        }
    }

</style>



<script type="text/javascript">
    async function payer() {
        const stripe = new GestionStripe()
        stripe.startStripePaymentUseThisOne([12], ["test"], {"subject":"MailObject", "htmlString":`MailBody`}, "optionalReturnPath")
    }
</script>