<?php
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
	<?php
		if($message){
			$message = explode("?message=", $message)[1];
			$message = str_replace("%20", " ", $message);

			echo('	<h2 class="" id="titleFooter" style="position:absolute;">
						'.$message.'
					</h2>
				');
		}
	?>
	
</footer>

<script type="text/javascript" defer>
	let message

	message = document.getElementById('titleFooter')

	if(message){
		popup(message.innerHTML)
	}

</script>