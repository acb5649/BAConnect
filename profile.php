<?php
require_once "session.php";
require_once "database.php";

if (isset($_POST['submit'])) {

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    $file_name = $_FILES['profile']['name'];
    $file_size = $_FILES['profile']['size'];
    $file_tmp = $_FILES['profile']['tmp_name'];
    $file_type = $_FILES['profile']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['profile']['name'])));

    $target = $image_dir_path . DIRECTORY_SEPARATOR . $file_name;
    move_uploaded_file($file_tmp, $target);

    registerNewPicture($_SESSION["account_ID"], $target);
    header("location: profile.php");
}

if (isset($_GET['user'])) {
    if (isset($_SESSION["account_ID"])) {
        if ($_SESSION["account_ID"] == $_GET['user']) {
            header("location: profile.php");
        }
    }
    $account_id = $_GET['user'];
    $allowEdit = FALSE;
} elseif (isset($_SESSION["account_ID"])) {
    $account_id = $_SESSION["account_ID"];
    $allowEdit = TRUE;
} else {
    header("location: index.php");
}

function makeEditable($allowEdit, $id) {
    if ($allowEdit) {
        return ' <a class="w3-button w3-display-right" onclick="enterEditState(\'' . $id . '\');"><i class="fa fa-pencil fa-fw w3-large w3-text-lime w3-opacity"></i></a>';
    } else {
        return "";
    }
}

function makeHistoryElementEditable($allowEdit, $id) {
    if ($allowEdit) {
        return ' <a class="w3-button w3-display-topright w3-margin" onclick="enterHistoryElementEditState(\'' . $id . '\');"><i class="fa fa-pencil fa-fw w3-large w3-text-lime w3-opacity"></i></a>';
    } else {
        return "";
    }
}

function putItInASpan($thing) {
    return "<span>" . $thing . "</span>";
}

function formatDegrees($degrees) {
    $result = "";
    foreach($degrees as $degree) {
        $result .= '<div class="w3-container"><h5 class="w3-opacity"><b>';
        $result .= $degree[1] . " / " . $degree[0];
        $result .= '</b></h5><h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>';
        $result .= $degree[3] . " - " . $degree[2];
        $result .= '</h6><hr></div>';
    }
    return $result;
}

function formatDegreesEditable($degrees) {
    $result = '<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>';
    $result .= '<button name="addDegree" class="w3-button w3-third w3-lime w3-section">Add Degree</button>';
    $result .= '<button name="cancel" class="w3-button w3-third w3-red w3-section" onclick="exitHistoryElementEditState(\'degrees\');">Cancel</button>';
    foreach($degrees as $degree) {
        $result .= '<form method="post" class="w3-container w3-text-grey" action="updateProfile.php">';
        $result .= '<p><span>Degree Type:</span></p>';
        $result .= '<select name="degreeType" id="degreeType" class="w3-select w3-border">' . listDegreeTypes() . "</select>";
        $result .= '<p><span>Major:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $degree[1] . '" name="major"/>';
        $result .= '<p><span>University/College:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $degree[0] . '" name="school"/>';
        $result .= '<p><span>Enrollment Year:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $degree[3] . '" name="start"/>';
        $result .= '<p><span>Graduation Year:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $degree[2] . '" name="end"/>';
        $result .= '<input type="hidden" id="degree_ID" name="degree_ID" value="' . ' $degree[4] ' . '">';
        $result .= '<button type="submit" name="submit" class="w3-button w3-third w3-lime w3-section">Edit</button>';
        $result .= '<button type="button" class="w3-button w3-third w3-red w3-section" onclick="">Delete</button>';
        $result .= '<hr></form>';
    }
    return $result;
}

function formatJobsEditable($jobs) {
    $result = '<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>';
    $result .= '<button name="addJob" class="w3-button w3-third w3-lime w3-section" onclick="addEmptyJob();">Add Job</button>';
    $result .= '<button name="cancel" class="w3-button w3-third w3-red w3-section" onclick="exitHistoryElementEditState(\'jobs\');">Cancel</button>';
    foreach($jobs as $job) {
        $result .= '<form method="post" class="w3-container w3-text-grey" action="updateProfile.php">';
        $result .= '<p><span>Company:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[1] . '" name="employer"/>';
        $result .= '<p><span>Job Title/Field:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[0] . '" name="title"/>';
        $result .= '<p><span>Start Year:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[2] . '" name="start"/>';
        $result .= '<p><span>End Year:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[3] . '" name="end"/>';
        $result .= '<input type="hidden" id="degree_ID" name="job_ID" value="' . $job[4] . '">';
        $result .= '<button type="submit" name="submit" class="w3-button w3-third w3-lime w3-section">Edit</button>';
        $result .= '<button type="button" class="w3-button w3-third w3-red w3-section" onclick="">Delete</button>';
        $result .= '<hr></form>';
    }
    return $result;
}

function formatJobs($jobs) {
    $result = "";
    foreach ($jobs as $job) {
        $result .= '<div class="w3-container"><h5 class="w3-opacity"><b>';
        $result .= $job[1] . " / " . $job[0];
        $result .= '</b></h5><h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>';
        $result .= $job[2] . " - " . $job[3];
        $result .= '</h6><hr></div>';
    }
    return $result;
}
?>
<!-- template from: https://www.w3schools.com/w3css/w3css_templates.asp -->
<!DOCTYPE html>
<html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAConnect Profile</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="js/closeModals.js"></script>
    <script>
        // Used to toggle the menu on small screens when clicking on the menu button
        function toggleNav() {
            let x = document.getElementById("navMobile");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        
        function init() {

        }

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

        function enterHistoryElementEditState(id) {
            if (id == "jobs") {
                document.getElementById(id).innerHTML = `<?php echo formatJobsEditable(getJobs($account_id)); ?>`;
            } else if (id == "degrees") {
                document.getElementById(id).innerHTML = `<?php echo formatDegreesEditable(getDegrees($account_id)); ?>`;
            }

        }

        function exitHistoryElementEditState(id) {
            if (id == "jobs") {
                document.getElementById(id).innerHTML = `<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2><?php echo formatJobs(getJobs($account_id)) . makeHistoryElementEditable($allowEdit, "jobs"); ?>`;
            } else if (id == "degrees") {
                document.getElementById(id).innerHTML = `<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2><?php echo formatDegrees(getDegrees($account_id)) . makeHistoryElementEditable($allowEdit, "degrees"); ?>`;
            }
        }

        function addEmptyJob() {
            document.getElementById("jobs").innerHTML += `
            <form method="post" class="w3-container w3-text-grey" action="updateProfile.php">
            <p><span>Company:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="employer"/>
            <p><span>Job Title/Field:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="title"/>
            <p><span>Start Year:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="start"/>
            <p><span>End Year:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="end"/>
            <input type="hidden" id="degree_ID" name="job_ID" value="-1">
            <input type="hidden" id="account_ID" name="account_ID" value="<?php echo $account_id ?>">
            <button type="submit" name="submit" class="w3-button w3-third w3-lime w3-section">Edit</button>
            <button type="button" class="w3-button w3-third w3-red w3-section" onclick="">Delete</button>
            <hr></form>`;
        }

        function exitEditState(id) {
            document.getElementById(id).classList.remove("w3-cell-row");
            if (id == "gender") {
                document.getElementById(id).innerHTML = `<i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getGender($account_id)) . makeEditable($allowEdit, "gender")?>`;
            } else if (id == "status") {
                document.getElementById(id).innerHTML = `<i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getStatus($account_id)) . makeEditable($allowEdit, "status")?>`;
            } else if (id == "email") {
                document.getElementById(id).innerHTML = `<i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getEmail($account_id)) . makeEditable($allowEdit, "email")?>`;
            } else if (id == "phone") {
                document.getElementById(id).innerHTML = `<i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getPhoneNumber($account_id)) . makeEditable($allowEdit, "phone")?>`;
            } else if (id == "location") {
                document.getElementById(id).innerHTML = `<i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getApproximateLocation($account_id)) . makeEditable($allowEdit, "location")?>`;
                document.getElementById("countrySpan").innerHTML = `<i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getCountry($account_id))?>`;
            } else if (id == "facebook") {
                document.getElementById(id).innerHTML = `<i class="fa fa-facebook-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getFacebookLink($account_id)) . makeEditable($allowEdit, "facebook")?>`;
            } else if (id == "linkedin") {
                document.getElementById(id).innerHTML = `<i class="fa fa-linkedin-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getLinkedinLink($account_id)) . makeEditable($allowEdit, "linkedin")?>`;
            } else if (id == "preference") {
                document.getElementById(id).innerHTML = `<i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getUserMentorshipPreference($account_id)) . makeEditable($allowEdit, "preference")?>`;
            }
        }

        function enterEditState(id) {
            document.getElementById(id).className += " w3-cell-row";
            if (id == "gender") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i>Gender:</p>
                    <form method="post" action="updateProfile.php">
                    <select class="w3-select w3-border w3-cell" name="gender" id="gender">
                        <option value="0"> Male </option>
                        <option value="1"> Female </option>
                        <option value="2"> Nonbinary/Other </option>
                    </select>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Gender</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('gender');">Cancel</button>
                    </form>`;
            } else if (id == "status") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i>Status:</p>
                    <form method="post" action="updateProfile.php">
                    <select class="w3-select w3-border w3-cell" name="status" id="status">
                        <option value="0"> Student </option>
                        <option value="1"> Working Professional </option>
                    </select>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Status</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('status');">Cancel</button>
                    </form>`;
            } else if (id == "email") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i>Email:</p>
                    <form method="post" action="updateProfile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getEmail($account_id); ?>" name="email" id="email"/>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Email</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('email');">Cancel</button>
                    </form>`;
            } else if (id == "phone") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i>Phone Number:</p>
                    <form method="post" action="updateProfile.php">
                    <input class="w3-input w3-border w3-cell" type="tel" value="<?php echo getPhoneNumber($account_id); ?>" name="phone"/>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Phone</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('phone');">Cancel</button>
                    </form>`;
            } else if (id == "location") {
                document.getElementById(id).innerHTML = `
                    <form method="post" action="updateProfile.php">

                    <p><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i>Country:</p>
                    <select class="w3-select w3-border w3-cell" name="country" id="country" onchange="showStates(this.value);">
                        <?php echo listCountries($account_id) ?>
                    </select>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Address Line 1:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getAddressLine1($account_id); ?>" name="addr1"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Address Line 2:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getAddressLine2($account_id); ?>" name="addr2"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>City:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getCity($account_id); ?>" name="city"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>State:</p>
                    <select class="w3-select w3-border" name="state" id="state">
                        <?php echo getStatesList(getCountryID($account_id), $account_id); ?>
                    </select>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Post code:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getPostCode($account_id); ?>" name="postcode"/>

                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Location</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('location');">Cancel</button>
                    </form>`;
                document.getElementById("countrySpan").innerHTML = " ";
            } else if (id == "facebook") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-facebook-square fa-fw w3-margin-right w3-large w3-text-lime"></i>Facebook:</p>
                    <form method="post" action="updateProfile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getFacebookLink($account_id); ?>" name="fb" id="fb"/>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Facebook</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('facebook');">Cancel</button>
                    </form>`;
            } else if (id == "linkedin") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-linkedin-square fa-fw w3-margin-right w3-large w3-text-lime"></i>Linkedin:</p>
                    <form method="post" action="updateProfile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getLinkedinLink($account_id); ?>" name="li" id="li"/>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Linkedin</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('linkedin');">Cancel</button>
                    </form>`;
            } else if (id == "preference") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-lime"></i>Mentorship Preference:</p>
                    <form method="post" action="updateProfile.php">
                    <select class="w3-select w3-border w3-cell" name="preference" id="preference">
                        <option <?php if(getUserMentorshipPreference($account_id) == "Mentor"){echo("selected");}?> value="0"> Mentor </option>
                        <option <?php if(getUserMentorshipPreference($account_id) == "Mentee"){echo("selected");}?> value="1"> Mentee </option>
                        <option <?php if(getUserMentorshipPreference($account_id) == "Not Interested"){echo("selected");}?> value="2"> Not Interested </option>
                    </select>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Preference</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('preference');">Cancel</button>
                    </form>`;
            }
        }
    </script>
</head>

<body class="w3-light-grey" onload="init();">
<!-- Navbar -->
<?php include "header.php"; ?>
<!-- Page content -->

<div class="w3-content" style="max-width:1400px;">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <!-- Left Column -->
        <div class="w3-third">

            <div class="w3-white w3-text-grey w3-card-4">
                <div class="w3-display-container">
                    <img src="<?php echo file_get_contents("http://corsair.cs.iupui.edu:22891/courseproject/image.php?account_id=" . $account_id); ?>" style="width:100%;" alt="Avatar">
                    <div class="w3-display-middle w3-display-hover w3-xlarge">
                        <?php if ($allowEdit) { echo "<button class=\"w3-button w3-black\" onclick=\"document.getElementById('uploadPicModal').style.display='block'\">Change Picture...</button>";} ?>
                    </div>
                    <div class="w3-display-bottomleft w3-container w3-text-black">
                        <h2 class="w3-text-white" style="text-shadow:1px 1px 0 #444"><?php echo getName($account_id) ?></h2>
                    </div>
                </div>
                <div class="w3-container">
                    <p class="w3-display-container" id="preference"><i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getUserMentorshipPreference($account_id)) . makeEditable($allowEdit, "preference")?></p>
                    <hr>
                    <p class="w3-display-container" id="gender"><i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getGender($account_id)) . makeEditable($allowEdit, "gender")?></p>
                    <p class="w3-display-container" id="status"><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getStatus($account_id)) . makeEditable($allowEdit, "status")?></p>
                    <p class="w3-display-container" id="location"><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getApproximateLocation($account_id)) . makeEditable($allowEdit, "location")?></p>
                    <p class="w3-display-container" id="countrySpan"><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getCountry($account_id))?></p>
                    <hr>
                    <p class="w3-display-container" id="email"><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getEmail($account_id)) . makeEditable($allowEdit, "email")?></p>
                    <p class="w3-display-container" id="phone"><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getPhoneNumber($account_id)) . makeEditable($allowEdit, "phone")?></p>
                    <hr>
                    <p class="w3-display-container" id="facebook"><i class="fa fa-facebook-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getFacebookLink($account_id)) . makeEditable($allowEdit, "facebook")?></p>
                    <p class="w3-display-container" id="linkedin"><i class="fa fa-linkedin-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getLinkedinLink($account_id)) . makeEditable($allowEdit, "linkedin")?></p>
                    <hr>

                    <button class="w3-button w3-block w3-dark-grey">+ Connect</button>
                    <br>
                </div>
            </div><br>

            <!-- End Left Column -->
        </div>

        <!-- Right Column -->
        <div class="w3-twothird">

            <div id="jobs" class="w3-container w3-display-container w3-card w3-white w3-margin-bottom">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>
                <?php echo formatJobs(getJobs($account_id)) . makeHistoryElementEditable($allowEdit, "jobs"); ?>
            </div>

            <div id="degrees" class="w3-container w3-display-container w3-card w3-white">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>
                <?php echo formatDegrees(getDegrees($account_id)) . makeHistoryElementEditable($allowEdit, "degrees"); ?>
            </div>

            <!-- End Right Column -->
        </div>

        <!-- End Grid -->
    </div>

<?php if ($allowEdit) { echo "
<div id=\"uploadPicModal\" class=\"w3-modal\">
        <div class=\"w3-modal-content w3-animate-top w3-card-4\">
            <header class=\"w3-container w3-lime w3-center w3-padding-32\">
            <span onclick=\"document.getElementById('uploadPicModal').style.display='none'\"
                  class=\"w3-button w3-lime w3-xlarge w3-display-topright\">×</span>
                <h2 class=\"w3-wide\"><i class=\"w3-margin-right\"></i>Change Profile Picture </h2>
            </header>
            <form method=\"post\" action=\"profile.php\" enctype='multipart/form-data' class=\"w3-container\">
                <p>
                    <label>
                        <i class=\"fa fa-user\"></i> New picture:
                    </label>
                </p>
                <input class=\"w3-input w3-border\" type=\"file\" placeholder=\"\" name=\"profile\" id=\"profile\" accept=\"image/png, image/jpeg\">
                <button class=\"w3-button w3-block w3-lime w3-padding-16 w3-section w3-right\" type=\"submit\" name=\"submit\">
                    Submit New Photo
                    <i class=\"fa fa-check\"></i>
                </button>
                <button type=\"button\" class=\"w3-button w3-red w3-section\"
                        onclick=\"document.getElementById('uploadPicModal').style.display='none'\">Close
                    <i class=\"fa fa-remove\"></i>
                </button>
            </form>
        </div>
    </div>";} ?>

    <!-- End Page Container -->
</div>
<!-- End Page Content -->
</body>
</html>
