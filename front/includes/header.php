<!--

Créateur : Marc Lecomte
Date : 02/04/2024
EATNow : Header

-->

<?php

$directory = '../lang/';

$files22 = scandir($directory);
$fileArray = [];

foreach ($files22 as $filea) {
    if ($filea !== '.' && $filea !== '..' && $filea !== 'lang_example.json' && pathinfo($filea, PATHINFO_EXTENSION) === 'json') {
    	
    	$filea = explode(".json", $filea)[0];
    	$filea = explode('lang_', $filea)[1];
    	array_push($fileArray, $filea);
    }
}

?>

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
			<?php
			for ($i=0; $i < count($fileArray) ; $i++) { 
				//print_r($fileArray[$i]);
				echo '<option value="' . strtolower($fileArray[$i]) . '" onclick="setCookie(\'lang\', \'' . $fileArray[$i] . '\', 1000)">' . $fileArray[$i] . '</option>';
			}

			?>
		  <!-- <option value="en" onclick="setCookie('lang', 'EN', 1000)">EN</option>
		  <option value="fr" onclick="setCookie('lang', 'FR', 1000)">FR</option>
		  <option value="zu" onclick="setCookie('lang', 'ZU', 1000)">ZU</option> -->
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
