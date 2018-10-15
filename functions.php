<?php

/*
Date: 9/16/2018
File Name: functions.php
*/


/* This function will generate the list of countries for a drop down list
		I don't know if we'll add more countries or not. Depending on how many we add,
		we'll need a more efficient way of making the list.
*/
function CountryList() {
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT country FROM Countries");
	$list = $stmt->execute();

	return $list;
}

function DegreeTypeList(){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT degree FROM Degree Types");
	$list = $stmt->execute();

	return $list;
}

function AddDegreeType($degreeType){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT * FROM Degree Types WHERE degree = " . $degreeType);
	$result = $stmt->execute();

	if(!isset($result)){	//if there is already a degree type with the same name in the database
		return true;
	}
	$stmt = null;
	$result = null;

	$stmt = $con->prepare("INSERT INTO Degree Types (degree_type_ID, degree) values (?, ?)");
	$stmt->execute(DEFAULT, $degreeType);
	$stmt = null;

	$con = null;

	return true;
}

function AddCountry($countryName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT * FROM Countries WHERE country = " . $countryName);
	$result = $stmt->execute();

	if(!isset($result)){	//if there is already a country with the same name in the database
		return true;
	}
	$stmt = null;

	$stmt = $con->prepare("INSERT INTO Countries (country_ID, country) values (?, ?)");
	$stmt->execute(DEFAULT, $countryName);
	$stmt = null;

	$con = null;

	return true;
}

function EditCountry($oldName, $newName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("UPDATE Countries SET country = '" . $newName . "' WHERE country = '" . $oldName . "'");
	$stmt->execute();
}

function DeleteCountry($countryName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("DELETE FROM Countries WHERE country = '" . $countryName . "'");
	$stmt->execute();
}

function DegreeTypeList() {
	return '\n<option value = "HS">High School</option>\n<option value = "BS">Bachelors Degree</option>\n<option value = "MS">Masters Degree</option>\n<option value = "PHD">PHD</option>\n';
}

function makeCode($email) {

}

//isValidLogin checks if the username and password given correspond to an account.
//returns false if there is no account with the given username and password.
function isValidLogin($username, $password){
	return true;
}
?>
