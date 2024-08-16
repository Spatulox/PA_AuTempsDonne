<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["template"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <section class="flex flexCenter wrap">
        <h1 class="width100 textCenter noMarginBottom"><?php echo($data["template"]["sectionTitle"]) ?></h1>
    </section>

    <section>
        <div class="premiumBox">
            Premium 1 Month
            prod_QflheD8zyM1zCN
            id=1
        </div>
        <div class="premiumBox">
            Premium 3 Month
            prod_Qflh88TLcaQ7rM
            id=3
        </div>
        <div class="premiumBox">
            Premium 1 Year
            price_1PoQAfFP4zc2O5WMWbSl8bPa
            id=12
        </div>
    </section>


    <!-- Template de comment utiliser le module stripe créé -->
    <input type="button" value="Payer sur Stripe" onclick="payer()">

    <script src="https://js.stripe.com/v3/" data-js-isolation="on"></script>



</main>

<?php include("../includes/footer.php");?>

</body>
</html>



<script type="text/javascript">
    async function payer() {
        const stripe = new GestionStripe()
        stripe.startStripePaymentUseThisOne([12], ["test"], {"subject":"MailObject", "htmlString":`MailBody`}, "optionalReturnPath")
    }
</script>