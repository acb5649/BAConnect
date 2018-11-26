<?php

require_once "dbhelper.php";
require_once "locationFunctions.php";
require_once "mentorshipFunctions.php";

class Report{
    public $title;
    public $msg;
    public $nextModal;
    public $success;
    public $inputs; //associative array of all the users' inputs, so you
                        //can reset them when the modal re-opens.

    function __construct($name, $message, $next, $worked){
        $this->title = $name;
        $this->msg = $message;
        if((strpos($next, 'Modal') === false) && $next != ""){
            $next = $next . "Modal";
        }
        $this->nextModal = $next;
        $this->success = $worked;
        $this->inputs = null;
    }
}

class Connection {
    public static function connect() {
        try {
            return new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }
}

function registerUser($user, $address, $educationHistory, $workHistory, $picture, $resume) {
    // First create an Account in the DB
    registerNewAccount($user);
    // Now get account_ID to link with other tables.
    $account_id = getAccountIDFromUsername($user->username);
    // Next, send basic user information. Many things are stored in different tables, so this might be a little verbose.
    applyUserInformation($account_id, $user);
    // make Address table entry, then get it's address_ID, then save that ID and the user ID to the Address History table.
    updateUserAddress($account_id, $address);
    // send phone number info to db
    registerNewPhoneNumber($account_id, $user->phoneNumber);
    // Third, send Education
    foreach($educationHistory as $educationElement) {
        registerNewDegree($account_id, $educationElement);
    }
    // and Work History...
    foreach($workHistory as $workElement) {
        registerNewWork($account_id, $workElement);
    }
    // Finally, assign photos and resumes
    registerNewPicture($account_id, $picture);
    registerNewResume($account_id, $resume);
    // If all that went well, set the account to disabled, and only enable it once the user clicks the link to activate their account.
    finalizeRegistration($account_id, $user->email);
}

function registerNewAccount($user) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into Account (account_ID, username, password, type, active) values (?, ?, ?, ?, ?)");
    $stmt->bindValue(1, null, PDO::PARAM_NULL);
    $stmt->bindValue(2, $user->username, PDO::PARAM_STR);
    $stmt->bindValue(3, $user->password, PDO::PARAM_STR);
    $stmt->bindValue(4, 1, PDO::PARAM_INT);
    $stmt->bindValue(5, 0, PDO::PARAM_INT);
    $stmt->execute();
    $con = null;
}



function applyUserInformation($account_id, $user) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into Information (account_ID, first_name, middle_name, last_name, gender, email_address, status) values (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $user->firstName, PDO::PARAM_STR);
    $stmt->bindValue(3, $user->middleName, PDO::PARAM_STR);
    $stmt->bindValue(4, $user->lastName, PDO::PARAM_STR);
    $stmt->bindValue(5, $user->gender, PDO::PARAM_INT);
    $stmt->bindValue(6, $user->email, PDO::PARAM_STR);
    $stmt->bindValue(7, $user->status, PDO::PARAM_INT);
    $stmt->execute();
    $con = null;
}



function registerNewPhoneNumber($account_id, $phoneNumber) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into `Phone Numbers` (account_ID, phone_type_ID, phone_number) values (?, ?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, 1, PDO::PARAM_INT);
    $stmt->bindValue(3, $phoneNumber, PDO::PARAM_INT);
    $stmt->execute();
    $con = null;
}

function compressImage($originalImage, $outputImage, $quality) {
    list($width, $height, $type, $attr) = getimagesize($originalImage);
    if ($type == IMAGETYPE_PNG) {
        $imageTmp=imagecreatefrompng($originalImage);
    } elseif ($type = IMAGETYPE_JPEG) {
        $imageTmp=imagecreatefromjpeg($originalImage);
    } else {
        return 0;
    }

    // quality is a value from 0 (worst) to 100 (best)
    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return 1;
}

function registerNewPicture($account_id, $picture) {

    $date = new Datetime('NOW');
    $dateStr = $date->format('Y-m-d H:i:s');
    //compressImage($picture, $picture, 15);
    $file = fopen($picture,'rb');

    $con = Connection::connect();
    $stmt = $con->prepare("delete from Pictures where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $con->prepare("insert into Pictures (picture_ID, account_ID, date_uploaded, picture) values (?, ?, ?, ?)");
    $stmt->bindValue(1, null, PDO::PARAM_NULL);
    $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(3, $dateStr, PDO::PARAM_STR);
    $stmt->bindValue(4, $file, PDO::PARAM_LOB);
    $stmt->execute();
    $con = null;
}

function finalizeRegistration($account_id, $email) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into Registration (account_ID, registration_code) values (?, ?)");
    $code = makeCode($email);
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $code, PDO::PARAM_STR);
    $stmt->execute();
    $con = null;

    //Email a verification code to the email provided.
    mail($email, "BAConnect: Verify Your Account", "Click this link to verify your account: http://corsair.cs.iupui.edu:22891/courseproject/verify.php?code=" . $code . "&email=" . urlencode($email) . "&type=reg");
}

function resetPassword($email) {
    $account_id = getAccountIDFromEmail($email);
    $code = makeCode($email);

    $con = Connection::connect();
    $stmt = $con->prepare("select * from `Password Recovery` where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($row) < 2) {
        $stmt = $con->prepare("insert into `Password Recovery` (account_ID, code) values (?, ?)");
        $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $code, PDO::PARAM_STR);
        $stmt->execute();
        $con = null;

        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $url = str_replace("forgot.php", "verify.php", $url);

        mail($email, "BAConnect: Reset Your Password", "Click this link to reset your password: http://" . $url . "?code=" . $code . "&email=" . urlencode($email) . "&type=reset");

        return new Report("Success!", "An email has been sent to the address registered with your account.", "", TRUE);
    } else {
        return new Report("Maximum Reset Attempts Limit Reached", "Contact an admin for further assistance.", "", FALSE);
    }


}

function changePassword($email, $code, $newPassword) {
    if (verifyCode($code, $email)) {
        $con = Connection::connect();
        if($con == null){
            $report = new Report("Database Error", "Could not secure connection.", "changePassModal", FALSE);
            return $report;
        }
        $stmt = $con->prepare("select account_ID from `Password Recovery` where code = ?");
        $stmt->bindValue(1, $code, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row == null){
            $con = null;
            $report = new Report("Verification Error", "Invalid verification code.", "changePassModal", FALSE);
            return $report;
        }
        $account_id = $row['account_ID'];

        $stmt = $con->prepare("update Account set password = ? where account_ID = ?");
        $stmt->bindValue(1, $newPassword, PDO::PARAM_STR);
        $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
        if($stmt->execute()){
            $report = new Report("Success!", "Your password was successfully changed", "loginModal", TRUE);
        }
        else{
            $report = new Report("Error", "Your password could not be changed", "changePassModal", FALSE);
        }

        // Change was successful, delete row from table.
        $stmt = $con->prepare("delete from `Password Recovery` where code = ? and account_ID = ?");
        $stmt->bindValue(1, $code, PDO::PARAM_STR);
        $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
        $stmt->execute();

        $con = null;
        return $report;
    }
    else{
        $report = new Report("Verification Error", "Invalid verification code", "changePassModal", FALSE);
        return $report;
    }
}

function editAccountType($account_id, $newType){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Account` SET type = ? WHERE account_ID = ?");
    $stmt->bindValue(1, $newType, PDO::PARAM_INT);
    $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
    $stmt->execute();

    $con = null;
    $stmt = null;
}

function login($username, $password) {
    $con = Connection::connect();
    if($con == null){
        $report = new Report("Error", "Could not connect to database.", "login", FALSE);
        return $report;
    }
    $account_id = getAccountIDFromUsername($username);

    if($account_id == null){
        $report = new Report("Error", "Invalid username or password", "login", FALSE);
        return $report;
    }

    $stmt = $con->prepare("select password from Account where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;

    if($password == $row['password']){
        $report = new Report("Success", "Login Successful", "login", TRUE);
    }
    else{
        $report = new Report("Error", "Invalid username or password", "login", FALSE);
    }

    return $report;
}

function makeCode($email) {
    return hash('md5', $email) . substr(str_shuffle(str_repeat($x='0123456789abcdef', ceil(18/strlen($x)) )), 1, 18);
}

function verifyCode($code, $email) {
    $hash = hash('md5', $email);
    $codeHash = substr($code, 0, 32);
    return ($hash == $codeHash);
}




/* NOTE: Work and Education functions section */


function registerNewResume($account_id, $resume) {
    if ($resume == "") {
        return;
    }

    $file = fopen($resume,'rb');

    $con = Connection::connect();
    $stmt = $con->prepare("insert into Resumes (account_ID, resume_file) values (?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $file, PDO::PARAM_LOB);
    $stmt->execute();
    $con = null;
}

function registerNewWork($account_id, $workHistory) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into `Job History` (`account_ID`, employer, profession_field, `start`, `end`) values (?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $workHistory->companyName, PDO::PARAM_STR);
    $stmt->bindValue(3, $workHistory->jobTitle, PDO::PARAM_STR);
    $stmt->bindValue(3, $workHistory->startYear, PDO::PARAM_INT);
    $stmt->bindValue(4, $workHistory->endYear, PDO::PARAM_INT);
    $stmt->execute();

    $stmt->execute();
    $con = null;
}

function registerNewDegree($account_id, $educationElement) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into Degrees (account_ID, degree_type_ID, school, major, graduation_year, enrollment_year) values (?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $educationElement->degreeType, PDO::PARAM_INT);
    $stmt->bindValue(3, $educationElement->schoolName, PDO::PARAM_STR);
    $stmt->bindValue(4, $educationElement->degreeMajor, PDO::PARAM_STR);
    $stmt->bindValue(5, $educationElement->gradYear, PDO::PARAM_INT);
    $stmt->bindValue(6, $educationElement->enrollmentYear, PDO::PARAM_INT);
    $stmt->execute();
    $con = null;
}

// This function will generate a list of all the degree Types in the database
function listDegreeTypes(){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT degree, degree_type_ID FROM `Degree Types` WHERE enabled = 1");
    $stmt->execute();
    $list = $stmt->fetchAll();
    $html = "";
    foreach ($list as $option) {
        $html = $html . '<option value="' . $option["degree_type_ID"] . '"> ' . $option["degree"] . ' </option> ';
    }
    $con = null;
    return $html;
}

// This function will add a new degree type to the database, but will check if it's already in the database first
function addDegreeType($degreeType){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT * FROM `Degree Types` WHERE degree = ?");
    $stmt->bindValue(1, $degreeType, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result != null){	//if there is already a degree type with the same name in the database
        if($result['enabled'] == "0"){
            $stmt = $con->prepare("UPDATE `Degree Types` SET enabled = 1 WHERE degree = ?");
            $stmt->bindValue(1, $degreeType, PDO::PARAM_STR);
            $stmt->execute();
            $report = new Report("Success!", "The degree type was successfully re-enabled", "addDegreeType", TRUE);
        }
        else{
            $report = new Report("Duplicate", "That degree type already exists", "addDegreeType", FALSE);
        }
        $con = null;
        $stmt = null;
        return $report;
    }
    $stmt = null;
    $result = null;

    $stmt = $con->prepare("INSERT INTO `Degree Types` (degree) values (?)");
    $stmt->bindValue(1, $degreeType, PDO::PARAM_STR);
    $success = $stmt->execute();

    if($success){
        $report = new Report("Success!", "The degree type was successfully added", "addDegreeType", TRUE);
    }
    else{
        $report = new Report("Error", "An error occured while adding that degree type", "addDegreeType", FALSE);
    }

    $con = null;
    return $report;
}

// This function will edit a pre-existing degree type name in the database
function editDegreeType($id, $newName){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Degree Types` SET degree = ? WHERE degree_type_ID = ?");
    $stmt->bindValue(1, $newName, PDO::PARAM_STR);
    $stmt->bindValue(2, $id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if($success){
        $report = new Report("Success!", "The degree type was successfully changed", "addDegreeType", TRUE);
    }
    else{
        $report = new Report("Error", "An error occured while changing that degree type", "addDegreeType", FALSE);
    }
    $con = null;
    return $report;
}

// This function will delete a degree type from the database
function deleteDegreeType($degreeTypeID){
    $con = Connection::connect();

    $stmt = $con->prepare("UPDATE `Degree Types` SET enabled = 0 WHERE degree_type_ID = ?");
    $stmt->bindValue(1, $degreeTypeID, PDO::PARAM_INT);
    $success = $stmt->execute();

    if($success){
        $report = new Report("Success!", "The degree type was successfully disabled", "addDegreeType", TRUE);
    }
    else{
        $report = new Report("Error", "An error occured while disabling that degree type", "addDegreeType", FALSE);
    }

    $con = null;
    $stmt = null;
    return $report;
}


/* NOTE: Account getter functions section */


function getAccountTypeFromAccountID($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select type from Account where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['type'];
}

function getAccountIDFromUsername($username) {
    $con = Connection::connect();
    $stmt = $con->prepare("select account_ID from Account where username = ?");
    $stmt->bindValue(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['account_ID'];
}

function getAccountIDFromEmail($email) {
    $con = Connection::connect();
    $stmt = $con->prepare("select account_ID from Information where email_address = ?");
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['account_ID'];
}

function getName($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select * from Information where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Missing Name";
    }
    $con = null;
    return $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
}

function getEmail($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select * from Information where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Missing Email";
    }
    $con = null;
    return $row['email_address'];
}

function getMentorshipStatus($account_id) {
    $result = "";
    $con = Connection::connect();
    $stmt = $con->prepare("select * from Mentorship where mentor_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $mentees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($mentees) > 0) {
        $result .= "Currently a mentor.";
    }
    $con = Connection::connect();
    $stmt = $con->prepare("select * from Mentorship where mentee_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($mentors) > 0) {
        $result .= "Currently being mentored.";
    }
    if ($result == "") {
        $result .= "Not in a mentorship.";
    }
    $con = null;
    return $result;
}


function getStatus($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select status from Information where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Missing Status";
    }
    $con = null;
    $status = (int) $row['status'];
    if ($status == 0) {
        return "Student";
    } elseif ($status == 1) {
        return "Working Professional";
    } else {
        return "Unknown Status";
    }
}

function getPhoneNumber($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select phone_number from `Phone Numbers` where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Missing Phone Number";
    }
    $con = null;
    return $row['phone_number'];
}

function getGender($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select gender from `Information` where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Missing Gender";
    }
    $con = null;
    $gender = (int) $row['gender'];
    if ($gender == 0) {
        return "Male";
    } elseif ($gender == 1) {
        return "Female";
    } else {
        return "Nonbinary";
    }
}

function getDegrees($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT * FROM Degrees where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $degrees = array();
    foreach ($result as $degree) {
        array_push($degrees, array($degree['school'], $degree['major'], $degree['graduation_year'], $degree['enrollment_year'], $degree['degree_ID']));
    }
    $con = null;
    return $degrees;
}

function getJobs($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT job_ID FROM `Job History` where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $jobs = array();
    foreach ($result as $job_id) {
        $stmt = $con->prepare("SELECT * FROM `Job History` where job_ID = ?");
        $stmt->bindValue(1, $job_id['job_ID'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        array_push($jobs, array($result['employer'], $result['profession_field'], $result['start'], $result['end'], $result['job_ID']));
    }
    $con = null;
    return $jobs;
}

function getFacebookLink($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select facebook from `Information` where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['facebook'] == ""){
        return "Add Facebook";
    }
    $con = null;
    return $row['facebook'];
}

function getLinkedinLink($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select linkedin from `Information` where account_ID = ?");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['linkedin'] == ""){
        return "Add LinkedIn";
    }
    $con = null;
    return $row['linkedin'];
}
