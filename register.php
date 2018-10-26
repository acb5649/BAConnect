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
        $degree[$degreeNum] = new EducationHistoryEntry($_POST['schoolName_' . $degreeNum], $_POST['degreeType_' . $degreeNum], $_POST['major_' . $degreeNum], $_POST['enrollmentYear_' . $degreeNum], $_POST['gradYear_' . $degreeNum]);
    }

    $numJobs = $_POST['numJobs'];
    for ($jobNum = 0; $jobNum < $numJobs; $jobNum++) {
        $work[$jobNum] = new WorkHistoryEntry($_POST['employerName_' . $degreeNum], $_POST['jobTitle' . $degreeNum], $_POST['startYear_' . $degreeNum], $_POST['endYear_' . $degreeNum]);
    }
    // handle files
    $picturePath = $_FILES['profile']['tmp_name'];
    $resumePath = $_FILES['resume']['tmp_name'];

    // verify Information
    if ($password != $confirmedPassword) {
        $error = true;
    }

    if ($error == false) {
        $user = new User($username, $password, $firstName, $middleName, $lastName, $email, $gender, $phone, $status);
        $address = new Address($street, $city, $postcode, $state, $country);
        registerUser($user, $address, $degree, $work, $picturePath, $resumePath);
        header("Location: created.php");
    }
}

?>

<div id="registerModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('registerModal').style.display='none'"
                  class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Register </h2>
        </header>
        <form method='post' action="register.php" enctype='multipart/form-data' class="w3-container">
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
                <h2>Education History</h2>
            </p>
            <fieldset id="education" style="border:0"></fieldset>
            <input name="addDegreeEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="button"
                   value="Add degree..." onclick="addEducationField()"/>
            <input type="hidden" id="numDegs" name="numDegs" value="0">

            <p>
                <h2>Work History</h2>
            </p>
            <fieldset id="work" style="border:0"></fieldset>
            <input name="addJobEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="button"
                   value="Add job..." onclick="addWorkField()"/>
            <input type="hidden" id="numJobs" name="numJobs" value="0">

            <p>
                <label>Profile Picture</label>
            </p>
            <input class="w3-input w3-border" type="file" id="profile" name="profile" accept="image/png, image/jpeg" />

            <p>
                <label>Resume</label>
            </p>
            <input class="w3-input w3-border" type="file" id="resume" name="resume" accept=".doc, .docx, .pdf" />

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
    function removeEducationField(number) {
        document.getElementById("eduContainer_" + number).remove();
        //document.getElementById("eduMember_" + number).remove();
        //document.getElementById("eduBreak_" + number).remove();

        var fieldCount = 0;
        var divs = document.querySelectorAll(".educationMember");
        [].forEach.call(divs, function(div) {
            var newNum = fieldCount.valueOf();
            var oldNumber = div.id.substring(10);
            console.log("old num: " + oldNumber);
            div.id = "eduMember_" + fieldCount;

            var brk = document.getElementById("eduBreak_" + oldNumber);
            brk.id = "eduBreak_" + newNum;

            var cont = document.getElementById("eduContainer_" + oldNumber);
            cont.id = "eduContainer_" + newNum;

            var schoolName = document.getElementById("schoolName_" + oldNumber);
            schoolName.id = "schoolName_" + newNum;
            var majorName = document.getElementById("major_" + oldNumber);
            majorName.id = "major_" + newNum;
            var year = document.getElementById("gradYear_" + oldNumber);
            year.id = "gradYear_" + newNum;
            var button = document.getElementById("eduHeaderSpan_" + oldNumber);
            button.id = "eduHeaderSpan_" + newNum;
            button.onclick = function() {
                removeEducationField(newNum);
            };
            fieldCount = fieldCount + 1;
        });

        document.getElementById("numDegs").value = document.querySelectorAll(".educationMember").length;
    }

    function addEducationField() {
        // Number of inputs to create
        var number = document.querySelectorAll(".educationMember").length;
        // Container <div> where dynamic content will be placed
        var fieldset = document.getElementById("education");
        var container = document.createElement("div");
        container.id = "eduContainer_" + number;
        container.className = "w3-card w3-container w3-display-container w3-margin-top w3-margin-bottom";

        var header = document.createElement("header");
        header.className = "w3-container w3-center";
        var span = document.createElement("span");
        span.className = "w3-button w3-lime w3-xlarge w3-display-topright";
        span.innerHTML = "&times";
        span.id = "eduHeaderSpan_" + number;
        span.onclick = function() {
            removeEducationField(number);
        };
        var label = document.createElement("h3");
        label.innerHTML = "Education Entry";
        header.appendChild(span);
        header.appendChild(label);
        container.appendChild(header);

        // Append a line break
        var brk = document.createElement("br");
        brk.id = "eduBreak_" + number;
        container.appendChild(brk);

        var parent = document.createElement("div");
        parent.className = "educationMember";
        parent.id = "eduMember_" + number;

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

        parent.appendChild(document.createTextNode("Year Enrolled:"));

        var startYearInput = document.createElement("input");
        startYearInput.type = "number";
        startYearInput.maxlength = 4;
        startYearInput.placeholder = "";
        startYearInput.name = "enrollmentYear_" + number;
        startYearInput.id = "enrollmentYear_" + number;
        startYearInput.className = "w3-input w3-border";
        parent.appendChild(startYearInput);

        parent.appendChild(document.createTextNode("Year Graduated:"));

        var graduationYearInput = document.createElement("input");
        graduationYearInput.type = "number";
        graduationYearInput.maxlength = 4;
        graduationYearInput.placeholder = "";
        graduationYearInput.name = "gradYear_" + number;
        graduationYearInput.id = "gradYear_" + number;
        graduationYearInput.className = "w3-input w3-border w3-margin-bottom";
        parent.appendChild(graduationYearInput);

        container.appendChild(parent);
        fieldset.appendChild(container);

        document.getElementById("numDegs").value = number + 1;
    }

    function removeWorkField(number) {
        document.getElementById("workContainer_" + number).remove();

        var fieldCount = 0;
        var divs = document.querySelectorAll(".workMember");
        [].forEach.call(divs, function(div) {
            var newNum = fieldCount.valueOf();
            var oldNumber = div.id.substring(11);
            console.log("old num: " + oldNumber);
            div.id = "workMember_" + fieldCount;

            var brk = document.getElementById("workBreak_" + oldNumber);
            brk.id = "workBreak_" + newNum;

            var cont = document.getElementById("workContainer_" + oldNumber);
            cont.id = "workContainer_" + newNum;

            var employerName = document.getElementById("employerName_" + oldNumber);
            employerName.id = "employerName_" + newNum;

            var jobTitle = document.getElementById("jobTitle_" + oldNumber);
            jobTitle.id = "jobTitle_" + newNum;

            var startYear = document.getElementById("startYear_" + oldNumber);
            startYear.id = "startYear_" + newNum;

            var endYear = document.getElementById("endYear_" + oldNumber);
            endYear.id = "endYear_" + newNum;

            var button = document.getElementById("workHeaderSpan_" + oldNumber);
            button.id = "workHeaderSpan_" + newNum;
            button.onclick = function() {
                removeWorkField(newNum);
            };
            fieldCount = fieldCount + 1;
        });

        document.getElementById("numJobs").value = document.querySelectorAll(".workMember").length;
    }

    function addWorkField() {
        // Number of inputs to create
        var number = document.querySelectorAll(".workMember").length;
        // Container <div> where dynamic content will be placed
        var fieldset = document.getElementById("work");
        var container = document.createElement("div");
        container.id = "workContainer_" + number;
        container.className = "w3-card w3-container w3-display-container w3-margin-top w3-margin-bottom";

        var header = document.createElement("header");
        header.className = "w3-container w3-center";
        var span = document.createElement("span");
        span.className = "w3-button w3-lime w3-xlarge w3-display-topright";
        span.innerHTML = "&times";
        span.id = "workHeaderSpan_" + number;
        span.onclick = function() {
            removeWorkField(number);
        };
        var label = document.createElement("h3");
        label.innerHTML = "Job Entry";
        header.appendChild(span);
        header.appendChild(label);
        container.appendChild(header);

        // Append a line break
        var brk = document.createElement("br");
        brk.id = "workBreak_" + number;
        container.appendChild(brk);

        var parent = document.createElement("div");
        parent.className = "workMember";
        parent.id = "workMember_" + number;

        var employerNameInput = document.createElement("input");
        employerNameInput.type = "text";
        employerNameInput.maxlength = 50;
        employerNameInput.value = "";
        employerNameInput.placeholder = "Name of Employer";
        employerNameInput.name = "employerName_" + number;
        employerNameInput.id = "employerName_" + number;
        employerNameInput.className = "w3-input w3-border";
        parent.appendChild(employerNameInput);

        var jobTitle = document.createElement("input");
        jobTitle.type = "text";
        jobTitle.maxlength = 50;
        jobTitle.value = "";
        jobTitle.placeholder = "Job Title";
        jobTitle.name = "jobTitle_" + number;
        jobTitle.id = "jobTitle_" + number;
        jobTitle.className = "w3-input w3-border";
        parent.appendChild(jobTitle);

        parent.appendChild(document.createTextNode("Year Started:"));

        var startYearInput = document.createElement("input");
        startYearInput.type = "number";
        startYearInput.maxlength = 4;
        startYearInput.placeholder = "";
        startYearInput.name = "startYear_" + number;
        startYearInput.id = "startYear_" + number;
        startYearInput.className = "w3-input w3-border";
        parent.appendChild(startYearInput);

        parent.appendChild(document.createTextNode("Year Ended:"));

        var endYearInput = document.createElement("input");
        endYearInput.type = "number";
        endYearInput.maxlength = 4;
        endYearInput.placeholder = "";
        endYearInput.name = "endYear_" + number;
        endYearInput.id = "endYear_" + number;
        endYearInput.className = "w3-input w3-border w3-margin-bottom";
        parent.appendChild(endYearInput);

        container.appendChild(parent);
        fieldset.appendChild(container);

        document.getElementById("numJobs").value = number + 1;
    }

    function init() {
        addEducationField();
        addWorkField();
    }
</script>