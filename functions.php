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
	$list = '<option value = "USA">United States of America</option>
  		    <option value = "MYA">Myanmar</option>';

	return $list;
}

function AddCountry($countryName){

}

function DegreeTypeList() {
	return '\n<option value = "HS">High School</option>\n<option value = "BS">Bachelors Degree</option>\n<option value = "MS">Masters Degree</option>\n<option value = "PHD">PHD</option>\n';
}

function makeCode($email) {
	return hash('md5', $email) . substr(str_shuffle(str_repeat($x='0123456789abcdef', ceil(18/strlen($x)) )), 1, 18);
}

function verifyCode($code, $email) {
	$hash = hash('md5', $email);
	$codeHash = substr($code, 0, 32);
	return ($hash == $codeHash);
}

//isValidLogin checks if the username and password given correspond to an account.
//returns false if there is no account with the given username and password.
function isValidLogin($username, $password){
	return true;
}
?>
