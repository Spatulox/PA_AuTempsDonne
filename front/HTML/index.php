<?php


function hasMessageInUrl($url) {
    // Vérifier si l'URL contient "?body=" ou "&body="
    if (strpos($url, '?message=') !== false || strpos($url, '&message=') !== false) {

    	$message = explode("?message=", $url)[1];
        return "?message=".$message;
    } else {
        return null;
    }
}

$message = hasMessageInUrl($_SERVER['REQUEST_URI']);

header('Location: actualites.php'.$message);
exit()
?>

