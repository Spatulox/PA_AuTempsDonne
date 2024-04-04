<!--

Créateur : Marc Lecomte
Date : 02/04/2024
EATNow : Header

-->

<header>
	<img src="../Images/Au_Temps_Donne.png">
	<nav>
		<ul>
			<?php

				foreach ($data["header"]["li"] as $key => $value) {
					echo('<li><a href="' . $key . '">' . $value . '</a></li>');
				}
			?>
		</ul>

		<select id="language-select" name="language">
			<option value="<?php echo(strtolower($userLanguage)) ?>_default"><?php echo($userLanguage) ?></option>
			<hr>
		  <option value="en" onclick="setCookie('lang', 'EN', 1000)">EN</option>
		  <option value="fr" onclick="setCookie('lang', 'FR', 1000)">FR</option>
		  <option value="zu" onclick="setCookie('lang', 'ZU', 1000)">ZU</option>
		</select>

	</nav>
</header>

<script type="text/javascript" defer>
	
	// Fonction pour créer un cookie avec une durée d'expiration en heures
	function setCookie(cname, cvalue, exhours) {
	  var d = new Date();
	  d.setTime(d.getTime() + (exhours*3600*1000));
	  var expires = "expires="+ d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";

	  window.location.reload(true);
	}

	// Créer un cookie qui expire après 10 000 heures
	//setCookie("myCustomCookie", "myCustomValue", 10000);

</script>
