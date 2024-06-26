<?php include("../includes/loadLang.php");?>


<!DOCTYPE html>
<html lang="fr">
<head>

	<?php include("../includes/head.php"); ?>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo($data["404"]["title"]) ?></title>
  <style>
    /* Styles généraux */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        height: 100vh;
        width: 100vw;
    }

    html{
        height: 100vh;
        width: 100vw;
    }

    /* Styles pour le contenu principal */
    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
        margin: 0;
        border-width: 0px;
    }

    .error-code {
      font-size: 10rem;
      font-weight: bold;
      color: #e53935;
      animation: bounce 2s infinite;
    }

    .error-message {
      font-size: 2rem;
      margin-top: 2rem;
    }

    .btn {
      display: inline-block;
      background-color: #e53935;
      color: #fff;
      text-decoration: none;
      padding: 1rem 2rem;
      border-radius: 4px;
      margin-top: 2rem;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #c62828;
    }

    /* Vidéo en arrière-plan */
    iframe {
        width: 400px;
        height: 230px;
        background-color: blue;
        position: absolute;
        animation: move-around 10s infinite linear;
    }

    @keyframes move-around {
        0% {
            top: 0;
            left: 0;
        }
        25% {
            top: 0;
            left: calc(100% - 400px);
        }
        50% {
            top: calc(100% - 230px);
            left: calc(100% - 400px);
        }
        75% {
            top: calc(100% - 230px);
            left: 0;
        }
        100% {
            top: 0;
            left: 0;
        }
    }

    /* Animations */
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-30px);
      }
      60% {
        transform: translateY(-15px);
      }
    }
  </style>
</head>
<body>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/L0TB1IkhVds?autoplay=1&loop=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <div class="container">
        <div class="error-code">404</div>
        <div class="error-message"><?php echo($data["404"]["errorMessage"]) ?></div>
        <a href="./index.php" class="btn"><?php echo($data["404"]["goBack"]) ?></a>
    </div>
</body>
</html>
