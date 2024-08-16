<?php include("../includes/loadLang.php");?>


<!DOCTYPE html>
<html lang="fr">
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["premium"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main style="background-color: #e1edf9;">
    <div class="containerPremium">
        <div class="product-box" onclick="payerPremium(event)">
            <h2>Premium 1 Month</h2>
            <ul>
                <li>10 €</li>
            </ul>
            <p>Vous serez facturé tous les <?php echo (new DateTime())->format('d'); ?> du mois, à partir d'aujourd'hui</p>
            <input type="hidden" name="id" value="price_1PoQ9wFP4zc2O5WMdRpyuFaq">
            <input type="hidden" name="time" value="1">
        </div>
        <div class="product-box gold-border-box border-animation" onclick="payerPremium(event)">
            <h2>Premium 3 Month</h2>
            <ul>
                <li>27 €</li>
                <li>1€ de réduction chaque mois</li>
            </ul>
            <p>Vous serez facturé tous les <?php echo (new DateTime())->format('d'); ?> du mois, à partir d'aujourd'hui</p>
            <input type="hidden" name="id" value="price_1PoQAHFP4zc2O5WMetg7EbRx">
            <input type="hidden" name="time" value="3">
        </div>
        <div class="product-box" onclick="payerPremium(event)">
            <h2>Premium 1 Year</h2>
            <ul>
                <li>100 €</li>
                <li>2 mois gratuits</li>
            </ul>
            <p>Vous serez facturé tous les <?php echo (new DateTime())->format('d'); ?> du mois, à partir d'aujourd'hui</p>
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
        <br>
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
    }

    .product-box {
        background-color: white;
        border-radius: 15px; /* Coins arrondis */
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Ombre légère */
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transition pour l'animation */
        max-width: 16%;
    }

    .product-box:hover {
        transform: scale(1.05); /* Grossissement au survol */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Ombre plus prononcée au survol */
        cursor: pointer;
    }

    .product-box > h2, .product-box > p{
        pointer-events: none;
    }


    .gold-border-box {
        border: 5px solid gold; /* Définit la largeur et la couleur du contour */
        border-radius: 10px;    /* Optionnel : arrondir les coins */
        padding: 20px;          /* Espacement interne */
        background-color: white; /* Couleur de fond de la boîte */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Ombre légère */
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