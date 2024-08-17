<?php
	
function checkError($msg){
	if (strpos(strtolower($msg), "error") !== false){
		return true;
	}

	return false;
}

# -------------------------------------------------------------- #

function checkMsg($msg, $wordToSearch){

	if (empty($msg) || empty($wordToSearch))
	{
		exit_with_message("ERROR : Plz enter a valid msg or a valid wordToSearg in the msg");
	}

	if (strpos($msg, $wordToSearch) !== false){
		return true;
	}

	return false;
}


?>