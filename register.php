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
    $street2 = $_POST['street2'];
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
        $address = new Address($street, $street2, $city, $postcode, $state, $country);
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
            <label>Male<input class="w3-radio w3-border" type="radio" name="gender" value="0" checked="checked"/></label>
            <label>Female<input class="w3-radio w3-border" type="radio" name="gender" value="1"/></label>
            <label>Nonbinary<input class="w3-radio w3-border" type="radio" name="gender" value="2"/></label>
            <p>
                <label>Address Line 1</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="street" id="street"/>
            <p>
                <label>Address Line 2</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="street2" id="street2"/>
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