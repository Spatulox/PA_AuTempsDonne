<?php include("../includes/loadLang.php");?>

<?php
$dossier = '../Images/background_images/';

// Lire le contenu du dossier
$contenu = scandir($dossier);

$contenu = array_slice($contenu, 2);

$img = $contenu[rand(0, count($contenu)-1)]
?>



<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["actualites"]["title"]) ?></title>
	</head>
	<body class="">

		<?php include("../includes/header.php");?>

		<main style="background-image: url('../Images/background_images/<?php echo($img) ?>');" id="backgroundFixed" class="alternateSection">

			<section class="flex flexCenter wrap">
				<h1 class="width100 textCenter noMarginBottom"><?php echo($data["actualites"]["sectionTitle"]) ?></h1>
				<div class="width100">
					<input class="block marginAuto" type="button" name="becomePatreon" value="<?php echo($data["actualites"]["button"]) ?>"  onclick="redirect()">
				</div>
			</section>

			<section ><!--id="article">-->
				<p class="border marginAuto marginTop30 marginBottom30 width80">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</section>

			<section ><!--id="article">-->
				<p class="border marginAuto marginTop30 marginBottom30 width80">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</section>

			<section ><!--id="article">-->
				<p class="border marginAuto marginTop30 marginBottom30 width80">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</section>

			<section ><!--id="article">-->
				<p class="border marginAuto marginTop30 marginBottom30 width80">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</section>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>

<script type="text/javascript" defer>

	function redirect(){
		window.location.href = "<?php echo($data["actualites"]["buttonLink"]) ?>";
	}
	
</script>