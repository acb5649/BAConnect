<?php
    require_once "functions.php";

    $msg = "";
    $term = "You must agree to the terms and conditions";

    //just naming the variables now

    $firstName = "Johnny";
    $lastName = "Appleseed";
    $middleName = "";
    $address = "";
    $gender = "Female";
    $phone = "8675309";	//user may have multiple phone numbers

    $password = "1234";
    $username = "";

    $email = "j.appleseed@iu.edu";
    $student = true;	//user can be a student, a working professional, or both
    $working = false;
    $field = "";	//field of profession, if working

    $placeOfEmployment = "IUPUI";
    $jobTitle = "TA";

    $schoolName = "IUPUI";
    $major = "CSCI";
    $graduationYear = 2022;

    $employer = "";	//name of employer, if working
    $numDegrees = 0;
    $Degrees[] = null; //array containing $Degrees
                                    //degrees may need to be objects
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
              schoolNameInput.value = "<?php print $schoolName; ?>"
              schoolNameInput.placeholder = "School Name"
              schoolNameInput.name = "schoolName_" + number
              schoolNameInput.id = "schoolName_" + number
              schoolNameInput.className = "w3-input w3-border"
              parent.appendChild(schoolNameInput)

              var majorInput = document.createElement("input");
              majorInput.type = "text"
              majorInput.maxlength = 50
              majorInput.value = "<?php print $major; ?>"
              majorInput.placeholder = "Major"
              majorInput.name = "major_" + number
              majorInput.id = "major_" + number
              majorInput.className = "w3-input w3-border"
              parent.appendChild(majorInput)

              parent.appendChild(document.createTextNode("Year Graduated:"))

              var graduationYearInput = document.createElement("input")
              graduationYearInput.type = "number"
              graduationYearInput.maxlength = 4
              graduationYearInput.value = <?php print $graduationYear; ?>

              graduationYearInput.name = "gradYear_" + number
              graduationYearInput.id = "gradYear_" + number
              graduationYearInput.className = "w3-input w3-border"
              parent.appendChild(graduationYearInput)

              //<input name = "addEntry" class = "btn" type = "button" value = "Add degree" onclick = "addField()" />

              var deleteInputFieldButton = document.createElement("input")
              deleteInputFieldButton.className = "w3-button w3-pink w3-padding-16 w3-right"
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
        placeOfEmployment.value = "<?php print $placeOfEmployment; ?>"
        placeOfEmployment.placeholder = "Name of Business"
        placeOfEmployment.name = "businessName"
        placeOfEmployment.id = "businessName"
        placeOfEmployment.className = "w3-input w3-border"
        parent.appendChild(placeOfEmployment)

        var jobTitle = document.createElement("input")
        jobTitle.type = "text"
        jobTitle.maxlength = 50
        jobTitle.value = "<?php print $jobTitle; ?>"
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
  <div class="w3-bar w3-pink w3-card">
    <!-- The homepage will have a feed of the newest users and updated users -->
    <a class="w3-bar-item w3-button w3-padding-large">BAConnect</a>
    <a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('editDataModal').style.display='block'">EDIT INFORMATION</a>
  </div>
</div>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">

  <!-- Modals -->
  <div id="editDataModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-pink w3-center w3-padding-32">
        <span onclick="document.getElementById('registerModal').style.display='none'" class="w3-button w3-pink w3-xlarge w3-display-topright">×</span>
        <h2 class="w3-wide"><i class="w3-margin-right"></i>Edit Information</h2>
      </header>
      <form class="w3-container">
        <label>First name</label>
        <input class="w3-input w3-border" type="text" maxlength = "50" value="<?php print $firstName; ?>" name="firstName" id="firstName"/>
        <label>Last name</label>
        <input class="w3-input w3-border" type="text" maxlength = "50" value="<?php print $lastName; ?>" name="lastName" id="lastName"/>
        <label>Email</label>
        <input class="w3-input w3-border" type="text" maxlength = "50" value="<?php print $email; ?>" name="email" id="email"/>
        <label>Password (Must be longer than 12 characters and contains at least 1 digit)</label>
        <input class="w3-input w3-border" type="password" maxlength = "50" value="<?php print $password; ?>" name="pwd" id="pwd"/>
        <label>Gender</label>
        <label>Male</label><input class="w3-radio w3-border" type = "radio" name = "gender" value = "Male" <?php if ($gender == "Male") print 'checked = "checked"'; ?> />
				<label>Female</label><input class="w3-radio w3-border" type = "radio" name = "gender" value = "Female" <?php if ($gender == "Female") print 'checked = "checked"'; ?> />
        <!-- Need to change how country is prepared after there is a backend -->
        <label>Country</label>
        <select class="w3-select w3-border" name = "country">
  				<?php print countryList(); ?>
  			</select>
        <label>Phone number</label>
        <input class="w3-input w3-border" type = "tel" value = "<?php print $phone; ?>" name = "PhoneNumber" />
        <label>Status</label>
        <label>Student</label><input class="w3-check w3-border" type="checkbox" name = "student" value=1 <?php if ($student) print 'checked="checked"'; ?> />
  			<label>Working Professional</label><input class="w3-check w3-border" type="checkbox" name = "Working Professional" value=1 <?php if ($working) print 'checked="checked"'; ?> />
        <label>Education</label>
        <input name = "addEntry" class="w3-button w3-block w3-pink w3-padding-16 w3-section w3-right" type = "button" value = "Add degree" onclick = "addField()" />
  			<fieldset id="education"></fieldset>
        <label>Work</label>
        <fieldset id="work"></fieldset>

        <button class="w3-button w3-block w3-pink w3-padding-16 w3-section w3-right" type="submit">Save<i class="fa fa-check"></i></button>
        <button class="w3-button w3-red w3-section" onclick="document.getElementById('editDataModal').style.display='none'">Close <i class="fa fa-remove"></i></button>
      </form>
    </div>
  </div>

<!-- End Page Content -->
</div>

<script>

// When the user clicks anywhere outside of the modal, close it
var editModal = document.getElementById('editDataModal');
window.onclick = function(event) {
  if (event.target == modal) {
    editModal.style.display = "none";
  }
}
</script>

</body>
</html>