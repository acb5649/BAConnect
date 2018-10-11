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

function MakeDegreeEntry() {
	echo '

	<fieldset>
		<select name = "DegreeType">
			<?php print DegreeTypeList(); ?>
		</select>

		<input type = "text" maxlength = "50" value = "" placeholder = "School Name" name = "School" id = "School" />
		<input type = "text" value = "" placeholder = "Major" name = "Major" id = "Major" />
		Year Graduated:
		<input type = "number" maxlength = "4" value = "2022" name = "gradYear" id = "gradYear" />
	</fieldset>

	';
}
//isValidLogin checks if the username and password given correspond to an account.
//returns false if there is no account with the given username and password.
function isValidLogin($username, $password){
	return true;
}
?>
