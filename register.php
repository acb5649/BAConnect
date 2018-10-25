<?php
require_once "dbhelper.php";

if (isset($_POST['submit'])) {
    $error = false;
    // Collect Account data from Post
    $password = $_POST['password'];
    $confirmedPassword = $_POST['confirmedPassword'];
    $username = $_POST['username'];
    // Collect User data from Post
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phoneNumber'];
    $status = $_POST['status'];
    // Collect Address data from Post
    $street = $_POST['street'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    // Collect Education and Work Histories
    $numDegrees = $_POST['numDegs'];
    for ($degreeNum = 0; $degreeNum < $numDegrees; $degreeNum++) {
        $degree[$degreeNum] = new EducationHistoryEntry($_POST['schoolName_' . $degreeNum], $_POST['degreeType_' . $degreeNum], $_POST['major_' . $degreeNum], $_POST['gradYear_' . $degreeNum]);
    }

    $businessName = $_POST['businessName'];
    $jobTitle = $_POST['jobTitle'];
    $workHistory = new WorkHistoryEntry($businessName, $jobTitle);

    // verify Information
    if ($password != $confirmedPassword) {
        $error = true;
    }


    if ($error == false) {
        $user = new User($username, $password, $firstName, $middleName, $lastName, $email, $gender, $phone, $status);
        $address = new Address($street, $city, $postcode, $state, $country);
        registerUser($user, $address, $degree, $workHistory, "", "");
        header("Location: created.php");
    }

}

if (isset($_POST['upload_picture'])) {

}

if (isset($_POST['upload_resume'])) {

}

?>

<div id="registerModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('registerModal').style.display='none'"
                  class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Register </h2>
        </header>
        <form method='post' action="register.php" class="w3-container">
            <p>
                <label>First name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="firstName" id="firstName"/>
            <p>
                <label>Middle name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="middleName" id="middleName"/>
            <p>
                <label>Last name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="lastName" id="lastName"/>
            <p>
                <label>Email</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="email" id="email"/>
            <p>
                <label>User Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="username" id="username"/>
            <p>
                <label>Password (Must be longer than 12 characters and contains at least 1 digit)</label>
            </p>
            <input class="w3-input w3-border" type="password" maxlength="50" value="" name="password" id="password"/>
            <p>
                <label>Confirm Password</label>
            </p>
            <input class="w3-input w3-border" type="password" maxlength="50" value="" name="confirmedPassword"
                   id="confirmedPassword"/>
            <p>
                <label>Gender</label>
            </p>
            <label>Male</label>
            <input class="w3-radio w3-border" type="radio" name="gender" value="0" checked="checked"/>
            <label>Female</label>
            <input class="w3-radio w3-border" type="radio" name="gender" value="1"/>
            <label>Nonbinary</label>
            <input class="w3-radio w3-border" type="radio" name="gender" value="2"/>
            <p>
                <label>Address Line 1</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="street" id="street"/>
            <p>
                <label>City</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="city" id="city"/>
            <p>
                <label>State/province</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="state" id="state"/>
            <p>
                <label>Postal Code</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="postcode" id="postcode"/>
            <p>
                <label>Country</label>
            </p>
            <select class="w3-select w3-border" name="country" id="country">
                <?php print listCountries(); ?>
            </select>
            <p>
                <label>Phone number</label>
            </p>
            <input class="w3-input w3-border" type="tel" value="" name="phoneNumber"/>
            <p>
                <label>Status</label>
            </p>
            <label>Student</label>
            <input class="w3-check w3-border" type="checkbox" name="status" value=0/>
            <label>Working Professional</label>
            <input class="w3-check w3-border" type="checkbox" name="status" value=1/>
            <p>
                <label>Education</label>
            </p>
            <input name="addEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="button"
                   value="Add degree" onclick="addField()"/>
            <fieldset id="education"></fieldset>
            <input type="hidden" id="numDegs" name="numDegs" value="0">
            <p>
                <label>Work History</label>
            </p>
            <fieldset id="work"></fieldset>
            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="submit" name="submit">
                Register
                <i class="fa fa-check"></i>
            </button>
            <button type="button" class="w3-button w3-red w3-section"
                    onclick="document.getElementById('registerModal').style.display='none'">Close
                <i class="fa fa-remove"></i>
            </button>
        </form>
    </div>
</div>
<script>
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