<?php
    require_once "session.php";
    require_once "database.php";
    require_once "card.php";


	if (isset($_SESSION['type'])) {
		$type = $_SESSION['type'];
	} else {
        $type=0;
    }

	echo $type;

?>
    <!-- template from: https://www.w3schools.com/w3css/w3css_templates.asp -->
    <!DOCTYPE html>
    <html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BAConnect Home</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type='text/javascript'>
            function removeField(number) {
                console.log("Removing field " + number);

                document.getElementById("member_" + number).remove();
                document.getElementById("break_" + number).remove();

                var fieldCount = 0;
                var divs = document.querySelectorAll(".educationMember");
                [].forEach.call(divs, function(div) {
                  var newNum = fieldCount.valueOf();
                  var oldNumber = div.id.substring(7);
                  div.id = "member_" + fieldCount;

                  var brk = document.getElementById("break_" + oldNumber);
                  brk.id = "break_" + newNum;
                  var schoolName = document.getElementById("schoolName_" + oldNumber);
                  schoolName.id = "schoolName_" + newNum;
                  var majorName = document.getElementById("major_" + oldNumber);
                  majorName.id = "major_" + newNum;
                  var year = document.getElementById("gradYear_" + oldNumber);
                  year.id = "gradYear_" + newNum;
                  var button = document.getElementById("deleteButton_" + oldNumber);
                  button.id = "deleteButton_" + newNum;
                  button.onclick = function() {
                      console.log("deleting new number: " + newNum);
                      removeField(newNum);
                  };
                  fieldCount = fieldCount + 1;
                });

                document.getElementById("numDegs").value = document.querySelectorAll(".educationMember").length;
            }

            function addField() {
                // Number of inputs to create
                var number = document.querySelectorAll(".educationMember").length;
                // Container <div> where dynamic content will be placed
                var container = document.getElementById("education");
                // Append a line break
                if (number != 0) {
                    var brk = document.createElement("br");
                    brk.id = "break_" + number;
                    container.appendChild(brk);
                }

                var parent = document.createElement("div");
                parent.className = "educationMember";
                parent.id = "member_" + number;

                var select = document.createElement("select");
                select.name = "degreeType_" + number;
                select.id = "degreeType_" + number;
                select.className = "w3-select w3-border";
                select.innerHTML = '<?php print listDegreeTypes(); ?>';

                parent.appendChild(select);

                var schoolNameInput = document.createElement("input");
                schoolNameInput.type = "text";
                schoolNameInput.maxlength = 50;
                schoolNameInput.value = "";
                schoolNameInput.placeholder = "School Name";
                schoolNameInput.name = "schoolName_" + number;
                schoolNameInput.id = "schoolName_" + number;
                schoolNameInput.className = "w3-input w3-border";
                parent.appendChild(schoolNameInput);

                var majorInput = document.createElement("input");
                majorInput.type = "text";
                majorInput.maxlength = 50;
                majorInput.value = "";
                majorInput.placeholder = "Major";
                majorInput.name = "major_" + number;
                majorInput.id = "major_" + number;
                majorInput.className = "w3-input w3-border";
                parent.appendChild(majorInput);

                parent.appendChild(document.createTextNode("Year Graduated:"));

                var graduationYearInput = document.createElement("input");
                graduationYearInput.type = "number";
                graduationYearInput.maxlength = 4;
                graduationYearInput.value = 2022;
                graduationYearInput.name = "gradYear_" + number;
                graduationYearInput.id = "gradYear_" + number;
                graduationYearInput.className = "w3-input w3-border";
                parent.appendChild(graduationYearInput);

                var deleteInputFieldButton = document.createElement("input");
                deleteInputFieldButton.className = "w3-button w3-lime w3-padding-16 w3-right";
                deleteInputFieldButton.type = "button";
                deleteInputFieldButton.value = "Remove Degree";
                deleteInputFieldButton.id = "deleteButton_" + number;
                deleteInputFieldButton.onclick = function() {
                    console.log("deleting: " + number);
                    removeField(number);
                };
                parent.appendChild(deleteInputFieldButton);

                container.appendChild(parent);

                document.getElementById("numDegs").value = number + 1;
            }

            function createWork() {
                var container = document.getElementById("work");

                var parent = document.createElement("div");
                parent.className = "WorkSection";
                parent.id = "WorkSection";

                var placeOfEmployment = document.createElement("input");
                placeOfEmployment.type = "text";
                placeOfEmployment.maxlength = 50;
                placeOfEmployment.value = "";
                placeOfEmployment.placeholder = "Name of Business";
                placeOfEmployment.name = "businessName";
                placeOfEmployment.id = "businessName";
                placeOfEmployment.className = "w3-input w3-border";
                parent.appendChild(placeOfEmployment);

                var jobTitle = document.createElement("input");
                jobTitle.type = "text";
                jobTitle.maxlength = 50;
                jobTitle.value = "";
                jobTitle.placeholder = "Job Title";
                jobTitle.name = "jobTitle";
                jobTitle.id = "jobTitle";
                jobTitle.className = "w3-input w3-border";
                parent.appendChild(jobTitle);

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
                <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="toggleNav()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
                <!-- The homepage will have a feed of the newest users and updated users -->
                <a class="w3-bar-item w3-button w3-padding-large">BAConnect</a>
                <!-- If user is logged in, don't show this link -->
				<?php
					$block="'block'";
					$firstBlock="";

					if($type==1){
						$prof= "'profileModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$prof.').style.display='.$block.'">PROFILE</a>';
					}else{
						$log= "'loginModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$log.').style.display='.$block.'">LOG IN</a>';
					}

					//Hide upon login
					if($type <= 0){
						$reg= "'registerModal'";
						$forgot = "'forgotModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$reg.').style.display='.$block.'">REGISTER</a>';
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$forgot.').style.display='.$block.'">FORGOT LOGIN</a>';

					}

                if($type >=1){
                    print '<a class="w3-bar-item w3-button w3-hover-red w3-padding-large w3-hide-small w3-right" href="logout.php">LOG OUT</a>';
                }

					if($type > 1){
						$match="'matchModal'";
						$edit="'editModal'";
						$upgrade="'upgradeModal'";
						$search="'searchModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$match.').style.display='.$block.'">MATCH USERS</a>';
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$edit.').style.display='.$block.'">EDIT ACCOUNTS </a>';
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$upgrade.').style.display='.$block.'">UPGRADE ACCOUNTS</a>';
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('.$search.').style.display='.$block.'">USER SEARCH</a>';
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" href="addCountry.php">ADD COUNTRY</a>';
						print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" href="addDegreeType.php">ADD DEGREE TYPE</a>';
					}
				  ?>

            </div>
        </div>

        <div id="navMobile" class="w3-bar-block w3-black w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
			<?php
					$block="'block'";
					$firstBlock="";

					if($type==1){
						$prof= "'profileModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('.$prof.').style.display='.$block.'">PROFILE</a>';
					}else{
						$log= "'loginModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('.$log.').style.display='.$block.'">LOG IN</a>';
					}

					//Hide upon login
					if($type <= 0){
						$reg= "'registerModal'";
						$forgot = "'forgotModal'";
						print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('.$reg.').style.display='.$block.'">REGISTER</a>';
						print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('.$forgot.').style.display='.$block.'">FORGOT LOGIN</a>';

					}

				if($type >=1){
                    print '<a class="w3-bar-item w3-button w3-padding-large" href="logout.php">LOG OUT</a>';
				}

				if($type > 1){
					$match="'matchModal'";
					$edit="'editModal'";
					$upgrade="'upgradeModal'";
					$search="'searchModal'";
					print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="toggleNav();document.getElementById('.$match.').style.display='.$block.'">MATCH USERS</a>';
					print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="toggleNav();document.getElementById('.$edit.').style.display='.$block.'">EDIT ACCOUNTS </a>';
					print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="toggleNav();document.getElementById('.$upgrade.').style.display='.$block.'">UPGRADE ACCOUNTS</a>';
					print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="toggleNav();document.getElementById('.$search.').style.display='.$block.'">USER SEARCH</a>';
					print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" href="addCountry.php">ADD COUNTRY</a>';
					print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" href="addDegreeType.php">ADD DEGREE TYPE</a>';
				}


			?>
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

            <div class="w3-row-padding" id="mentorDisplay">
              <?php for ($k = 0 ; $k < 15; $k++) {
    $card = createCard(0);
    echo '<div class="w3-col s4 w3-center">
                '.$card.'
                </div>';
};?>
            </div>
            <!-- End Page Content -->
        </div>
    </body>
    <script>
    // Used to toggle the menu on small screens when clicking on the menu button
function toggleNav() {
    var x = document.getElementById("navMobile");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}
    </script>
</html>
