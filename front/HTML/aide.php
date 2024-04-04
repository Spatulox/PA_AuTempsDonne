<?php
$dossier = '../Images/background_images/';

// Lire le contenu du dossier
$contenu = scandir($dossier);

$contenu = array_slice($contenu, 2);

$img = $contenu[rand(0, count($contenu)-1)];

?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["aide"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main style="background-image: url('../Images/background_images/<?php echo($img) ?>');" id="backgroundFixed" class="alternateSection">

			<section class="flex flexCenter wrap">
				<h1 class="width100 textCenter noMarginBottom"><?php echo($data["aide"]["sectionTitle"]) ?></h1>
			</section>

			<?php

				foreach ($data["aide"]["sections"] as $key => $value) {
					echo('<section>
						<p>' . $value["description"] . '</p>
						<input type="button" onclick="loadPage(' . $key . ')" value="' . $value["title"] . '">
						</section>');
				}

			?>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>

<script type="text/javascript" defer>
	
	function loadPage(role){
		window.location.href = `./signup_login.php?signup=true?role=${role}`;
	}

</script>
