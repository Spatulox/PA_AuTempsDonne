<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["contact"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main class="flex flexCenter alignCenter nowrap">

			<section class="width50 border">
				<h1 class="textCenter underline bold"><?php echo($data["contact"]["sectionTitle"]) ?></h1>
				<hr>
				<ul class="noDecoration textCenter noPadding">
					<?php

						foreach ($data["contact"]["li"] as $key => $value) {
							echo('<li class="marginTop10 pointer padding10">' . $value . '</li>');
						}
					?>
				</ul>

			</section>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>
