<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["don"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main>

			<section class="flex flexCenter wrap">
				<h1 class="width100 textCenter noMarginBottom"><?php echo($data["don"]["title"]) ?></h1>
			</section>

            <div class="border marginTop40 textCenter">

                <input class="search-box" type="number" placeholder="<?php echo($data["don"]["montant"]) ?>"><br>
                <input class="marginTop20" type="button" value="<?php echo($data["don"]["give"]) ?>">

            </div>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>


<script type="text/javascript" defer>


    /*
    const response = await fetch("http://localhost:8081/index.php/user", options)

        if (!response.ok) {
            const text = await response.json()
            alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
            if (text.hasOwnProperty("message")) {
                alertDebug(text.message)
                popup(text.message)
            }
            return false
        }

        const message = await response.json()
        if (message.hasOwnProperty("message")) {
            popup(message.message)
            return true
        }
     */

    async function onload(){
        //startLoading()
        //stopLoading()
    }

    onload()

</script>

