<?php include("../includes/loadLang.php");?>

<?php include("../includes/checkRights.php");?>

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

            <div id="ticket">
                <h1><?php echo($data["ticket"]["title"]) ?></h1>
                <ul>
                    <li><a style="color: #0c2124" href="<?php echo($address["ticket"]) ?>/list?me=true" target="_blank"><?php echo($data["ticket"]["me"]) ?></a></li>
                    <li><a style="color: #0c2124" href="<?php echo($address["ticket"]) ?>/list?assign=false" target="_blank"><?php echo($data["ticket"]["assignFalse"]) ?></a></li>
                    <li><a style="color: #0c2124" href="<?php echo($address["ticket"]) ?>/list" target="_blank"><?php echo($data["ticket"]["all"]) ?></a></li>
                </ul>
            </div>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>


