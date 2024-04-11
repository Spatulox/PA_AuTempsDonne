<?php

if (isset($_COOKIE["lang"])) {
    $userLanguage = $_COOKIE['lang'];
	$userLanguage = strtoupper($userLanguage);

} else {
	$currentDomain = $_SERVER['HTTP_HOST'];

    $userLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	$userLanguage = strtoupper($userLanguage);

	setcookie("lang", $userLanguage, time() + 1000 * 3600, "/");
}

$userLanguageFile = '../lang/lang_'.$userLanguage.'.json';
if (!file_exists($userLanguageFile)) {
   $userLanguageFile = '../lang/lang_EN.json';
   $userLanguage = "EN";
}

$file = file_get_contents($userLanguageFile);
$data = json_decode($file, true);

?>