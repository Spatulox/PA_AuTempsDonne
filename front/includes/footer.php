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

<script type="text/javascript" defer>
	let message

	message = document.getElementById('titleFooter')

	if(message.innerHTML != ""){
		if(message){
			popup(message.innerHTML)
		}
	}

</script>