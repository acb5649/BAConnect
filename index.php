<?php
    require_once "functions.php";

    $msg = "";
    $term = "You must agree to the terms and conditions";

    if (isset($_POST['enter'])) {
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
    <!-- template from: https://www.w3schools.com/w3css/w3css_templates.asp -->
    <!DOCTYPE html>
    <html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta content="utf-8" http-equiv="encoding">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Themed Demo</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type='text/javascript'>
            function removeField(number) {
                document.getElementById("member_" + number).remove()
            }

            function addField() {
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
                select.className = "w3-select w3-border"
                select.innerHTML = '
                <?php print DegreeTypeList(); ?>
                '
                parent.appendChild(select)

                var schoolNameInput = document.createElement("input");
                schoolNameInput.type = "text"
                schoolNameInput.maxlength = 50
                schoolNameInput.value = ""
                schoolNameInput.placeholder = "School Name"
                schoolNameInput.name = "schoolName_" + number
                schoolNameInput.id = "schoolName_" + number
                schoolNameInput.className = "w3-input w3-border"
                parent.appendChild(schoolNameInput)

                var majorInput = document.createElement("input");
                majorInput.type = "text"
                majorInput.maxlength = 50
                majorInput.value = ""
                majorInput.placeholder = "Major"
                majorInput.name = "major_" + number
                majorInput.id = "major_" + number
                majorInput.className = "w3-input w3-border"
                parent.appendChild(majorInput)

                parent.appendChild(document.createTextNode("Year Graduated:"))

                var graduationYearInput = document.createElement("input")
                graduationYearInput.type = "number"
                graduationYearInput.maxlength = 4
                graduationYearInput.value = 2022
                graduationYearInput.name = "gradYear_" + number
                graduationYearInput.id = "gradYear_" + number
                graduationYearInput.className = "w3-input w3-border"
                parent.appendChild(graduationYearInput)

                // < input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "addField()" />

                var deleteInputFieldButton = document.createElement("input")
                deleteInputFieldButton.className = "w3-button w3-lime w3-padding-16 w3-right"
                deleteInputFieldButton.type = "button"
                deleteInputFieldButton.value = "Remove Degree"
                deleteInputFieldButton.onclick = function() {
                    removeField(number);
                }
                parent.appendChild(deleteInputFieldButton)

                container.appendChild(parent);
            }

            function createWork() {
                var container = document.getElementById("work");

                var parent = document.createElement("div")
                parent.className = "WorkSection"
                parent.id = "WorkSection"

                var placeOfEmployment = document.createElement("input")
                placeOfEmployment.type = "text"
                placeOfEmployment.maxlength = 50
                placeOfEmployment.value = ""
                placeOfEmployment.placeholder = "Name of Business"
                placeOfEmployment.name = "businessName"
                placeOfEmployment.id = "businessName"
                placeOfEmployment.className = "w3-input w3-border"
                parent.appendChild(placeOfEmployment)

                var jobTitle = document.createElement("input")
                jobTitle.type = "text"
                jobTitle.maxlength = 50
                jobTitle.value = ""
                jobTitle.placeholder = "Job Title"
                jobTitle.name = "jobTitle"
                jobTitle.id = "jobTitle"
                jobTitle.className = "w3-input w3-border"
                parent.appendChild(jobTitle)

                container.appendChild(parent);
            }

            function init() {
                addField();
                createWork();
            }
        </script>
    </head>

    <body onload="init();">
        <!-- Navbar -->
        <div class="w3-top">
            <div class="w3-bar w3-lime w3-card">
                <!-- The homepage will have a feed of the newest users and updated users -->
                <a class="w3-bar-item w3-button w3-padding-large">BAConnect</a>
                <!-- If user is logged in, this link becomes a link to the user's profile -->
                <a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('loginModal').style.display='block'">LOG IN</a>
                <!-- If user is logged in, don't show this link -->
                <a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('registerModal').style.display='block'">REGISTER</a>
                <a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('forgotModal').style.display='block'">FORGOT LOGIN</a>
				
				<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('matchModal').style.display='block'">MATCH USERS</a>
				<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('editModal').style.display='block'">EDIT ACCOUNTS </a>
				<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('upgradeModal').style.display='block'">UPGRADE ACCOUNTS</a>
				<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('searchModal').style.display='block'">USER SEARCH</a>
				
				
                <!-- Admin login button -->
                <a href="javascript:void(0)" class="w3-padding-large w3-hover-red w3-hide-small w3-right">
                    <i class="fa fa-cogs"></i>
                </a>
            </div>
        </div>
        <!-- Page content -->
        <div class="w3-content" style="max-width:2000px;margin-top:46px">
            <!-- Modals -->
            <?php include "login.php";?>
			<?php include "register.php";?>
			<?php include "forgot.php";?>
			<?php include "match.php";?>
			<?php include "edit.php";?>
			<?php include "upgrade.php";?>
			<?php include "search.php";?>
            <!-- End Page Content -->
        </div>
    </body>

    </html>