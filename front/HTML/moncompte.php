<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<script type="text/javascript" src="../JS/utils.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>-->
<script type="text/javascript">

  //let userCookie = Cookies.get('apikey');
  let userCookie = getCookie('apikey')

  // PhpStorm detect it as an error, but don't touch plz
  let messagere = "<?php echo($data["moncompte"]["error"]["errorCo"]) ?>"

  if(messagere == ""){
  	messagere="You can't do that"
  }

  if(!userCookie){
  	redirect('./signup_login.php', messagere)
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
                <?php if(!isset($_COOKIE["premiumDate"])){
                    echo '<a href="./premium.php">' . $data["moncompte"]["premium"] .' </a>';
                } else {

                    $startDate = strtotime($_COOKIE["premiumDate"]);

                    // Calcul de la durée en mois en jours (approximativement 30 jours par mois)
                    $durationInDays = $_COOKIE["premiumTime"] * 30;

                    // Calcul de la date d'expiration
                    $expirationDate = $startDate + ($durationInDays * 86400);
                    $currentDate = time();
                    $daysRemaining = ($expirationDate - $currentDate) / 86400;

                    echo floor($daysRemaining) . $data["moncompte"]["premiumDayLeft"];
                }
                ?>
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
                        <li id=""><input class="noMarginImportant" type="text" id="u_email" name="u_email" placeholder="<?php echo($data["moncompte"]["form"]["placeholderEmail"]) ?>"></li>
                        <li id="" class="marginTop10"><input class="noMarginImportant" type="text" id="u_telephone" name="u_telephone" placeholder="<?php echo($data["moncompte"]["form"]["placeholderPhone"]) ?>"></li>
                        <li id="" class="marginTop10"><input class="noMarginImportant" type="text" id="u_name" name="u_name" placeholder="<?php echo($data["moncompte"]["form"]["placeholderName"]) ?>"></li>
                        <li id="" class="marginTop10"><input class="noMarginImportant" type="text" id="u_lastname" name="u_lastname" placeholder="<?php echo($data["moncompte"]["form"]["placeholderLastname"]) ?>"></li>					</ul>
					<input class="noMarginImportant" type="button" name="updateAccount" value="<?php echo($data["moncompte"]["form"]["inputUpdate"]) ?>" id="updateAccount" onclick="updateLeUser()">
				</div>
			</section>


            <section class="textCenter marginTop30">
                <script type="text/javascript" defer>

                </script>
                <button type="button" id="buttonTicket"><?php echo $data["moncompte"]["Ticket"] ?></button>
            </section>

            <section class="textCenter width80 border marginAuto marginTop30 marginBottom30 flex flexAround wrap">
                <h1 class="width80 textCenter">Vos fichiers</h1>
                <div id="containerFileLoader" class="width80">
                    <div class="loader"></div>
                </div>
                <div>
                    <hr>
                    <h2>Ajouter un document</h2>
                    <div id="fileSelect">
                        <label for="fileType">Type de document :</label>
                        <select name="fileType" id="fileType" required>
                            <option value="">Sélectionnez un type</option>
                            <hr>
                            <option value="permis">Permis de conduire</option>
                            <!--<option value="cni">Carte Nationale d'Identité (CNI)</option>
                            <option value="passport">Passeport</option>
                            <option value="justificatif_domicile">Justificatif de domicile</option>
                            <option value="rib">Relevé d'Identité Bancaire (RIB)</option>-->
                        </select>
                    </div>
                    <div class="marginTop20">
                        <label for="fileUpload">Choisissez un fichier :</label><br>
                        <input type="file" name="fileUpload" id="fileInput" required><br>
                        <input class="marginTop30" type="button" value="Ajouter un fichier" onclick="addFile()">
                    </div>
                </div>
            </section>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>

<script type="text/javascript" defer>
	myAccount()

    const button = document.getElementById("buttonTicket")
    button.addEventListener("click", ()=>{
        window.location.href = "<?php echo $data["moncompte"]["Ticketlink"] ?>"
    })

    async function addFile(){
        startLoading()
        //const fileType = document.querySelectorAll("#fileSelect > select").value
        const fileType = document.getElementById("fileType").value
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0]; // Récupère le premier fichier sélectionné

        if(fileType === "Choisissez un fichier :"){
            popup("Vous devez choisir un type")
            stopLoading()
            return
        }

        console.log(fileType)
        const data = {
            "file_type":fileType
        }
        const formData = new FormData();
        formData.append('file', file);
        formData.append('data', JSON.stringify(data));

        const fileManager = new File()
        await fileManager.connect()
        await fileManager.uploadFile(formData)
        await getUserFile(fileManager.id_user)
        stopLoading()
    }


    async function updateLeUser() {
        let u_email = document.getElementById("u_email").value
        let u_telephone = document.getElementById("u_telephone").value
        let u_name = document.getElementById("u_name").value
        let u_lastname = document.getElementById("u_lastname").value

        const userBabla = new User()
        await userBabla.connect()

        in_email = u_email ? u_email : userBabla.email
        in_telephone = u_telephone ? u_telephone : userBabla.telephone
        in_nom = u_name ? u_name : userBabla.nom
        in_prenom = u_lastname ? u_lastname : userBabla.prenom

        startLoading()
        const response = await userBabla.updateUser(in_nom, in_prenom, in_telephone, in_email)

        if (response) {
            popup("Updated successfully")
            await userBabla.me(true)
        } else {
            popup("Something went wrong")
        }
        stopLoading()
        myAccount()
    }

</script>