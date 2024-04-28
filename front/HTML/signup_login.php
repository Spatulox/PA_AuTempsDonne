<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html lang="fr">
<head>

  <?php include("../includes/head.php");?>

  <title><?php echo($data["signup_login"]["title"]) ?></title>
  
</head>
<body>

  <?php include("../includes/header.php");?>

  <main>

    <section id="signup_login" class="inheritWidth container flex flexAround alignCenter nowrap noMargin">

      <div class="active" id="connectionBox">

        <div class="title_form">
          <h1><?php echo($data["signup_login"]["formLogin"]["title"]) ?></h1>
        </div>
      
        <div>
          <div class="form-group">
            <label for="email"> <?php echo($data["signup_login"]["formLogin"]["email"]) ?> : </label>
            <input type="email" id="emailCo" name="emailCo" placeholder=" <?php echo($data["signup_login"]["formLogin"]["email"]) ?> " oninput="fillEmail()">
          </div>
          <div class="form-group">
            <label for="motdepasse"> <?php echo($data["signup_login"]["formLogin"]["password"]) ?> : </label>
            <input type="password" id="motdepasseCo" name="motdepasseCo" placeholder=" <?php echo($data["signup_login"]["formLogin"]["password"]) ?> ">
          </div>
          <div class="form-group">
            <button type="button" onclick="connexion()" value="<?php echo($data["signup_login"]["formLogin"]["input"]) ?>"> <?php echo($data["signup_login"]["formLogin"]["input"]) ?> </button>
          </div>
          <div class="form-group">
            <input type="button" id="connexionButton" value=" <?php echo($data["signup_login"]["formLogin"]["switch"]) ?> ">
          </div>
        </div>

      </div>  

      <div class="" id="inscriptionBox" >
        <div class="title_form">
          <h1><?php echo($data["signup_login"]["formSignUp"]["title"]) ?></h1>
        </div>

        <div>
          <div class="form-group">
            <label for="nom"> <?php echo($data["signup_login"]["formSignUp"]["name"]) ?>* : </label>
            <input type="text" id="nomInsc" name="nom" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["name"]) ?>* ">
          </div>

          <div class="form-group">
            <label for="prenom"> <?php echo($data["signup_login"]["formSignUp"]["firstname"]) ?>* : </label>
            <input type="text" id="prenomInsc" name="prenom" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["firstname"]) ?>* ">
          </div>
          
          <div class="form-group">
            <label for="phone"> <?php echo($data["signup_login"]["formSignUp"]["phone"]) ?> : </label>
            <input type="number" id="phoneInsc" name="phoneInsc" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["phone"]) ?> ">
          </div>

          <div class="form-group">
            <label for="email"> <?php echo($data["signup_login"]["formSignUp"]["email"]) ?>* : </label>
            <input type="email" id="emailInsc" name="emailInsc" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["email"]) ?>* ">
          </div>
          
          <div class="form-group">
            <label for="motdepasse"> <?php echo($data["signup_login"]["formSignUp"]["password"]) ?>* : </label>
            <input type="password" id="motdepasseInsc" name="motdepasseInsc" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["password"]) ?>* ">
          </div>
          
          <div class="form-group">
            <label for="confirmation"> <?php echo($data["signup_login"]["formSignUp"]["confirmPassword"]) ?>* : </label>
            <input type="password" id="confirmation" name="confirmation" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["confirmPassword"]) ?>* ">
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
            <input type="button" onclick="signup()" value=" <?php echo($data["signup_login"]["formSignUp"]["input"]) ?> ">
          </div>
          
          <div class="form-group">
            <input type="button" id="inscriptionButton" value=" <?php echo($data["signup_login"]["formSignUp"]["switch"]) ?> ">
          </div>
        </div>

      </div>

    </section>
  </main>

  <?php include("../includes/footer.php");?>

</body>
</html>

<script type="text/javascript" defer>

  const connectionBox = document.getElementById('connectionBox')
  const connectionButton = document.getElementById('connexionButton')
  const inscriptionBox = document.getElementById('inscriptionBox')
  const inscriptionButton = document.getElementById('inscriptionButton')

  connectionButton.addEventListener("click", ()=>{
    connectionBox.classList.remove('active')
    inscriptionBox.classList.add('active')
  })

  inscriptionButton.addEventListener("click", ()=>{
    connectionBox.classList.add('active')
    inscriptionBox.classList.remove('active')
  })

  function fillEmail(){
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

    if(currentURL.length > 1){  

      if(urlParams.includes('signup=true')){
        const signup = document.getElementById('inscriptionBox')
        const login = document.getElementById('connectionBox')

        signup.classList.add("active")
        login.classList.remove('active')
      }
    }

</script>