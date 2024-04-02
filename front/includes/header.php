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
		  <option value="en">EN</option>
		  <option value="fr">FR</option>
		</select>

	</nav>
</header>
