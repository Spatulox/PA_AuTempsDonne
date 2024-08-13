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


    <input type="button" value="Payer sur Stripe" onclick="payer()">
    <?php

    ?>
    <script src="https://js.stripe.com/v3/" data-js-isolation="on"></script>
    <script type="text/javascript">
        async function payer() {
            const stripe = new GestionStripe()
            stripe.startStripeUseThisOne([12, 24], ["test", "porc-epic"])
        }
    </script>


</main>

<?php include("../includes/footer.php");?>

</body>
</html>