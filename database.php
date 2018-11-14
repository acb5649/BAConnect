<?php

require_once "dbhelper.php";
require_once "locationFunctions.php";
require_once "mentorshipFunctions.php";

class Report{
    public var $title;
    public var $msg;
    public var $nextModal;
    public var $success;
    public var $inputs; //associative array of all the users' inputs, so you
                        //can reset them when the modal re-opens.

    function __construct($name, $message, $next, $worked){
        $this->title = $name;
        $this->msg = $message;
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

function searchEntireDBFor($str) {

    $con = Connection::connect();
    $stmt = $con->prepare("select account_ID from Information where first_name like concat('%', ?, '%') limit 10");
    $stmt->bindValue(1, $str, PDO::PARAM_STR);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr1 = array_values($result);

    print_r($arr1);

    $stmt = $con->prepare("select account_ID from Information where last_name like concat('%', ?, '%') limit 10");
    $stmt->bindValue(1, $str, PDO::PARAM_STR);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr2 = array_values($result);

    print_r($arr2);

    $stmt = $con->prepare("select account_ID from `Job History` where employer like concat('%', ?, '%') limit 10");
    $stmt->bindValue(1, $str, PDO::PARAM_STR);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr3 = array_values($result);

    print_r($arr3);

    $stmt = $con->prepare("select account_ID from `Job History` where profession_field like concat('%', ?, '%') limit 10");
    $stmt->bindValue(1, $str, PDO::PARAM_STR);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr4 = array_values($result);

    print_r($arr4);

    $stmt = $con->prepare("select account_ID from `Degrees` where school like concat('%', ?, '%') limit 10");
    $stmt->bindValue(1, $str, PDO::PARAM_STR);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr5 = array_values($result);

    print_r($arr5);

    $stmt = $con->prepare("select account_ID from `Degrees` where major like concat('%', ?, '%') limit 10");
    $stmt->bindValue(1, $str, PDO::PARAM_STR);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr6 = array_values($result);

    print_r($arr6);

    $con = null;

    return $arr1;
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
    $stmt = $con->prepare("delete from Pictures where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("insert into `Password Recovery` (account_ID, code) values (?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $code, PDO::PARAM_STR);
    $stmt->execute();
    $con = null;

    mail($email, "BAConnect: Reset Your Password", "Click this link to reset your password: http://corsair.cs.iupui.edu:22891/courseproject/verify.php?code=" . $code . "&email=" . urlencode($email) . "&type=reset");

    return TRUE;
}

function changePassword($email, $code, $newPassword) {
    if (verifyCode($code, $email)) {
        $con = Connection::connect();
        $stmt = $con->prepare("select account_ID from `Password Recovery` where code = '".$code."'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $account_id = $row['account_ID'];

        if (!$account_id) {
            $con = null;
            return False;
        }

        $stmt = $con->prepare("UPDATE Account set password = '" . $newPassword . "' where account_ID = '" . $account_id . "'");
        $stmt->execute();

        $con = null;
        return True;
    }
}

function editAccountType($account_id, $newType){
  $con = Connection::connect();
  $stmt = $con->prepare("UPDATE `Account` SET type = '" . $newType . "' WHERE account_ID = '" . $account_id . "'");
  $stmt->execute();

  $con = null;
  $stmt = null;

}

function login($username, $password) {
    $con = Connection::connect();
    $account_id = getAccountIDFromUsername($username);

    $stmt = $con->prepare("select password from Account where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $password == $row['password'];
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
    $stmt = $con->prepare("SELECT * FROM `Degree Types` WHERE degree = '" . $degreeType . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result != null){	//if there is already a degree type with the same name in the database
        if($result['enabled'] == "0"){
            $stmt = $con->prepare("UPDATE `Degree Types` SET enabled = 1 WHERE degree = '" . $degreeType . "'");
            $stmt->execute();
        }
        $con = null;
        $stmt = null;
        return true;
    }
    $stmt = null;
    $result = null;

    $stmt = $con->prepare("INSERT INTO `Degree Types` (degree) values (?)");
    $stmt->bindValue(1, $degreeType, PDO::PARAM_STR);
    $stmt->execute();

    $con = null;
    return true;
}

// This function will edit a pre-existing degree type name in the database
function editDegreeType($id, $newName){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Degree Types` SET degree = '" . $newName . "' WHERE degree_type_ID = '" . $id . "'");
    $stmt->execute();
    $con = null;
    return true;
}

// This function will delete a degree type from the database
function deleteDegreeType($degreeTypeID){
    $con = Connection::connect();

    $stmt = $con->prepare("UPDATE `Degree Types` SET enabled = 0 WHERE degree_type_ID = '" . $degreeTypeID . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}


/* NOTE: Account getter functions section */


function getAccountTypeFromAccountID($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select type from Account where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['type'];
}

function getAccountIDFromUsername($username) {
    $con = Connection::connect();
    $stmt = $con->prepare("select account_ID from Account where username = '" . $username . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['account_ID'];
}

function getAccountIDFromEmail($email) {
    $con = Connection::connect();
    $stmt = $con->prepare("select account_ID from Information where email_address = '" . $email . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['account_ID'];
}

function getName($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select * from Information where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("select * from Information where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Missing Email";
    }
    $con = null;
    return $row['email_address'];
}



function getStatus($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select status from Information where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("select phone_number from `Phone Numbers` where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("select gender from `Information` where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("SELECT * FROM Degrees where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("SELECT job_ID FROM `Job History` where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $jobs = array();
    foreach ($result as $job_id) {
        $stmt = $con->prepare("SELECT * FROM `Job History` where job_ID = '" . $job_id['job_ID'] . "'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        array_push($jobs, array($result['employer'], $result['profession_field'], $result['start'], $result['end'], $result['job_ID']));
    }
    $con = null;
    return $jobs;
}

function getFacebookLink($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select facebook from `Information` where account_ID = '" . $account_id . "'");
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
    $stmt = $con->prepare("select linkedin from `Information` where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['linkedin'] == ""){
        return "Add LinkedIn";
    }
    $con = null;
    return $row['linkedin'];
}



?>
