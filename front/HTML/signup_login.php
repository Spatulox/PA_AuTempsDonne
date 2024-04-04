<!DOCTYPE html>
<html lang="fr">
<head>

  <?php include("../includes/head.php");?>

  <title><?php echo($data["signup_login"]["title"]) ?></title>
  
</head>
<body>

  <?php include("../includes/header.php");?>

  <main>

    <section id="signup_login" class="inheritWidth container flex flexAround alignCenter nowrap">

      <div class="active" id="connectionBox">

        <div class="title_form">
          <h1><?php echo($data["signup_login"]["formLogin"]["title"]) ?></h1>
        </div>
      
        <form action="" class="form">
          <div class="form-group">
            <label for="email"> <?php echo($data["signup_login"]["formLogin"]["email"]) ?> : </label>
            <input type="email" id="emailCo" name="emailCo" placeholder=" <?php echo($data["signup_login"]["formLogin"]["email"]) ?> " oninput="fillEmail()">
          </div>
          <div class="form-group">
            <label for="motdepasse"> <?php echo($data["signup_login"]["formLogin"]["password"]) ?> : </label>
            <input type="password" id="motdepasseCo" name="motdepasseCo" placeholder=" <?php echo($data["signup_login"]["formLogin"]["password"]) ?> ">
          </div>
          <div class="form-group">
            <input type="submit" value=" <?php echo($data["signup_login"]["formLogin"]["input"]) ?> ">
          </div>
          <div class="form-group">
            <input type="button" id="connexionButton" value=" <?php echo($data["signup_login"]["formLogin"]["switch"]) ?> ">
          </div>
        </form>

      </div>  

      <div class="" id="inscriptionBox" >
        <div class="title_form">
          <h1><?php echo($data["signup_login"]["formSignUp"]["title"]) ?></h1>
        </div>

        <form action="" class="form">
          <div class="form-group">
            <label for="nom"> <?php echo($data["signup_login"]["formSignUp"]["name"]) ?> : </label>
            <input type="text" id="nom" name="nom" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["name"]) ?> ">
          </div>
          <div class="form-group">
            <label for="email"> <?php echo($data["signup_login"]["formSignUp"]["email"]) ?> : </label>
            <input type="email" id="emailInsc" name="emailInsc" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["email"]) ?> ">
          </div>
          <div class="form-group">
            <label for="motdepasse"> <?php echo($data["signup_login"]["formSignUp"]["password"]) ?> : </label>
            <input type="password" id="motdepasseInsc" name="motdepasseInsc" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["password"]) ?> ">
          </div>
          <div class="form-group">
            <label for="confirmation"> <?php echo($data["signup_login"]["formSignUp"]["confirmPassword"]) ?> : </label>
            <input type="password" id="confirmation" name="confirmation" placeholder=" <?php echo($data["signup_login"]["formSignUp"]["confirmPassword"]) ?> ">
          </div>
          <div class="form-group">
            <input type="submit" value=" <?php echo($data["signup_login"]["formSignUp"]["input"]) ?> ">
          </div>
          <div class="form-group">
            <input type="button" id="inscriptionButton" value=" <?php echo($data["signup_login"]["formSignUp"]["switch"]) ?> ">
          </div>
        </form>

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

      // const roleIndex = urlParams.findIndex(param => param.includes('role'));
      // if (roleIndex !== -1) {
      //   const roleBox = document.getElementById('roleBox')

      //   const roleNum = urlParams[roleIndex].split("=")[1]
      //   console.log(roleNum)

      //   // Active la radio box correspondante au numéro du rôle
      //   roleBox.?.? = roleIndex
      // }

    }

</script>