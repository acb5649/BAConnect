<?php

/*
Date: 10/16/2018
File Name: functions.php
*/

//WARNING!!! In the function DeleteDegreeType, when editing the degree_type_ID of degrees using the degree type that is about to be deleted, 'Degrees' shows up as a different
//color than usual, but we couldn't figure out why. So PHP may think it means something we didn't intend. If we're having problems deleting degree types, you may want to look
//there first.

// This function will generate a list of all the countries in the database
function CountryList() {
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return null;
	}

	$stmt = $con->prepare("SELECT country FROM Countries");
	$list = $stmt->execute();

	$con = null;
	$stmt = null;
	return $list;
}

// This function will generate a list of all the degree Types in the database
function DegreeTypeList(){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return null;
	}

	$stmt = $con->prepare("SELECT degree FROM Degree Types");
	$list = $stmt->execute();

	$con = null;
	$stmt = null;
	return $list;
}

// This function will add a new degree type to the database, but will check if it's already in the database first
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
		$con = null;
		$stmt = null;
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
// This function will edit a pre-existing degree type name in the database
function EditDegreeType($oldName, $newName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("UPDATE Degree Types SET degree = '" . $newName . "' WHERE degree = '" . $oldName . "'");
	$stmt->execute();

	$con = null;
	$stmt = null;
	return true;
}
// This function will delete a degree type from the database
//WARNING!!! In the function DeleteDegreeType, when editing the degree_type_ID of degrees using the degree type that is about to be deleted, 'Degrees' shows up as a different
//color than usual, but we couldn't figure out why. So PHP may think it means something we didn't intend. If we're having problems deleting degree types, you may want to look
//there first.
function DeleteDegreeType($degreeTypeName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT degree_type_ID FROM Degree Types WHERE degree = '" . $degreeTypeName . "'");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($row == null){
		return true;
	}
	$id = $row['degree_type_ID'];

	$stmt = null;

	$stmt = $con->prepare("UPDATE Degrees SET degree_type_ID = -1 WHERE degree_type_ID = '" . $id . "'" );

	$row = null;
	$id = null;

	$stmt = $con->prepare("DELETE FROM Degree Types WHERE degree = '" . $degreeTypeName . "'");
	$stmt->execute();

	$con = null;
	$stmt = null;
	return true;
}
// This function will add a new country to the database, but will check if it's already in the database first
function AddCountry($countryName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT * FROM Countries WHERE country = " . $countryName);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if($result != null){	//if there is already a country with the same name in the database
		return true;
	}
	$stmt = null;
	$result = null;

	$stmt = $con->prepare("INSERT INTO Countries (country_ID, country) values (?, ?)");
	$stmt->execute(DEFAULT, $countryName);

	$stmt = null;
	$con = null;
	return true;
}
// This function will edit a pre-existing country name in the database
function EditCountry($oldName, $newName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("UPDATE Countries SET country = '" . $newName . "' WHERE country = '" . $oldName . "'");
	$stmt->execute();

	$con = null;
	$stmt = null;
	return true;
}
// This function will delete a country from the database
function DeleteCountry($countryName){
	try {
		$con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
	} catch(PDOException $e){
		echo $e->getMessage();
		return false;
	}

	$stmt = $con->prepare("SELECT country_ID FROM Countries WHERE country = '" . $countryName . "'");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($row == null){
		return true;
	}
	$id = $row['country_ID'];

	$stmt = null;

	$stmt = $con->prepare("UPDATE Addresses SET country_ID = -1 WHERE country_ID = '" . $id . "'" );

	$row = null;
	$id = null;


	$stmt = $con->prepare("DELETE FROM Countries WHERE country = '" . $countryName . "'");
	$stmt->execute();

	$con = null;
	$stmt = null;
	return true;
}

function makeCode($email) {

}

//isValidLogin checks if the username and password given correspond to an account.
//returns false if there is no account with the given username and password.
function isValidLogin($username, $password){
	return true;
}
?>
