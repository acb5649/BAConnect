<?php

class Connection {
    public static function connect() {
        try {
            return new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
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
    return $row['account_ID'];
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

function registerNewAddress($address) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into Addresses (country_ID, state, city, post_code, street_address, street_address2) values (?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $address->country, PDO::PARAM_INT);
    $stmt->bindValue(2, $address->state, PDO::PARAM_STR);
    $stmt->bindValue(3, $address->city, PDO::PARAM_STR);
    $stmt->bindValue(4, $address->postcode, PDO::PARAM_STR);
    $stmt->bindValue(5, $address->street, PDO::PARAM_STR);
    $stmt->bindValue(6, $address->street, PDO::PARAM_STR);
    $stmt->execute();
    $con = null;
}

function getAddressID($address) {
    $con = Connection::connect();
    $stmt = $con->prepare("select address_ID from Addresses where street_address = '" . $address->street . "' and post_code = '" . $address->postcode . "' and city = '" . $address->city . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['address_ID'];
}

function updateUserAddress($account_id, $address) {
    registerNewAddress($address);

    $address_id = getAddressID($address);

    $con = Connection::connect();
    $stmt = $con->prepare("insert into `Address History` (`address_ID`, `account_ID`, `start`) values (?, ?, CURRENT_TIMESTAMP)");
    $stmt->bindValue(1, $address_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
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
            return False;
        }

        $stmt = $con->prepare("UPDATE Account set password = '" . $newPassword . "' where account_ID = '" . $account_id . "'");
        $stmt->execute();

        return True;
    }
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

function getAccountTypeFromAccountID($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select type from Account where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['type'];
}

function listCountries() {
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT country, country_ID FROM Countries");
    $stmt->execute();
    $list = $stmt->fetchAll();
    $con = null;
    $html = "";
    foreach ($list as $option) {
        $html = $html . '<option value="' . $option["country_ID"] . '"> ' . $option["country"] . ' </option> ';
    }
    return $html;
}

// This function will generate a list of all the degree Types in the database
function listDegreeTypes(){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT degree, degree_type_ID FROM `Degree Types`");
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
    $stmt = $con->prepare("SELECT * FROM `Degree Types` WHERE degree = " . $degreeType);
    $result = $stmt->execute();

    if(!isset($result)){	//if there is already a degree type with the same name in the database
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
function editDegreeType($oldName, $newName){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Degree Types` SET degree = '" . $newName . "' WHERE degree = '" . $oldName . "'");
    $stmt->execute();
    $con = null;
    return true;
}

// This function will delete a degree type from the database
function deleteDegreeType($degreeTypeName){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT degree_type_ID FROM `Degree Types` WHERE degree = '" . $degreeTypeName . "'");
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($row == null){
        return true;
    }
    $id = $row['degree_type_ID'];

    $stmt = null;

    $stmt = $con->prepare("UPDATE Degrees SET degree_type_ID = -1 WHERE degree_type_ID = '" . $id . "'" );
    $stmt->execute();

    $row = null;
    $id = null;

    $stmt = $con->prepare("DELETE FROM `Degree Types` WHERE degree = '" . $degreeTypeName . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}
// This function will add a new country to the database, but will check if it's already in the database first
function addCountry($countryName){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT * FROM Countries WHERE country = " . $countryName);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($result != null){	//if there is already a country with the same name in the database
        return true;
    }
    $stmt = null;
    $result = null;

    $stmt = $con->prepare("INSERT INTO Countries (country) values (?)");
    $stmt->bindValue(1, $countryName, PDO::PARAM_STR);
    $stmt->execute();

    $stmt = null;
    $con = null;
    return true;
}
// This function will edit a pre-existing country name in the database
function editCountry($oldName, $newName){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE Countries SET country = '" . $newName . "' WHERE country = '" . $oldName . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}
// This function will delete a country from the database
function deleteCountry($countryName){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT country_ID FROM Countries WHERE country = '" . $countryName . "'");
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($row == null){
        return true;
    }
    $id = $row['country_ID'];

    $stmt = null;

    $stmt = $con->prepare("UPDATE Addresses SET country_ID = -1 WHERE country_ID = '" . $id . "'" );
    $stmt->execute();

    $row = null;
    $id = null;

    $stmt = $con->prepare("DELETE FROM Countries WHERE country = '" . $countryName . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}

function makeCode($email) {
    return hash('md5', $email) . substr(str_shuffle(str_repeat($x='0123456789abcdef', ceil(18/strlen($x)) )), 1, 18);
}

function verifyCode($code, $email) {
    $hash = hash('md5', $email);
    $codeHash = substr($code, 0, 32);
    return ($hash == $codeHash);
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

function getApproximateLocation($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select address_ID from `Address History` where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Somewhere over the rainbow";
    }

    $address_id = $row['address_ID'];
    $stmt = $con->prepare("select * from `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == null){
        return "Somewhere over the rainbow";
    }
    $city = $row['city'];

    $stmt = $con->prepare("select state_name from `States` where state_id = ?");
    $stmt->bindValue(1, $row['state'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return  $city . ", " . $row['state_name'];
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
        array_push($degrees, array($degree['school'], $degree['major'], $degree['graduation_year'], $degree['enrollment_year']));
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
        array_push($jobs, array($result['employer'], $result['profession_field'], $result['start'], $result['end']));
    }
    $con = null;
    return $jobs;
}

function getStatesList($countryID, $account_id){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT state_name, state_ID FROM States WHERE country_ID = '" . $countryID . "'");
    $stmt->execute();
    $list = $stmt->fetchAll();
    $con = null;

    $selected = getStateID($account_id);

    $html = "";
    foreach ($list as $option) {
        if ($option["state_ID"] == $selected) {
            $html = $html . '<option selected value="' . $option["state_ID"] . '"> ' . $option["state_name"] . ' </option> ';
        } else {
            $html = $html . '<option value="' . $option["state_ID"] . '"> ' . $option["state_name"] . ' </option> ';
        }
    }
    return $html;
}

function getAddressIDFromAccount($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT address_ID FROM `Address History` where account_ID = '" . $account_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['address_ID'];
}

function getCountryID($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT country_ID FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['country_ID'];
}

function getStateID($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT state FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['state'];
}

function setStateID($account_id, $newState) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Addresses` set state = ? where address_ID = '" . $address_id . "'");
    $stmt->bindValue(1, $newState, PDO::PARAM_INT);
    $stmt->execute();
    $con = null;
}


function getAddressLine1($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT street_address FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['street_address'];
}

function setAddressLine1($account_id, $newStreet1) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Addresses` set street_address = '" . $newStreet1 . "' where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $con = null;
}


function getAddressLine2($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT street_address2 FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['street_address2'];
}

function setAddressLine2($account_id, $newStreet2) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Addresses` set street_address2 = '" . $newStreet2 . "' where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $con = null;
}


function getCity($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT city FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['city'];
}

function setCity($account_id, $city) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Addresses` set city = '" . $city . "' where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $con = null;
}

function getPostCode($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT post_code FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['post_code'];
}

function setPostCode($account_id, $postCode) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Addresses` set post_code = '" . $postCode . "' where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $con = null;
}