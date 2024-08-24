<?php

include_once './Service/globalFunctions.php';

function checkData($table = -10, $columnArray = -10, $columnData = -10, $condition = -10){
	$bool = false;

	$sentence = "Please specifie ";
	$addSentence = "";
	if (empty($table)){
		$bool = true;
		$sentence .= "the table, ";
	}
	if (empty($columnArray)){
		$bool = true;
		$sentence .= "the colums, ";
	}
	if (empty($columnData)){
		$bool = true;
		$sentence .= "the data, ";
	}

	if (empty($condition))
	{
		$bool = true;
		$sentence .= "the condition, ";
		$addSentence .= " To apply no condition, plz give -1.";
	}

	if ($bool == true){
		$sentence .= "(to execute the function, each args has to be not null).". $addSentence;
		exit_with_message($sentence);
	}

	/*if (!checkMsg($condition, "=") && $condition != -1 && $condition != -10){
		exit_with_message('Plz enter a valid condition like : columnName=data'. $addSentence);
	}*/
}

# -------------------------------------------------------------- #

function connectDB(){

	// Lire le contenu du fichier JSON
	$json_file = file_get_contents('/var/www/html/env.json');

	// Décoder le contenu JSON en un tableau PHP
	$data = json_decode($json_file, true);

	$dbHost = $data['DB_HOST'];
	$dbPort = $data['DB_PORT'];
	$dbName = $data['DB_NAME'];
	$dbUser = $data['DB_USER'];
	$dbPassword = $data['DB_PASSWORD'];

	try {
	    $db = new PDO(
	        'mysql:host='.$dbHost.';
	        port='.$dbPort.';
	        dbname='.$dbName.';
	        user='.$dbUser.';
	        password='.$dbPassword.'',
	        null,
	        null,
	        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
	    );
	} catch (Exception $e) {
		die($e);
	}

	return $db;
}

# -------------------------------------------------------------- #

function selectDB($table, $colums, $condition = -1, $additionnalMessage = NULL){
	// -1 : the user want no condition or no condition entered by the user.
	// $colums must be like that : $columns = "idusers, role"

	checkData($table, $colums, -10, $condition);


	$db = connectDB();

	if ($condition == -1){
		$dbRequest = 'SELECT '. $colums .' FROM '. $table;
	}
	else{
		/*if(!checkMsg($condition, '=')){
			exit_with_message('Plz enter a valid condition like : columnName=data', 500);
		}*/

		$dbRequest = 'SELECT '. $colums .' FROM '. $table . ' WHERE ' . $condition;
	}

    if($additionnalMessage != '-@' && $additionnalMessage != "bool" && $additionnalMessage != NULL){
        $dbRequest .= " ".$additionnalMessage;
    }

	if($additionnalMessage == "-@"){
		var_dump($dbRequest);
	}

	try{
		$result = $db->prepare($dbRequest);
		$result->execute();

		$reponse = $result->fetchAll();
		if ($reponse == false)
		{
			if ($additionnalMessage == NULL || $additionnalMessage == "-@"){
				exit_with_message("ERROR : Impossible to select data", 500);
			}
			elseif($additionnalMessage == "bool"){
				return false;
			}
			else{
				exit_with_message("ERROR : Impossible to select data ".$additionnalMessage, 500);
			}
		}
		return $reponse;
	}
	catch (PDOException $e)
	{
        if($additionnalMessage == "-@"){
            exit_with_message($e->getMessage());
        }

		if (checkMsg($e->getMessage(), $wordToSearch = "Undefined column"))
		{

			$tmp = explode("does not exist", explode(":", $e->getMessage())[3])[0] . "does not exist";
			exit_with_message("Error : ".str_replace('"', "'", $tmp), 500);
		}

	    exit_with_message("PDO error :" . str_replace('"', "'", explode("DETAIL: ", $e->getMessage())[1]), 500);
	}

	return false;
}

# -------------------------------------------------------------- #

function selectJoinDB($table, $colums, $join, $condition = -1, $additionnalMessage = NULL){
	// -1 : the user want no condition or no condition entered by the user.
	// $colums must be like that : $columns = "idusers, role"

	checkData($table, $colums, -10, $condition);


	$db = connectDB();

	if ($condition == -1){
		$dbRequest = 'SELECT '. $colums .' FROM '. $table . ' ' . $join;
	}
	else{
		/*if(!checkMsg($condition, '=')){
			exit_with_message('Plz enter a valid condition like : columnName=data', 500);
		}*/

		$dbRequest = 'SELECT '. $colums .' FROM '. $table . ' ' . $join . ' WHERE ' . $condition;
	}

	if($additionnalMessage == "-@"){
		var_dump($dbRequest);
	}

	try{
		$result = $db->prepare($dbRequest);
		$result->execute();

		$reponse = $result->fetchAll();
		if ($reponse == false)
		{
			if ($additionnalMessage == NULL || $additionnalMessage == "-@"){
				exit_with_message("ERROR : Nothing to show", 500);
			}
			elseif($additionnalMessage == "bool"){
				return false;
			}
			else{
				exit_with_message("ERROR : Impossible to select data ".$additionnalMessage, 500);
			}
		}
		return $reponse;
	}
	catch (PDOException $e)
	{

        if($additionnalMessage == "-@"){
            exit_with_message($e->getMessage());
        }

		if (checkMsg($e->getMessage(), $wordToSearch = "Undefined column"))
		{

			$tmp = explode("does not exist", explode(":", $e->getMessage())[3])[0] . "does not exist";
			exit_with_message("Error : ".str_replace('"', "'", $tmp), 500);
		}

	    exit_with_message("PDO error :" . str_replace('"', "'", explode("DETAIL: ", $e->getMessage())[1]), 500);
	}
	return false;
}

# -------------------------------------------------------------- #

function insertDB($table, $columnArray, $columnData, $returningData = null)
{
	// -10 no condition enter by the user
	// -1 : the user want no condition

	checkData($table, $columnArray, $columnData, -10);

	$db = connectDB();


	$colums = $columnArray[0];
	for ($i=1; $i < count($columnArray) ; $i++) { 
		$colums .= ", " . $columnArray[$i];
	}


	if (gettype($columnData[0]) == "boolean") {
	    $columnData[$i] == "1" ? $tmp = "true" : $tmp = "false";
	    $data = $tmp;
	}
    else if (gettype($columnData[0]) == "integer"){
        $data = $columnData[0];
    }
    else if ($columnData[0] == "NULL"){
        $data = NULL;
    }
	else{
		$data = "'".$columnData[0]."'";
	}


	for ($i=1; $i < count($columnData) ; $i++) { 
		if (gettype($columnData[$i]) == "boolean") {
		    $columnData[$i] == "1" ? $tmp = "true" : $tmp = "false";
		    $data .= ", " . $tmp;
		}
        else if (gettype($columnData[$i]) == "integer"){

            $data .= ", " . $columnData[$i];
        }
        else if ($columnData[$i] == "NULL"){
            $data .= ", " . $columnData[$i];
        }
		else{
			$data .= ", '" . $columnData[$i]."'";
		}
	}


    $dbRequest = 'INSERT INTO '. $table .' (' . $colums . ') VALUES ('. $data . ')';
	
	if($returningData == "-@"){
		var_dump($dbRequest);
	}


	try{
		$result = $db->prepare($dbRequest);
		$result->execute();

		if ($returningData == null || $returningData == "-@" ||  $returningData == "bool"){
			return true;
		}
        if(strpos($returningData, "MAX") !== false){
            return selectDB($table, $returningData);
        }
		return selectDB($table, '*', $returningData);
	}
	catch (PDOException $e)
	{
        if($returningData == "-@"){
            exit_with_message("PDO error :" . $e->getMessage());
        }

        if($returningData == "bool"){
            return false;
        }

		if (checkMsg($e->getMessage(), $wordToSearch = "Undefined column"))
		{
			$tmp = explode("does not exist", explode(":", $e->getMessage())[3])[0] . "does not exist";
			exit_with_message("Error : ".str_replace('"', "'", $tmp));
		}



	    exit_with_message("PDO error :" . str_replace('"', "'", explode("DETAIL: ", $e->getMessage())[1]));
	}

	return false;
}

# -------------------------------------------------------------- #

function updateDB($table, $columnArray, $columnData, $condition = null, $debug = null)
{
	// -10 no condition enter by the user
	// -1 : the user want no condition

	checkData($table, $columnArray, $columnData, $condition);

	if (count($columnArray) != count($columnData)){
		exit_with_message('ERROR : Colums and data must have the same length');
	}

	$db = connectDB();

	// Need to have the first initialization for the concatenation for the db request "not have a ',' at the begining of the request"
	if (gettype($columnData[0]) == "boolean") {
	    $columnData[0] == "1" ? $tmp = "true" : $tmp = "false";
	    $updatedData = $columnArray[0] . "=" . $tmp;
	}
	else{
		$updatedData = $columnArray[0] . "='" . $columnData[0] ."'";
	}


	for ($i=1; $i < count($columnArray) ; $i++) {
		if (gettype($columnData[$i]) == "boolean") {
		    $columnData[$i] == "1" ? $tmp = "true" : $tmp = "false";
		    $updatedData .= ", " . $columnArray[$i] . "=" . $tmp;
		} 
		else if (gettype($columnData[$i]) == "integer"){
			//var_dump($columnData[$i]);
		    $updatedData .= ", " . $columnArray[$i] . "=" . $columnData[$i];
		}
		else{
			$updatedData .= ", " . $columnArray[$i] . "='" . $columnData[$i]."'";
		}
	}

	if ($condition == -1){
		$dbRequest = 'UPDATE '. $table .' SET ' . $updatedData;
	}
	else{
		$dbRequest = 'UPDATE '. $table .' SET ' . $updatedData .'  WHERE ' . $condition ;
	}

    if($debug == "-@"){
        var_dump($dbRequest);
    }

	try{
		$result = $db->prepare($dbRequest);
		$result->execute();

		return true;
	}
	catch (PDOException $e)
	{	
		if($debug == "-@"){
			var_dump($e->getMessage());
			//exit_with_message($e->getMessage());
		}
		if (checkMsg($e->getMessage(), $wordToSearch = "Undefined column"))
		{
			$tmp = explode("does not exist", explode(":", $e->getMessage())[3])[0] . "does not exist";
			exit_with_message("Error : ".str_replace('"', "'", $tmp));
		}

		if (checkMsg($e->getMessage(), $wordToSearch = "for key"))
		{
			$tmp = explode("for key", explode(":", $e->getMessage())[2])[0];
			exit_with_message("Error : ".str_replace('"', "'", $tmp));
		}

        if($debug == "-@"){
            exit_with_message("PDO error :" . $e->getMessage());
        }
        if($debug == "bool"){
            return false;
        }

	    exit_with_message("PDO error :" . str_replace('"', "'", explode("DETAIL: ", $e->getMessage())[1]));
	}
	
	return false;
}

# -------------------------------------------------------------- #

function deleteDB($table, $condition, $debug = null)
{
	checkData($table, -10, -10, $condition);

	$db = connectDB();

	if(!selectDB($table, "*", $condition, "bool"))
	{
        if( $debug == "bool"){
            return false;
        }
		exit_with_message("ERROR : The thing reqested doesn't exist");
	}

	if($condition == -1){
		$dbRequest = 'DELETE FROM '. $table;
	}
	else{
		$dbRequest = 'DELETE FROM '. $table .' WHERE ' . $condition ;
	}

    if($debug == "-@"){
        var_dump($dbRequest);
    }

	try{
		$result = $db->prepare($dbRequest);
		$result->execute();

		return true;
	}
	catch (PDOException $e)
	{

        if($debug == "-@"){
            exit_with_message("PDO error :" . $e->getMessage());
        }

        if($debug == "bool"){
            return false;
        }

		if (checkMsg($e->getMessage(), $wordToSearch = "Undefined column"))
		{
			$tmp = explode("does not exist", explode(":", $e->getMessage())[3])[0] . "does not exist";
			exit_with_message("Error : ".str_replace('"', "'", $tmp));
		}

	    exit_with_message("PDO error :" . str_replace('"', "'", explode("DETAIL: ", $e->getMessage())[1]));
	}
	
	return false;
}



?>