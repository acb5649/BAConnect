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
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
	<title>Functions Demo</title>
	<style type = "text/css">
  		h1, h2 {
    		text-align: center;
  		}
	</style>
	<script type='text/javascript'>
            function removeField(number) {
                document.getElementById("member_" + number).remove()
            }

        	function addField(){
           	 // Number of inputs to create
                var number = document.querySelectorAll(".educationMember").length;
            	// Container <div> where dynamic content will be placed
            	var container = document.getElementById("education");
                // Append a line break
                if (number != 0) {
                    container.appendChild(document.createElement("br"));
                }
                
                var parent = document.createElement("div")
                parent.className = "educationMember"
                parent.id = "member_" + number
                
                var select = document.createElement("select")
                select.innerHTML = '<?php print DegreeTypeList(); ?>'
                parent.appendChild(select)
                
                var schoolNameInput = document.createElement("input");
                schoolNameInput.type = "text"
                schoolNameInput.maxlength = 50
                schoolNameInput.value = ""
                schoolNameInput.placeholder = "School Name"
                schoolNameInput.name = "schoolName_" + number
                schoolNameInput.id = "schoolName_" + number
                parent.appendChild(schoolNameInput)
                
                var majorInput = document.createElement("input");
                majorInput.type = "text"
                majorInput.maxlength = 50
                majorInput.value = ""
                majorInput.placeholder = "Major"
                majorInput.name = "major_" + number
                majorInput.id = "major_" + number
                parent.appendChild(majorInput)
                
                parent.appendChild(document.createTextNode("Year Graduated:"))
                
                var graduationYearInput = document.createElement("input")
                graduationYearInput.type = "number"
                graduationYearInput.maxlength = 4
                graduationYearInput.value = 2022
                graduationYearInput.name = "gradYear_" + number
                graduationYearInput.id = "gradYear_" + number
                parent.appendChild(graduationYearInput)
                
                //<input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "addField()" />
                
                var deleteInputFieldButton = document.createElement("input")
                deleteInputFieldButton.className = "btn"
                deleteInputFieldButton.type = "button"
                deleteInputFieldButton.value = "Remove Degree"
                deleteInputFieldButton.onclick = function(){removeField(number);}
                parent.appendChild(deleteInputFieldButton)
                
                container.appendChild(parent);
        }
    </script>

	</head>

	<body onload="addField();">

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
			<input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "addField()" />
			<fieldset id="education"></fieldset>
			<br />
			<input name="enter" class="btn" type="submit" value="Submit" />
		</form>
	</body>
</html>
