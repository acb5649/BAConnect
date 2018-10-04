<?php
				
					require_once "functions.php";
					
					//just naming the variables now
					$fNameReg = "";
					$lNameReg = "";
					$mNameReg = "";
					$address = "";
					$gender = "";
					$phone = "";	//user may have multiple phone numbers
					$passReg = "";
					$passConReg = "";
					$uNameReg = "";
					$emailReg = "";
					$emailConReg = "";
					$student = false;	//user can be a student, a working professional, or both
					$working = false;
					$field = "";	//field of profession, if working
					$employer = "";	//name of employer, if working
					$numDegrees = 0;
					$Degrees[] = null; //array containing $Degrees
                                                //degrees may need to be objects
					if (isset($_POST['enter'])) {
					}
					
?>
<form id="regUser"  action="login.php" method="post">
	
	<!-- First Name -->
	<label class = "txt">First Name: </label>
	<input type="text" maxlength = "50" value="" name="firstNameReg" id="firstNameReg" placeholder="Enter First Name" class="tbx"  required> <br><br>
					
	<!-- Middle Name 
	<h3 class = "txt">Middle Name:</h3>
	<input type = "text" maxlength = "50" value = "" name = "middleNameReg" id = "middleNameReg" placeholder="Enter Middle Name" class="tbx" required><br>
	-->				
	<!-- Last Name-->
	<label class = "txt">Last Name: </label>
	<input type="text" maxlength = "50" value="" name="lastNameReg" id="lastNameReg" placeholder="Enter Last Name" class="tbx" required><br><br>
					
	<!-- Email -->
	<label class = "txt">Email: </label> 
	<input type="email" id="emailReg" placeholder="Enter Email" name="emailReg"   class="tbx" required><br><br>
					
	<!-- Confirm Email -->
	<label class = "txt">Confirm Email: </label>
	<input type="email" id="emailConfirmReg" placeholder="Confirm Email" name="emailConfirmReg"  class="tbx" required><br><br>
					
	<!--Username -->
	<label class = "txt">Username: </label> 
	<input type = "text" id="usernameReg" name = "usernameReg" maxlength ="50" placeholder="Enter Username" class="tbx" required><br><br>
					
	<!--Password-->				
	<label class = "txt">Password: </label>
	<input type="password" id="passwordReg" placeholder="Enter Password" name="passwordReg" class="tbx" required><br><br>
	
	<!-- Confirm Password-->
	<label class = "txt">Confirm Password: </label>
	<input type="password" id="passwordConfirmReg" placeholder="Enter Password" name="passwordConfirmReg" class="tbx" required><br><br>
	
	<!--
	<h3 class = "txt">Gender:</h3>
	<div id="genderReg" style="margin-top: 1em;">
		<input type="radio" id="male" name="radio" checked= "checked" value="Male"><label for="male">Male</label>
		<input type="radio" id="female" name="radio"><label for="female" value="Female">Female</label>
		<input type="radio" id="transexual" name="radio"><label for="transexual" value="Transexual">Transexual</label>
	</div><br>
	
	<h3 class = "txt">Country:</h3>
	<select id="country" name = "country">
		<?php 
			//populate list
			$list = countryList();
			//using foreach to assign the label to a variable and the value to a variable
			foreach ($list as $pos => $value)
				print '	<option value = "'.$value.'">'.$value.'</option>';
		?>
	</select><br>
	-->
	<!--Telephone-->
	<label class = "txt">Phone Number: </label>
	<input type="tel" id="tele" placeholder="(317) 444 6954" name="tele" class="tbx" required><br><br>
	<!--
	<div class="cbox">
		<h3 class = "txt">Status:</h3>
		<h3 class"cbox"><input type="checkbox"   name = "student" value="1">Student</h3>
		<h3 class"cbox"><input type="checkbox"  name = "Working Professional" value="1">Working Professional</h3>
	</div>
	
	<h3 class = "txt">Education:</h3><br>
	<div>
		<button id="addDeg" name = "addEntry"  type = "button" value = "Add degree" onclick = "addField()">Add Degree/Certification</button>
	<div><br>
	<fieldset id="education"></fieldset><br> -->
					
	<!--
					

					

					Status:
					<br>
					<input type="checkbox" name = "student" value="1">
					student
					<br>
					<input type="checkbox" name = "Working Professional" value="1">
					Working Professional
					<br>
					Education:
					<br>
					Degree:
					<input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "addField()">
					<fieldset id="education"></fieldset>
					<br>-->
					
					<!-- Submit -->
					
		<br>
		<div align = "right">
			<button name="enter" type="submit" class="btn" id="submit">Register</button>
		</div>
	</form>
	