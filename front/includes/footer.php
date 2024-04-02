<?php

$file = file_get_contents('../lang/lang_fr.json');
$data = json_decode($file, true);

?>

<footer>
	<nav>
		<ul>
			<?php

				foreach ($data["footer"]["li"] as $key => $value) {
					echo('<li><a href="' . $key . '">' . $value . '</a></li>');
				}
			?>
		</ul>
	</nav>
</footer>