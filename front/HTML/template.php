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

			<?php

			?>


            <!-- Template de comment utiliser le module stripe créé -->
            <input type="button" value="Payer sur Stripe" onclick="payer()">

            <script src="https://js.stripe.com/v3/" data-js-isolation="on"></script>
            <script type="text/javascript">
                async function payer() {
                    const stripe = new GestionStripe()
                    stripe.startStripeUseThisOne([12], ["test"])
                }
            </script>



		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>

<style>	/*Balise à ne pas copier hein -_-*/

/*Modèle préfait pour connaitre les variables existante lors de la création d'un nouveau CSS*/

/* Variables  */

.Light{
	--colorHFhover : #f7a300;	/*Couleur Header/Footer lors du hover 								=> Ne pas réutiliser*/
	--colorText: whitesmoke;	/*Couleur du texte whitesmoke*/
	--colorHF: #FFB72D;			/*Couleur des backgrounds Header/Footer 							=> Ne pas réutiliser*/
	--colorNav: #FFB72D;		/*Couleur de la navigation 											=> Ne pas réutiliser*/
	--colorInput: whitesmoke;	/*Couleur des inputs (et fieldset) dans connexion et inscription*/
	--colorBoxs: #FFDEAD;		/*Couleur des boites												=> Ne pas réutiliser*/
	--colorMain: #E6E6E6;		/*Couleur du main 													=> Ne pas réutiliser*/
}

.Dark{
	--colorHFhover : #0c2124;
	--colorText: whitesmoke;
	--colorHF: rgb(19, 50, 55);
	--colorNav: rgb(19, 50, 55);
	--colorInput : grey;
	--colorBoxs: #6B6966;
	--colorMain: #1D1D1D;
}

/* Balises  */



/* Classes */




</style>