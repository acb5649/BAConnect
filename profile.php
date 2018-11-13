<?php
require_once "session.php";
require_once "database.php";

if (isset($_REQUEST['user']) && isset($_SESSION["account_ID"])) {
    if ($_SESSION["account_ID"] == $_REQUEST['user']) {
        $profile_account_id = $_SESSION["account_ID"];
        if (isset($_REQUEST["action"])) {
            // we're handling a request, don't redirect!

            $allowEdit = TRUE;
        } else {
            //User is on own profile, they get edit privleges.
            header("location: profile.php");
            $allowEdit = TRUE;
        }
    } elseif (getAccountTypeFromAccountID($_SESSION["account_ID"]) > 1) {
        // accessing user is an admin, they get edit privleges too.
        $profile_account_id = $_REQUEST['user'];
        $allowEdit = TRUE;
    } else {
        // a normal user is looking at another user's profile, no editing.
        $profile_account_id = $_REQUEST['user'];
        $allowEdit = FALSE;
    }
} elseif (isset($_SESSION["account_ID"])) {
    $profile_account_id = $_SESSION["account_ID"];
    $allowEdit = FALSE;
} else {
    if (isset($_REQUEST['user'])) {
        $profile_account_id = $_REQUEST['user'];
        $allowEdit = FALSE;
    } else {
        header("location: index.php");
        $allowEdit = FALSE;
    }
}

if (isset($_POST['submit']) && isset($_FILES['profile'])) {
    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    $file_name = $_FILES['profile']['name'];
    $file_size = $_FILES['profile']['size'];
    $file_tmp = $_FILES['profile']['tmp_name'];
    $file_type = $_FILES['profile']['type'];
    $file_ext = strtolower(end(explode('.',$_FILES['profile']['name'])));

    $target = $image_dir_path . DIRECTORY_SEPARATOR . $file_name;
    move_uploaded_file($file_tmp, $target);

    registerNewPicture($profile_account_id, $target);
    header("location: profile.php?user=" . $profile_account_id);
} elseif (isset($_POST['submit'])) {
    $con = Connection::connect();

    if (isset($_POST['gender'])) {
        $stmt = $con->prepare("UPDATE Information set gender = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['gender'], PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST['status'])) {
        $stmt = $con->prepare("UPDATE Information set status = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['status'], PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST['email'])) {
        $stmt = $con->prepare("UPDATE Information set email_address = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['email'], PDO::PARAM_STR);
        $stmt->execute();
    }

    if (isset($_POST['phone'])) {
        $stmt = $con->prepare("UPDATE `Phone Numbers` set phone_number = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['phone'], PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST['addr1']) && isset($_POST['addr2']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['postcode']) && isset($_POST['country'])) {
        $address = new Address($_POST['addr1'], $_POST['addr2'], $_POST['city'], $_POST['postcode'], $_POST['state'], $_POST['country']);

        $old_address_id = getAddressIDFromAccount($profile_account_id);
        $stmt = $con->prepare("UPDATE `Address History` set end = CURRENT_TIMESTAMP where address_id = ?");
        $stmt->bindValue(1, $old_address_id, PDO::PARAM_INT);
        $stmt->execute();

        updateUserAddress($profile_account_id, $address);
    }

    if (isset($_POST['fb'])) {
        $stmt = $con->prepare("UPDATE Information set facebook = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['fb'], PDO::PARAM_STR);
        $stmt->execute();
    }

    if (isset($_POST['li'])) {
        $stmt = $con->prepare("UPDATE Information set linkedin = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['li'], PDO::PARAM_STR);
        $stmt->execute();
    }

    if (isset($_POST['preference'])) {
        $stmt = $con->prepare("UPDATE Information set mentorship_preference = ? where account_ID = '" . $profile_account_id . "'");
        $stmt->bindValue(1, $_POST['preference'], PDO::PARAM_STR);
        $stmt->execute();
    }

    if (isset($_POST['job_ID'])) {
        if (isset($_POST['delete'])) {
            $stmt = $con->prepare("DELETE FROM `Job History` where job_ID = ?");
            $stmt->bindValue(1, $_POST['job_ID'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            if ($_POST['job_ID'] == -1) {
                // adding new degree
                $stmt = $con->prepare("insert into `Job History` (`account_ID`, employer, profession_field, `start`, `end`) values (?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $profile_account_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $_POST['employer'], PDO::PARAM_STR);
                $stmt->bindValue(3, $_POST['title'], PDO::PARAM_STR);
                $stmt->bindValue(4, $_POST['start'], PDO::PARAM_INT);
                $stmt->bindValue(5, $_POST['end'], PDO::PARAM_INT);

                $stmt->execute();
            } else {
                $stmt = $con->prepare("UPDATE `Job History` set employer = ?, profession_field = ?, start = ?, `end` = ? where job_ID = ?");
                $stmt->bindValue(1, $_POST['employer'], PDO::PARAM_STR);
                $stmt->bindValue(2, $_POST['title'], PDO::PARAM_STR);
                $stmt->bindValue(3, $_POST['start'], PDO::PARAM_INT);
                $stmt->bindValue(4, $_POST['end'], PDO::PARAM_INT);
                $stmt->bindValue(5, $_POST['job_ID'], PDO::PARAM_INT);

                $stmt->execute();
            }
        }
    }

    if (isset($_POST['degree_ID'])) {
        if (isset($_POST['delete'])) {
            $stmt = $con->prepare("DELETE FROM `Degrees` where degree_ID = ?");
            $stmt->bindValue(1, $_POST['degree_ID'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            if ($_POST['degree_ID'] == -1) {
                // adding new degree
                $stmt = $con->prepare("insert into Degrees (account_ID, degree_type_ID, school, major, graduation_year, enrollment_year) values (?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $profile_account_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $_POST['degreeType'], PDO::PARAM_INT);
                $stmt->bindValue(3, $_POST['school'], PDO::PARAM_STR);
                $stmt->bindValue(4, $_POST['major'], PDO::PARAM_STR);
                $stmt->bindValue(5, $_POST['end'], PDO::PARAM_INT);
                $stmt->bindValue(6, $_POST['start'], PDO::PARAM_INT);

                $stmt->execute();
            } else {
                $stmt = $con->prepare("UPDATE `Degrees` set degree_type_ID = ?, school = ?, major = ?, graduation_year = ?, enrollment_year = ? where degree_ID = ?");
                $stmt->bindValue(1, $_POST['degreeType'], PDO::PARAM_INT);
                $stmt->bindValue(2, $_POST['school'], PDO::PARAM_STR);
                $stmt->bindValue(3, $_POST['major'], PDO::PARAM_STR);
                $stmt->bindValue(4, $_POST['end'], PDO::PARAM_INT);
                $stmt->bindValue(5, $_POST['start'], PDO::PARAM_INT);
                $stmt->bindValue(6, $_POST['degree_ID'], PDO::PARAM_INT);

                $stmt->execute();
            }
        }
    }

    $con = null;
    header("location: profile.php?user=" . $profile_account_id);
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addEmptyJob") {
    echo '  <form method="post" class="w3-container w3-text-grey" action="profile.php">
            <p><span>Company:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="employer"/>
            <p><span>Job Title/Field:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="title"/>
            <p><span>Start Year:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="start"/>
            <p><span>End Year:</span></p>
            <input class="w3-input w3-border" type="text" value="" name="end"/>
            <input type="hidden" id="degree_ID" name="job_ID" value="-1">
            <input type="hidden" id="account_ID" name="account_ID" value="' . $profile_account_id . '">
            <input type="hidden" id="user" name="user" value="' . $_REQUEST['user'] . '">
            <button type="submit" name="submit" class="w3-button w3-third w3-lime w3-section">Save</button>
            <button type="button" class="w3-button w3-third w3-red w3-section" onclick="">Delete</button>
            <hr></form>';
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addEmptyDegree") {
    echo '<form method="post" class="w3-container w3-text-grey" action="profile.php"><p><span>Degree Type:</span></p><select name="degreeType" id="degreeType" class="w3-select w3-border">' . listDegreeTypes() . '</select><p><span>Major:</span></p><input class="w3-input w3-border" type="text" value="" name="major"/><p><span>University/College:</span></p><input class="w3-input w3-border" type="text" value="" name="school"/><p><span>Enrollment Year:</span></p><input class="w3-input w3-border" type="text" value="" name="start"/><p><span>Graduation Year:</span></p><input class="w3-input w3-border" type="text" value="" name="end"/><input type="hidden" id="degree_ID" name="degree_ID" value="-1"><input type="hidden" id="user" name="user" value="' . $_REQUEST['user'] . '"><button type="submit" name="submit" class="w3-button w3-third w3-lime w3-section">Save</button><button type="button" class="w3-button w3-third w3-red w3-section" onclick="">Delete</button><hr></form>';
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getFormattedDegrees") {
    echo '<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>' . formatDegrees(getDegrees($profile_account_id)) . makeHistoryElementEditable($allowEdit, "degrees");
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getFormattedJobs") {
    echo '<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>' . formatJobs(getJobs($profile_account_id)) . makeHistoryElementEditable($allowEdit, "jobs");
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getEditableFormattedDegrees") {
    echo formatDegreesEditable(getDegrees($profile_account_id), $profile_account_id);
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getEditableFormattedJobs") {
    echo formatJobsEditable(getJobs($profile_account_id), $profile_account_id);
    die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "handlePendingRequest") {
    $user = $_REQUEST['user'];
    $pending = $_REQUEST['pending'];
    $response = $_REQUEST['response'];

    $success = pendingMentorshipResponse($user, $pending, $response);
    if ($success) {
        echo formatPendingMentorships($profile_account_id);
        die();
    } else {
        die();
    }
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

function formatDegreesEditable($degrees, $profile_account_ID) {
    $result = '<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>';
    $result .= '<button name="addDegree" class="w3-button w3-third w3-lime w3-section" onclick="addEmptyDegree();">Add Degree</button>';
    $result .= '<button name="cancel" class="w3-button w3-third w3-red w3-section" onclick="exitHistoryElementEditState(\'degrees\');">Cancel</button>';
    foreach($degrees as $degree) {
        $result .= '<form method="post" class="w3-container w3-text-grey" action="profile.php">';
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
        $result .= '<input type="hidden" id="degree_ID" name="degree_ID" value="' . $degree[4] . '">';
        $result .= '<input type="hidden" id="user" name="user" value="' . $profile_account_ID . '">';
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

function formatJobsEditable($jobs, $profile_account_ID) {
    $result = '<h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>';
    $result .= '<button name="addJob" class="w3-button w3-third w3-lime w3-section" onclick="addEmptyJob();">Add Job</button>';
    $result .= '<button name="cancel" class="w3-button w3-third w3-red w3-section" onclick="exitHistoryElementEditState(\'jobs\');">Cancel</button>';
    foreach($jobs as $job) {
        $result .= '<form method="post" class="w3-container w3-text-grey" action="profile.php">';
        $result .= '<p><span>Company:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[0] . '" name="employer"/>';
        $result .= '<p><span>Job Title/Field:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[1] . '" name="title"/>';
        $result .= '<p><span>Start Year:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[2] . '" name="start"/>';
        $result .= '<p><span>End Year:</span></p>';
        $result .= '<input class="w3-input w3-border" type="text" value="' . $job[3] . '" name="end"/>';
        $result .= '<input type="hidden" id="job_ID" name="job_ID" value="' . $job[4] . '">';
        $result .= '<input type="hidden" id="user" name="user" value="' . $profile_account_ID . '">';
        $result .= '<button type="submit" name="submit" class="w3-button w3-third w3-lime w3-section">Edit</button>';
        $result .= '<button type="button" class="w3-button w3-third w3-red w3-section" onclick="">Delete</button>';
        $result .= '<hr></form>';
    }
    return $result;
}

function formatMentorships($profile_account_id) {
    $current = getCurrentMentorships($profile_account_id);
    $ended = getEndedMentorships($profile_account_id);

    $result = '<table id="mentorship_history_table"><thead><tr><th>Mentor</th><th>Mentee</th><th>Began</th><th>Ended</th></tr></thead><tbody>';

    foreach($current as $cur) {
        $result .= "<tr>";
        $result .= "<th>" . $cur['mentor_ID'] . "</th>";
        $result .= "<th>" . $cur['mentee_ID'] . "</th>";
        $result .= "<th>" . $cur['start'] . "</th>";
        $result .= "<th>" . $cur['end'] . "</th>";
        $result .= "</tr>";
    }

    foreach($ended as $cur) {
        $result .= "<tr>";
        $result .= "<th>" . $cur['mentor_ID'] . "</th>";
        $result .= "<th>" . $cur['mentee_ID'] . "</th>";
        $result .= "<th>" . $cur['start'] . "</th>";
        $result .= "<th>" . $cur['end'] . "</th>";
        $result .= "</tr>";
    }

    $result .= '</tbody></table>';
    return $result;
}

function formatPendingMentorships($profile_account_id) {
    $pending = getPendingMentorships($profile_account_id);


    $result = '<table id="pending_mentorship_history_table"><thead><tr><th>Mentor</th><th>Mentee</th><th>Approve Request</th><th>Delete Request</th></tr></thead><tbody>';

    foreach($pending as $cur) {

        $id = $cur['pending_ID'];

        $accept = '<button name="accept" class="w3-button w3-third w3-lime w3-section" onclick="handlePendingMentorship(\'' . $id . '\', 1);">Accept</button>';
        $decline = '<button name="decline" class="w3-button w3-third w3-red w3-section" onclick="handlePendingMentorship(\'' . $id . '\', 0);">Decline</button>';

        $result .= "<tr>";
        $result .= "<th>" . $cur['mentor_ID'] . "</th>";
        $result .= "<th>" . $cur['mentee_ID'] . "</th>";
        $result .= "<th>" . $accept . "</th>";
        $result .= "<th>" . $decline . "</th>";
        $result .= "</tr>";
    }

    $result .= '</tbody></table>';
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
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

        $(document).ready( function () {
            $('#mentorship_history_table').DataTable({
                "paging":   false,
                "ordering": false,
                "info":     false,
                "searching":   false
            });
            $('#pending_mentorship_history_table').DataTable({
                "paging":   false,
                "ordering": false,
                "info":     false,
                "searching":   false
            });
        });

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

        function handlePendingMentorship(pending_id, accept = 0) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    $('#pending_mentorship_history_table').DataTable().destroy();
                    document.getElementById("pending_content").innerHTML = this.responseText;
                    $('#pending_mentorship_history_table').DataTable({
                        "paging":   false,
                        "ordering": false,
                        "info":     false,
                        "searching":   false
                    });
                }
            };

            xmlhttp.open("POST", "profile.php?action=handlePendingRequest&user=<?php echo $profile_account_id?>&pending=" + pending_id+ "&response=" +  accept, true);
            xmlhttp.send();
        }

        function sendMentorshipRequest() {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){

                }
            };

            xmlhttp.open("POST", "profile.php?action=sendMentorshipRequest&user=<?php echo $profile_account_id?>", true);
            xmlhttp.send();
        }

        function enterHistoryElementEditState(id) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById(id).innerHTML = this.responseText;
                }
            };

            if (id == "jobs") {
                xmlhttp.open("GET", "profile.php?action=getEditableFormattedJobs&user=<?php echo $profile_account_id?>" , true);
            } else if (id == "degrees") {
                xmlhttp.open("GET", "profile.php?action=getEditableFormattedDegrees&user=<?php echo $profile_account_id?>", true);
            }

            xmlhttp.send();
        }

        function exitHistoryElementEditState(id) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById(id).innerHTML = this.responseText;
                }
            };

            if (id == "jobs") {
                xmlhttp.open("GET", "profile.php?action=getFormattedJobs&user=<?php echo $profile_account_id?>", true);
            } else if (id == "degrees") {
                xmlhttp.open("GET", "profile.php?action=getFormattedDegrees&user=<?php echo $profile_account_id?>", true);
            }

            xmlhttp.send();
        }

        function addEmptyJob() {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("jobs").innerHTML += this.responseText;
                }
            };
            xmlhttp.open("GET", "profile.php?action=addEmptyJob&user=<?php echo $profile_account_id?>", true);
            xmlhttp.send();
        }

        function addEmptyDegree() {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("degrees").innerHTML += this.responseText;
                }
            };
            xmlhttp.open("GET", "profile.php?action=addEmptyDegree&user=<?php echo $profile_account_id?>", true);
            xmlhttp.send();
        }

        function exitEditState(id) {
            document.getElementById(id).classList.remove("w3-cell-row");
            if (id == "gender") {
                document.getElementById(id).innerHTML = `<i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getGender($profile_account_id)) . makeEditable($allowEdit, "gender")?>`;
            } else if (id == "status") {
                document.getElementById(id).innerHTML = `<i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getStatus($profile_account_id)) . makeEditable($allowEdit, "status")?>`;
            } else if (id == "email") {
                document.getElementById(id).innerHTML = `<i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getEmail($profile_account_id)) . makeEditable($allowEdit, "email")?>`;
            } else if (id == "phone") {
                document.getElementById(id).innerHTML = `<i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getPhoneNumber($profile_account_id)) . makeEditable($allowEdit, "phone")?>`;
            } else if (id == "location") {
                document.getElementById(id).innerHTML = `<i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getApproximateLocation($profile_account_id)) . makeEditable($allowEdit, "location")?>`;
                document.getElementById("countrySpan").innerHTML = `<i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getCountry($profile_account_id))?>`;
            } else if (id == "facebook") {
                document.getElementById(id).innerHTML = `<i class="fa fa-facebook-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getFacebookLink($profile_account_id)) . makeEditable($allowEdit, "facebook")?>`;
            } else if (id == "linkedin") {
                document.getElementById(id).innerHTML = `<i class="fa fa-linkedin-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getLinkedinLink($profile_account_id)) . makeEditable($allowEdit, "linkedin")?>`;
            } else if (id == "preference") {
                document.getElementById(id).innerHTML = `<i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getUserMentorshipPreference($profile_account_id)) . makeEditable($allowEdit, "preference")?>`;
            }
        }

        function enterEditState(id) {
            document.getElementById(id).className += " w3-cell-row";
            if (id == "gender") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i>Gender:</p>
                    <form method="post" action="profile.php">
                    <select class="w3-select w3-border w3-cell" name="gender" id="gender">
                        <option value="0"> Male </option>
                        <option value="1"> Female </option>
                        <option value="2"> Nonbinary/Other </option>
                    </select>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Gender</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('gender');">Cancel</button>
                    </form>`;
            } else if (id == "status") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i>Status:</p>
                    <form method="post" action="profile.php">
                    <select class="w3-select w3-border w3-cell" name="status" id="status">
                        <option value="0"> Student </option>
                        <option value="1"> Working Professional </option>
                    </select>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Status</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('status');">Cancel</button>
                    </form>`;
            } else if (id == "email") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i>Email:</p>
                    <form method="post" action="profile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getEmail($profile_account_id); ?>" name="email" id="email"/>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Email</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('email');">Cancel</button>
                    </form>`;
            } else if (id == "phone") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i>Phone Number:</p>
                    <form method="post" action="profile.php">
                    <input class="w3-input w3-border w3-cell" type="tel" value="<?php echo getPhoneNumber($profile_account_id); ?>" name="phone"/>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Phone</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('phone');">Cancel</button>
                    </form>`;
            } else if (id == "location") {
                document.getElementById(id).innerHTML = `
                    <form method="post" action="profile.php">

                    <p><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i>Country:</p>
                    <select class="w3-select w3-border w3-cell" name="country" id="country" onchange="showStates(this.value);">
                        <?php echo listCountries($profile_account_id) ?>
                    </select>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Address Line 1:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getAddressLine1($profile_account_id); ?>" name="addr1"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Address Line 2:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getAddressLine2($profile_account_id); ?>" name="addr2"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>City:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getCity($profile_account_id); ?>" name="city"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>State:</p>
                    <select class="w3-select w3-border" name="state" id="state">
                        <?php echo getStatesList(getCountryID($profile_account_id), $profile_account_id); ?>
                    </select>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Post code:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getPostCode($profile_account_id); ?>" name="postcode"/>

                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">

                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Location</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('location');">Cancel</button>
                    </form>`;
                document.getElementById("countrySpan").innerHTML = " ";
            } else if (id == "facebook") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-facebook-square fa-fw w3-margin-right w3-large w3-text-lime"></i>Facebook:</p>
                    <form method="post" action="profile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getFacebookLink($profile_account_id); ?>" name="fb" id="fb"/>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Facebook</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('facebook');">Cancel</button>
                    </form>`;
            } else if (id == "linkedin") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-linkedin-square fa-fw w3-margin-right w3-large w3-text-lime"></i>Linkedin:</p>
                    <form method="post" action="profile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getLinkedinLink($profile_account_id); ?>" name="li" id="li"/>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Linkedin</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="exitEditState('linkedin');">Cancel</button>
                    </form>`;
            } else if (id == "preference") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-lime"></i>Mentorship Preference:</p>
                    <form method="post" action="profile.php">
                    <select class="w3-select w3-border w3-cell" name="preference" id="preference">
                        <option <?php if(getUserMentorshipPreference($profile_account_id) == "Mentor"){echo("selected");}?> value="0"> Mentor </option>
                        <option <?php if(getUserMentorshipPreference($profile_account_id) == "Mentee"){echo("selected");}?> value="1"> Mentee </option>
                        <option <?php if(getUserMentorshipPreference($profile_account_id) == "Not Interested"){echo("selected");}?> value="2"> Not Interested </option>
                    </select>
                    <input type="hidden" id="user" name="user" value="<?php echo $profile_account_id; ?>">
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
                    <img src="<?php echo file_get_contents("http://corsair.cs.iupui.edu:22891/courseproject/image.php?account_id=" . $profile_account_id); ?>" style="width:100%;" alt="Avatar">
                    <div class="w3-display-middle w3-display-hover w3-xlarge">
                        <?php if ($allowEdit) { echo "<button class=\"w3-button w3-black\" onclick=\"document.getElementById('uploadPicModal').style.display='block'\">Change Picture...</button>";} ?>
                    </div>
                    <div class="w3-display-bottomleft w3-container w3-text-black">
                        <h2 class="w3-text-white" style="text-shadow:1px 1px 0 #444"><?php echo getName($profile_account_id) ?></h2>
                    </div>
                </div>
                <div class="w3-container">
                    <p class="w3-display-container" id="preference"><i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getUserMentorshipPreference($profile_account_id)) . makeEditable($allowEdit, "preference")?></p>
                    <hr>
                    <p class="w3-display-container" id="gender"><i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getGender($profile_account_id)) . makeEditable($allowEdit, "gender")?></p>
                    <p class="w3-display-container" id="status"><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getStatus($profile_account_id)) . makeEditable($allowEdit, "status")?></p>
                    <p class="w3-display-container" id="location"><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getApproximateLocation($profile_account_id)) . makeEditable($allowEdit, "location")?></p>
                    <p class="w3-display-container" id="countrySpan"><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getCountry($profile_account_id))?></p>
                    <hr>
                    <p class="w3-display-container" id="email"><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getEmail($profile_account_id)) . makeEditable($allowEdit, "email")?></p>
                    <p class="w3-display-container" id="phone"><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getPhoneNumber($profile_account_id)) . makeEditable($allowEdit, "phone")?></p>
                    <hr>
                    <p class="w3-display-container" id="facebook"><i class="fa fa-facebook-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getFacebookLink($profile_account_id)) . makeEditable($allowEdit, "facebook")?></p>
                    <p class="w3-display-container" id="linkedin"><i class="fa fa-linkedin-square fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getLinkedinLink($profile_account_id)) . makeEditable($allowEdit, "linkedin")?></p>
                    <hr>

                    <button id="request" <?php if (hasAlreadySentRequest($profile_account_id)) { echo "disabled=''"; } ?> class="w3-button w3-block w3-dark-grey" onclick="sendMentorshipRequest()">+ Connect</button>
                    <br>
                </div>
            </div><br>

            <!-- End Left Column -->
        </div>

        <!-- Right Column -->
        <div class="w3-twothird">

            <?php if ($allowEdit) { echo "
            <div id=\"pending\" class=\"w3-container w3-display-container w3-card w3-white w3-margin-bottom\">
                <h2 class=\"w3-text-grey w3-padding-16\"><i class=\"fa fa-users fa-fw w3-margin-right w3-xxlarge w3-text-lime\"></i>Pending Mentorships</h2>
                <div id=\"pending_content\" class=\"w3-container w3-padding-32 w3-text-grey\">
                    <?php echo formatPendingMentorships($profile_account_id); ?>
                </div>
            </div>"; } ?>

            <div id="degrees" class="w3-container w3-display-container w3-card w3-white w3-margin-bottom">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>
                <?php echo formatDegrees(getDegrees($profile_account_id)) . makeHistoryElementEditable($allowEdit, "degrees"); ?>
            </div>

            <div id="jobs" class="w3-container w3-display-container w3-card w3-white w3-margin-bottom">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>
                <?php echo formatJobs(getJobs($profile_account_id)) . makeHistoryElementEditable($allowEdit, "jobs"); ?>
            </div>

            <div id="mentorships" class="w3-container w3-display-container w3-card w3-white w3-margin-bottom">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-users fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Mentorships</h2>
                <div class="w3-container w3-padding-32 w3-text-grey">
                    <?php echo formatMentorships($profile_account_id); ?>
                </div>
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
