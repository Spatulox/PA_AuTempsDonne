<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["signup_login"]["title"]) ?></title>

</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <section id="signup_login" class="inheritWidth container flex flexAround alignCenter nowrap noMargin">

        <div class="active" id="connectionBox">

            <div class="title_form">
                <h1><?php echo($data["signup_login"]["formLogin"]["title"]) ?></h1>
            </div>

            <div>
                <div class="form-group">
                    <label for="email"> <?php echo($data["signup_login"]["formLogin"]["email"]) ?> : </label>
                    <input type="email" id="emailCo" name="emailCo"
                           placeholder=" <?php echo($data["signup_login"]["formLogin"]["email"]) ?> "
                           oninput="fillEmail()">
                </div>
                <div class="form-group">
                    <label for="motdepasse"> <?php echo($data["signup_login"]["formLogin"]["password"]) ?> : </label>
                    <input type="password" id="motdepasseCo" name="motdepasseCo"
                           placeholder=" <?php echo($data["signup_login"]["formLogin"]["password"]) ?> ">
                </div>
                <div class="form-group">
                    <button type="button" onclick="connexion()"
                            value="<?php echo($data["signup_login"]["formLogin"]["input"]) ?>"> <?php echo($data["signup_login"]["formLogin"]["input"]) ?> </button>
                </div>
                <div class="form-group">
                    <input type="button" id="connexionButton"
                           value=" <?php echo($data["signup_login"]["formLogin"]["switch"]) ?> ">
                </div>
            </div>

        </div>

        <div class="" id="inscriptionBox">
            <div class="title_form">
                <h1><?php echo($data["signup_login"]["formSignUp"]["title"]) ?></h1>
            </div>

            <div>
                <div class="form-group">
                    <label for="nom"> <?php echo($data["signup_login"]["formSignUp"]["name"]) ?>* : </label>
                    <input type="text" id="nomInsc" name="nom"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["name"]) ?>* ">
                </div>

                <div class="form-group">
                    <label for="prenom"> <?php echo($data["signup_login"]["formSignUp"]["firstname"]) ?>* : </label>
                    <input type="text" id="prenomInsc" name="prenom"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["firstname"]) ?>* ">
                </div>

                <div class="form-group">
                    <label for="phone"> <?php echo($data["signup_login"]["formSignUp"]["phone"]) ?> : </label>
                    <input type="number" id="phoneInsc" name="phoneInsc"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["phone"]) ?> ">
                </div>

                <div class="form-group">
                    <label for="email"> <?php echo($data["signup_login"]["formSignUp"]["email"]) ?>* : </label>
                    <input type="email" id="emailInsc" name="emailInsc"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["email"]) ?>* ">
                </div>

                <div class="form-group">
                    <label for="motdepasse"> <?php echo($data["signup_login"]["formSignUp"]["password"]) ?>* : </label>
                    <input type="password" id="motdepasseInsc" name="motdepasseInsc"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["password"]) ?>* ">
                </div>

                <div class="form-group">
                    <label for="confirmation"> <?php echo($data["signup_login"]["formSignUp"]["confirmPassword"]) ?>*
                        : </label>
                    <input type="password" id="confirmation" name="confirmation"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["confirmPassword"]) ?>* ">
                </div>

                <div class="form-group">
                    <label for="address"> <?php echo($data["signup_login"]["formSignUp"]["address"]) ?>*
                        : </label>
                    <input type="text" id="address" name="address"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["address"]) ?>* ">
                    <input type="text" id="postal" name="address"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["postal"]) ?>* ">
                    <input type="text" id="city" name="address"
                           placeholder=" <?php echo($data["signup_login"]["formSignUp"]["city"]) ?>* ">
                </div>

                <div>
                    <div class="form-group">
                        <label>Sélectionnez votre statut :</label>
                    </div>
                    <div class="radio-group">
                        <input type="radio" id="benevole" name="statut" value="3">
                        <label for="benevole">Bénévole</label>
                        <br>
                        <input type="radio" id="beneficiaire" name="statut" value="4">
                        <label for="beneficiaire">Bénéficiaire</label>
                        <br>
                        <input type="radio" id="prestataire" name="statut" value="5">
                        <label for="prestataire">Prestataire</label>
                    </div>
                </div>

                <div class="form-group">
                    <input type="button" onclick="signup()"
                           value=" <?php echo($data["signup_login"]["formSignUp"]["input"]) ?> ">
                </div>

                <div class="form-group">
                    <input type="button" id="inscriptionButton"
                           value=" <?php echo($data["signup_login"]["formSignUp"]["switch"]) ?> ">
                </div>
            </div>

        </div>

    </section>
</main>

<?php include("../includes/footer.php"); ?>

</body>
</html>

<script type="text/javascript" defer>

    const connectionBox = document.getElementById('connectionBox')
    const connectionButton = document.getElementById('connexionButton')
    const inscriptionBox = document.getElementById('inscriptionBox')
    const inscriptionButton = document.getElementById('inscriptionButton')

    connectionButton.addEventListener("click", () => {
        connectionBox.classList.remove('active')
        inscriptionBox.classList.add('active')
    })

    inscriptionButton.addEventListener("click", () => {
        connectionBox.classList.add('active')
        inscriptionBox.classList.remove('active')
    })

    function fillEmail() {
        const value = document.getElementById('emailCo')
        const valueToReplace = document.getElementById('emailInsc')

        valueToReplace.value = value.value
    }
</script>

<script type="text/javascript" defer>

    var currentURL = window.location.href;
    currentURL = currentURL.split('/')
    currentURL = currentURL[currentURL.length - 1]

    const urlParams = currentURL.split("?");

    if (currentURL.length > 1) {

        if (urlParams.includes('signup=true')) {
            const signup = document.getElementById('inscriptionBox')
            const login = document.getElementById('connectionBox')

            signup.classList.add("active")
            login.classList.remove('active')
        }
    }

</script>

<script>

    async function signup() {

        let getSelectedValue = (() => {
            const radios = document.getElementsByName('statut');
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    return radios[i].value
                }
            }
            return false
        })


        const nom = document.getElementById("nomInsc").value
        const prenom = document.getElementById("prenomInsc").value
        const telephone = document.getElementById("phoneInsc").value

        const email = document.getElementById("emailInsc").value
        const password = document.getElementById("motdepasseInsc").value

        const street = document.getElementById("address").value
        const postal = document.getElementById("postal").value
        const city = document.getElementById("city").value

        if (!nom || !prenom || !email || !password || !street || !postal || !city) {
            popup("Veuillez remplir le formulaire...")
            return
        }

        const role = getSelectedValue()
        if (role === false) {
            popup("Vous devez spécifier un rôle :/")
            return
        }

        let string = street + " " + postal + ", " + city

        const data = {
            "nom": nom,
            "prenom": prenom,
            "telephone": telephone,
            "email": email,
            "mdp": password,
            "role": role,
            "address": string
        }

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        };


        // Fetch the api
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

        const user = new User(email, password)
        await user.connect()
        user.printUser()

        redirect("./moncompte.php?message=Votre compte est en attente de validation auprès de la modération")

    }

</script>

<script>

    async function signup() {

        startLoading()

        let getSelectedValue = (() => {
            const radios = document.getElementsByName('statut');
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    return radios[i].value
                }
            }
            return false
        })


        const nom = document.getElementById("nomInsc").value
        const prenom = document.getElementById("prenomInsc").value
        const telephone = document.getElementById("phoneInsc").value

        const email = document.getElementById("emailInsc").value
        const password = document.getElementById("motdepasseInsc").value

        const street = document.getElementById("address").value
        const postal = document.getElementById("postal").value
        const city = document.getElementById("city").value

        if (!nom || !prenom || !email || !password || !street || !postal || !city) {
            popup("Veuillez remplir le formulaire...")
            stopLoading()
            return
        }

        const role = getSelectedValue()
        if (role === false) {
            popup("Vous devez spécifier un rôle :/")
            stopLoading()
            return
        }

        let string = street + " " + postal + ", " + city

        const data = {
            "nom": nom,
            "prenom": prenom,
            "telephone": telephone,
            "email": email,
            "mdp": password,
            "role": role,
            "address": string
        }

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        };


        // Fetch the api
        const response = await fetch("http://localhost:8081/index.php/user", options)

        if (!response.ok) {
            const text = await response.json()
            alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
            if (text.hasOwnProperty("message")) {
                alertDebug(text.message)
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
        }

        const user = new User(email, password)
        await user.connect()
        user.printUser()
        stopLoading()
        redirect("./moncompte.php?message=Votre compte est en attente de validation auprès de la modération")

    }

</script>