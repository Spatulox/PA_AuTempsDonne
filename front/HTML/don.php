<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>
    <script src="https://js.stripe.com/v3/" data-js-isolation="on"></script>

    <title><?php echo($data["don"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <section class="flex flexCenter wrap">
        <h1 class="width100 textCenter noMarginBottom"><?php echo($data["don"]["title"]) ?></h1>
    </section>

    <div class="border marginTop40 textCenter">

        <input class="search-box" type="number" id="prix" placeholder="<?php echo($data["don"]["montant"]) ?>"><br>
        <input class="marginTop20" type="button" onclick="createDon()" value="<?php echo($data["don"]["give"]) ?>">

    </div>

</main>

<?php include("../includes/footer.php"); ?>

</body>
</html>


<script type="text/javascript" defer>

    async function createDon() {

        startLoading()
        const prix = document.getElementById("prix")

        if(prix.value === ""){
            popup("You need to specify the amount, nothing was retrieve from your account")
            stopLoading()
            return
        }


        /* GESTION DON WITH STRIPE */
        const stripe = new GestionStripe()
        stripe.startStripeUseThisOne([prix.value], ["Don"], "don.php")


        /* GESTION DON WITH LOCAL DB */
        /*let apikey = getCookie("apikey")

        const data = {
            "prix":prix.value,
            "date":today()
        }

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                "apikey" : apikey
            },
            body: JSON.stringify(data)
        };

        const response = await fetch("http://localhost:8081/index.php/don/create", options)

        if (!response.ok) {
            const text = await response.json()
            if (text.hasOwnProperty("message")) {
                popup(text.message)
            }
            stopLoading()
            return false
        }

        const message = await response.json()
        if (message.hasOwnProperty("message")) {
            popup(message.message)
            stopLoading()
            return true
        }*/
    }

</script>

