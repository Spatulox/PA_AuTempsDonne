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


function hasMessageInUrl($url) {
    if (strpos($url, '?message=') !== false || strpos($url, '&message=') !== false) {

    	$message = explode("?message=", $url)[1];
        return "?message=".$message;
    } else {
        return null;
    }
}

$message = hasMessageInUrl($_SERVER['REQUEST_URI']);
?>

<header>
	<img src="../Images/Au_Temps_Donne.png" onclick="popup('ccc')">
	<nav>
		<ul>
			<?php

				foreach ($data["header"]["li"] as $key => $value) {
					
					if($key == "./signup_login.php" && isset($_COOKIE['apikey'])){
						//var_dump($_COOKIE['apikey'])
						echo('<li><a href="./index.php" onclick="deconnection()">'. $data["header"]["disconnect"] . '</a></li>');
					}
					else{
						echo('<li><a href="' . $key . '">' . $value . '</a></li>');
					}
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
	<?php
		if($message){
			$message = explode("?message=", $message)[1];
			$message = str_replace("%20", " ", $message);

			echo('	<h2 class="" id="titleFooter" style="position:absolute;">
						'.$message.'
					</h2>
				');
		}
		else{
			echo('	<h2 class="" id="titleFooter" style="position:absolute;"></h2>
				');
		}
	?>
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
