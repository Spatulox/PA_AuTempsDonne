<?php

$message = hasMessageInUrl($_SERVER['REQUEST_URI']);
header('Location: HTML/actualites.php' . $message);
exit();



function hasMessageInUrl($url) {
    // Vérifier si l'URL contient "?body=" ou "&body="
    if (strpos($url, '?message=') !== false || strpos($url, '&message=') !== false) {
        $message = explode("?message=", $url)[1];
        return "?message=" . $message;
    } else {
        return null;
    }
}

?>

