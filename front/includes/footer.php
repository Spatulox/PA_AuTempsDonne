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