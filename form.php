<?php
	require_once "functions.php";

	$msg = "";
	$term = "You must agree to the terms and conditions";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- Name: Eric Strayer -->
<!-- Date: 9/16/2018 -->
<!-- File Name: form.php -->

<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Functions Demo</title>
	<style type = "text/css">
  		h1, h2 {
    		text-align: center;
  		}
	</style>

	</head>

	<body>

		<?php
			if (isset($_POST['enter']))
			{
				//just naming the variables now

				$firstName = "";
				$lastName = "";
				$middleName = "";
				$address = "";
				$gender = "";
				$phone = "";	//user may have multiple phone numbers

				$password = "";
				$username = "";

				$email = "";
				$student = false;	//user can be a student, a working professional, or both
				$working = false;
				$field = "";	//field of profession, if working
				$employer = "";	//name of employer, if working
				$numDegrees = 0;
				$Degrees[] = null; //array containing $Degrees
												//degrees may need to be objects
			}
		?>

		<form action="form.php" method="post">
			<?php
				print $msg;
				$msg = "";
			?>
			<br />

			First Name: <input type="text" maxlength = "50" value="" name="firstName" id="firstName"   /> <br />
			Last Name: <input type="text" maxlength = "50" value="" name="lastName" id="lastName"   />  <br />

			Email: <input type="text" maxlength = "50" value="" name="email" id="email"   /> <br />
		<!--	Confirm Email: <input type="text" maxlength = "50" value="" name="cemail" id="email"   /> <br /> -->

			Password: <input type="text" maxlength = "50" value="" name="pwd" id="pwd"   />(Must be longer than 12 characters and contains at least 1 digit) <br />
		<!--	Confirm Password: <input type="text" maxlength = "50" value="" name="confirmPwd" id="confirmPwd"   /> <br /> -->
			Gender:
				<input type = "radio" name = "gender" value = "Male" checked = "checked" />Male
				<input type = "radio" name = "gender" value = "Female" />Female <br />
			Country:
			<select  name = "country">
				<?php print countryList(); ?>
			</select>

			<br />

			Phone number:
			<input type = "tel" value = "" name = "PhoneNumber" />
			<br />

			Status:
			<br />

			<input type="checkbox" name = "student" value=1 />
			student
			<br />
			<input type="checkbox" name = "Working Professional" value=1 />
			Working Professional
			<br />

			Education:
			<br />
			Degree:
			<input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "document.write('<?php MakeDegreeEntry()?>');" />
			<fieldset>
				<select name = "DegreeType">
				 	<?php print DegreeTypeList(); ?>
				</select>

				<input type = "text" maxlength = "50" value = "" placeholder = "School Name" name = "School" id = "School" />
				<input type = "text" value = "" placeholder = "Major" name = "Major" id = "Major" />
				Year Graduated:
				<input type = "number" maxlength = "4" value = "2022" name = "gradYear" id = "gradYear" />
		</fieldset>
			<br />
			<?php
			//	if(isset($_POST['addEntry'])){
		//			MakeDegreeEntry();
		//		}
			?>
			<br />
			<input name="enter" class="btn" type="submit" value="Submit" />
		</form>
	</body>
</html>
