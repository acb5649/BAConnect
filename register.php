<?php
require_once "dbhelper.php";

if (isset($_POST['submit'])) {
    $error = false;
    $msg = "";

    $requiredPOSTFieldNames = array('password', 'confirmedPassword', 'username', 'firstName', 'lastName', 'email', 'gender', 'phoneNumber', 'status', 'preference', 'state', 'country', 'numDegs', 'numJobs');
    $optionalPOSTFieldNames = array('middleName', 'street', 'street2', 'city', 'postcode', 'facebook', 'twitter', 'linkedin');

    foreach ($requiredPOSTFieldNames as $req) {
        if (isset($_REQUEST[$req])) {
            $_SESSION[$req] = Input::str($_POST[$req]);
        } else {
            $error = true;
        }
    }

    foreach ($optionalPOSTFieldNames as $req) {
        if (isset($_REQUEST[$req])) {
            $_SESSION[$req] = Input::str($_POST[$req]);
        } else {
            $_SESSION[$req] = "";
        }
    }

    // Collect Education and Work Histories
    $degree = array();
    for ($degreeNum = 0; $degreeNum < $_SESSION['numDegs']; $degreeNum++) {
        foreach (array('schoolName_' . $degreeNum, 'degreeType_' . $degreeNum, 'major_' . $degreeNum, 'enrollmentYear_' . $degreeNum, 'gradYear_' . $degreeNum) as $req) {
            if (isset($_REQUEST[$req])) {
                $_SESSION[$req] = Input::str($_POST[$req]);
            } else {
                $_SESSION[$req] = "";
            }
        }
        $degree[$degreeNum] = new EducationHistoryEntry($_SESSION['schoolName_' . $degreeNum], $_SESSION['degreeType_' . $degreeNum], $_SESSION['major_' . $degreeNum], $_SESSION['enrollmentYear_' . $degreeNum], $_SESSION['gradYear_' . $degreeNum]);
    }

    $work = array();
    for ($jobNum = 0; $jobNum < $_SESSION['numJobs']; $jobNum++) {
        foreach (array('employerName_' . $degreeNum, 'jobTitle' . $degreeNum, 'startYear_' . $degreeNum, 'endYear_' . $degreeNum) as $req) {
            if (isset($_REQUEST[$req])) {
                $_SESSION[$req] = Input::str($_POST[$req]);
            } else {
                $_SESSION[$req] = "";
            }
        }
        $work[$jobNum] = new WorkHistoryEntry($_SESSION['employerName_' . $degreeNum], $_SESSION['jobTitle' . $degreeNum], $_SESSION['startYear_' . $degreeNum], $_SESSION['endYear_' . $degreeNum]);
    }

    // handle files
    $picturePath = $_FILES['profile']['tmp_name'];
    $resumePath = $_FILES['resume']['tmp_name'];

    // verify Information
    if ($_SESSION['password'] != $_SESSION['confirmedPassword']) {
        $error = true;
        $msg += "\nPasswords do not match.";
    } elseif (!(preg_match('/[A-Za-z]/', $_SESSION['password']) && preg_match('/[0-9]/', $_SESSION['password']))) {
        $error = true;
        $msg += "\nPassword must contain a capital letter and a number.";
    } elseif (strlen($_SESSION['password']) < 12) {
        $error = true;
        $msg += "\nPassword must be 12 or more characters.";
    }

    if ($error == false) {
        $user = new User($_SESSION['username'], $_SESSION['password'], $_SESSION['firstName'], $_SESSION['middleName'], $_SESSION['lastName'], $_SESSION['email'], $_SESSION['gender'], $_SESSION['phoneNumber'], $_SESSION['$status']);
        $address = new Address($_SESSION['street'], $_SESSION['street2'], $_SESSION['city'], $_SESSION['postcode'], $_SESSION['state'], $_SESSION['country']);
        registerUser($user, $address, $degree, $work, $picturePath, $resumePath);
        header("Location: created.php");
    } else {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('action' => 'openModal', 'modal' => 'registerModal'))
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents("http://corsair.cs.iupui.edu:22891/courseproject/index.php", false, $context);
        if ($result === FALSE) { /* Handle error */ }
        header("Location: index.php");
        echo $result;
        print_r($_SESSION);
    }
}

?>

<script>
    function showStates(countryID){
        if(countryID != ""){
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("state").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "AJAX.php?action=refreshState&country=" + countryID, true);
            xmlhttp.send();
        }
    }
</script>

<div id="registerModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('registerModal').style.display='none'"
                  class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Register </h2>
        </header>
        <form method='post' action="register.php" enctype='multipart/form-data' class="w3-container">
            <p>
                <label>First name<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="<?php echo (isset($_SESSION['firstName']) ? $_SESSION['firstName'] : "") ?>" name="firstName" id="firstName" required/>
            <p>
                <label>Middle name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="<?php echo (isset($_SESSION['middleName']) ? $_SESSION['middleName'] : "") ?>" name="middleName" id="middleName"/>
            <p>
                <label>Last name<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="<?php echo (isset($_SESSION['lastName']) ? $_SESSION['lastName'] : "") ?>" name="lastName" id="lastName" required/>
            <p>
                <label>Email<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="<?php echo (isset($_SESSION['email']) ? $_SESSION['email'] : "") ?>" name="email" id="email" required/>
            <p>
                <label>User Name<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="username" id="username" required/>
            <p>
                <label>Password (Must be longer than 12 characters and contains at least 1 digit)<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="password" maxlength="50" value="" name="password" id="password" required/>
            <p>
                <label>Confirm Password<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="password" maxlength="50" value="" name="confirmedPassword" id="confirmedPassword" required/>
            <p>
                <label>Gender<span class="w3-text-red">*</span></label>
            </p>
            <label>Male<input class="w3-radio w3-border" type="radio" name="gender" value="0" checked="checked"/></label>
            <label>Female<input class="w3-radio w3-border" type="radio" name="gender" value="1"/></label>
            <label>Nonbinary<input class="w3-radio w3-border" type="radio" name="gender" value="2"/></label>

            <p>
                <label>Country<span class="w3-text-red">*</span></label>
            </p>
            <select class="w3-select w3-border" name="country" id="country" onchange="showStates(this.value)">
                <?php echo "<option value= '-1'>Please select a Country</option> " . listCountries(); ?>
            </select>

            <p>
                <label>State/Province<span class="w3-text-red">*</span></label>
            </p>
            <select class="w3-select w3-border" name="state" id="state">

            </select>

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
                <label>Postal Code</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="postcode" id="postcode"/>

            <p>
                <label>Phone number<span class="w3-text-red">*</span></label>
            </p>
            <input class="w3-input w3-border" type="tel" value="" name="phoneNumber"/>

            <p>
                <label>Facebook Link</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="facebook" id="facebook"/>
            <p>
                <label>Twitter Link</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="twitter" id="twitter"/>
            <p>
                <label>LinkedIn Link</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="linkedin" id="linkedin"/>

            <p>
                <label>Status<span class="w3-text-red">*</span></label>
            </p>
            <label>Student</label>
            <input class="w3-check w3-border" type="checkbox" name="status" value=0/>
            <label>Working Professional</label>
            <input class="w3-check w3-border" type="checkbox" name="status" value=1/>

            <p>
                <label>Preference</label>
            </p>
            <label>Mentor</label>
            <input class="w3-check w3-border" type="checkbox" name="preference" value=0/>
            <label>Mentee</label>
            <input class="w3-check w3-border" type="checkbox" name="preference" value=1/>
            <label>Not Interested</label>
            <input class="w3-check w3-border" type="checkbox" name="preference" value=1/>

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