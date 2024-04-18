<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<script type="text/javascript" src="../JS/functions.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
<script type="text/javascript">

  let userCookie = Cookies.get('apikey');

  let message = "<?php echo($data["moncompte"]["error"]["errorCo"]) ?>"

  if(message == ""){
  	message="You can't do that"
  }

  if(!userCookie){
  	redirect('./signup_login.php', message)
  }

</script>

<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["moncompte"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main>

			<section class="flex flexCenter wrap">
				<h1 class="width100 textCenter noMarginBottom"><?php echo($data["moncompte"]["sectionTitle"]) ?></h1>
			</section>

			<section class="width80 border marginAuto marginTop30 flex flexAround nowrap">
				
				<div>
					<h2 class="bold underline"> <?php echo($data["moncompte"]["form"]["titleF1"]) ?> </h2>
					<ul class="noDecoration noPadding">
						<li id="c_nom"> Nom : <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
						<li id="c_prenom"> Prénom : <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
						<li id="c_email"> Email : <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
						<li id="c_telephone"> Téléphone : <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
						<li id="c_date_inscription"> Date inscription :  <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
						<li id="c_entrepot"> Entrepot : <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
						<li id="c_role"> Role : <?php echo isset($selectionFromDB) ? $selectionFromDB : "My Informations"; ?> </li>
					</ul>
					<input class="noMarginImportant" type="button" name="planningRedirect" onclick="redirect('./planning.php')" value="<?php echo($data["moncompte"]["form"]["inputPlanning"]) ?>" id="planning">
				</div>

				<div class="form-group">
					<h2 class="bold underline"> <?php echo($data["moncompte"]["form"]["titleF2"]) ?> </h2>
					<ul class="noDecoration noPadding">
						<li id="u_email"><input class="noMarginImportant" type="text" id="u_nom" name="u_nom" placeholder="<?php echo($data["moncompte"]["form"]["placeholderEmail"]) ?>"></li>
						<li id="u_telephone" class="marginTop10"><input class="noMarginImportant" type="text" id="u_nom" name="u_nom" placeholder="<?php echo($data["moncompte"]["form"]["placeholderPhone"]) ?>"></li>
					</ul>
					<input class="noMarginImportant" type="button" name="updateAccount" value="<?php echo($data["moncompte"]["form"]["inputUpdate"]) ?>" id="updateAccount">
				</div>
			</section>
			<?php

			?>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>

<script type="text/javascript" defer>
	myAccount()
</script>