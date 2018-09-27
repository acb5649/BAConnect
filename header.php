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
              select.className = "w3-select w3-border"
              select.innerHTML = '<?php print DegreeTypeList(); ?>'
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

              //<input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "addField()" />

              var deleteInputFieldButton = document.createElement("input")
              deleteInputFieldButton.className = "w3-button w3-lime w3-padding-16 w3-right"
              deleteInputFieldButton.type = "button"
              deleteInputFieldButton.value = "Remove Degree"
              deleteInputFieldButton.onclick = function(){removeField(number);}
              parent.appendChild(deleteInputFieldButton)

              container.appendChild(parent);
      }

    function createWork(){
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

    function init(){
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
    <!-- Admin login button -->
    <a href="javascript:void(0)" class="w3-padding-large w3-hover-red w3-hide-small w3-right"><i class="fa fa-cogs"></i></a>
  </div>
</div>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">

  <!-- Modals -->
  <div id="loginModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
        <span onclick="document.getElementById('loginModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
        <h2 class="w3-wide"><i class="w3-margin-right"></i>Log In</h2>
      </header>
      <form class="w3-container">
        <p><label><i class="fa fa-user"></i> Username or Email</label></p>
        <input class="w3-input w3-border" type="text" placeholder="">
        <p><label><i class="fa fa-lock"></i> Password</label></p>
        <input class="w3-input w3-border" type="password" placeholder="">
        <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right">Log In <i class="fa fa-check"></i></button>
        <button class="w3-button w3-red w3-section" onclick="document.getElementById('loginModal').style.display='none'">Close <i class="fa fa-remove"></i></button>
        <p class="w3-right">Need an <a href="#" class="w3-text-blue" onclick="document.getElementById('registerModal').style.display='block'">account?</a></p>
      </form>
    </div>
  </div>

  <div id="registerModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
        <span onclick="document.getElementById('registerModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
        <h2 class="w3-wide"><i class="w3-margin-right"></i>Register</h2>
      </header>
      <form class="w3-container">
        <label>First name</label>
        <input class="w3-input w3-border" type="text" maxlength = "50" value="" name="firstName" id="firstName"/>
        <label>Last name</label>
        <input class="w3-input w3-border" type="text" maxlength = "50" value="" name="lastName" id="lastName"/>
        <label>Email</label>
        <input class="w3-input w3-border" type="text" maxlength = "50" value="" name="email" id="email"/>
        <label>Password (Must be longer than 12 characters and contains at least 1 digit)</label>
        <input class="w3-input w3-border" type="password" maxlength = "50" value="" name="pwd" id="pwd"/>
        <label>Gender</label>
        <label>Male</label><input class="w3-radio w3-border" type = "radio" name = "gender" value = "Male" checked = "checked"/>
				<label>Female</label><input class="w3-radio w3-border" type = "radio" name = "gender" value = "Female"/>
        <label>Country</label>
        <select class="w3-select w3-border" name = "country">
  				<?php print countryList(); ?>
  			</select>
        <label>Phone number</label>
        <input class="w3-input w3-border" type = "tel" value = "" name = "PhoneNumber" />
        <label>Status</label>
        <label>Student</label><input class="w3-check w3-border" type="checkbox" name = "student" value=1 />
  			<label>Working Professional</label><input class="w3-check w3-border" type="checkbox" name = "Working Professional" value=1 />
        <label>Education</label>
        <input name = "addEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "button" value = "Add degree" onclick = "addField()" />
  			<fieldset id="education"></fieldset>
        <label>Work</label>
        <input name = "addWorkEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "button" value = "Add empoyment" onclick = "addWork()" />
        <fieldset id="work"></fieldset>

        <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="submit">Register <i class="fa fa-check"></i></button>
        <button class="w3-button w3-red w3-section" onclick="document.getElementById('registerModal').style.display='none'">Close <i class="fa fa-remove"></i></button>
      </form>
    </div>
  </div>

  <div id="forgotModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
        <span onclick="document.getElementById('forgotModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
        <h2 class="w3-wide"><i class="w3-margin-right"></i>Reset Password</h2>
      </header>
      <form class="w3-container">
        <p><label><i class="fa fa-user"></i> Username or Email</label></p>
        <input class="w3-input w3-border" type="text" placeholder="">
        <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right">Reset Password <i class="fa fa-check"></i></button>
        <button class="w3-button w3-red w3-section" onclick="document.getElementById('forgotModal').style.display='none'">Close <i class="fa fa-remove"></i></button>
        <p class="w3-right">Need an <a href="#" class="w3-text-blue" onclick="document.getElementById('registerModal').style.display='block'">account?</a></p>
      </form>
    </div>
  </div>

<!-- End Page Content -->
</div>

<script>

// When the user clicks anywhere outside of the modal, close it
var loginModal = document.getElementById('loginModal');
window.onclick = function(event) {
  if (event.target == modal) {
    loginModal.style.display = "none";
  }
}
var regModal = document.getElementById('registerModal');
window.onclick = function(event) {
  if (event.target == modal) {
    regModal.style.display = "none";
  }
}
var forgModal = document.getElementById('forgotModal');
window.onclick = function(event) {
  if (event.target == modal) {
    forgModal.style.display = "none";
  }
}
</script>

</body>
</html>
