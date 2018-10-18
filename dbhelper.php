<?php
require_once "functions.php";

function registerUser($user, $address, $educationHistory, $workHistory, $photo, $resume) {
  try {
      $con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
      $isConnected = true;
  } catch (PDOException $e) {
      echo $e->getMessage();
  }

  $date = new Datetime('NOW');
  $dateStr = $date->format('Y-m-d H:i:s');

  // First create an Account in the DB
  $stmt = $con->prepare("insert into Account (account_ID, username, password, type, active) values (?, ?, ?, ?, ?)");
  $stmt->bindValue(1, null, PDO::PARAM_NULL);
  $stmt->bindValue(2, $user->username, PDO::PARAM_STR);
  $stmt->bindValue(3, $user->password, PDO::PARAM_STR);
  $stmt->bindValue(4, 0, PDO::PARAM_INT);
  $stmt->bindValue(5, 0, PDO::PARAM_INT);
  $stmt->execute();

  // Now get account_ID to link with other tables.
  $stmt = $con->prepare("select account_ID from Account where username = '" . $user->username . "'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $account_id = $row['account_ID'];

  // Next, send basic user information. Many things are stored in different tables, so this might be a little verbose.
  $stmt = $con->prepare("insert into Information (account_ID, first_name, middle_name, last_name, gender, email_address) values (?, ?, ?, ?, ?, ?)");
  $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $user->firstName, PDO::PARAM_STR);
  $stmt->bindValue(3, $user->middleName, PDO::PARAM_STR);
  $stmt->bindValue(4, $user->lastName, PDO::PARAM_STR);
  $stmt->bindValue(5, $user->gender, PDO::PARAM_INT);
  $stmt->bindValue(6, $user->email, PDO::PARAM_STR);
  $stmt->execute();

  // make Address table entry, then get it's address_ID, then save that ID and the user ID to the Address History table.
  $stmt = $con->prepare("insert into Addresses (address_ID, country_ID, state, city, post_code, street_address, street_address2) values (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bindValue(1, null, PDO::PARAM_NULL);
  $stmt->bindValue(2, $address->country, PDO::PARAM_INT);
  $stmt->bindValue(3, $address->state, PDO::PARAM_STR);
  $stmt->bindValue(4, $address->city, PDO::PARAM_STR);
  $stmt->bindValue(5, $address->postcode, PDO::PARAM_INT);
  $stmt->bindValue(6, $address->street, PDO::PARAM_STR);
  $stmt->bindValue(7, "", PDO::PARAM_STR);
  $stmt->execute();

  $stmt = $con->prepare("select account_ID from Addresses where street_address = '" . $address->street . "' and post_code = '" . $address->postcode . "' and city = '" . $address->city . "'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $address_id = $row['address_ID'];

  $stmt = $con->prepare("insert into `Address History` (address_ID, account_ID, start, end) values (?, ?, ?, ?)");
  $stmt->bindValue(1, $address_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
  $stmt->bindValue(3, $dateStr, PDO::PARAM_STR);
  $stmt->bindValue(4, null, PDO::PARAM_NULL);
  $stmt->execute();

  // send phone number info to db
  $stmt = $con->prepare("insert into `Phone Numbers` (account_ID, phone_type_ID, phone_number) values (?, ?, ?)");
  $stmt->bindValue(1, $account_ID, PDO::PARAM_INT);
  $stmt->bindValue(2, 1, PDO::PARAM_INT);
  $stmt->bindValue(3, $user->phoneNumber, PDO::PARAM_INT);
  $stmt->execute();

  // Third, send Education
  foreach($educationHistory as $educationElement) {

    $stmt = $con->prepare("insert into Degrees (account_ID, degree_type_ID, school, major, graduation_year) values (?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $educationElement->degreeType, PDO::PARAM_INT);
    $stmt->bindValue(3, $educationElement->schoolName, PDO::PARAM_STR);
    $stmt->bindValue(4, $educationElement->degreeMajor, PDO::PARAM_STR);
    $stmt->bindValue(5, $educationElement->gradYear, PDO::PARAM_INT);
    $stmt->execute();
  }

  // and Work History...
  $stmt = $con->prepare("insert into Job (job_ID, employer, profession_field) values (?, ?, ?)");
  $stmt->bindValue(1, null, PDO::PARAM_NULL);
  $stmt->bindValue(2, $workHistory->companyName, PDO::PARAM_STR);
  $stmt->bindValue(3, $workHistory->jobTitle, PDO::PARAM_STR);
  $stmt->execute();

  $stmt = $con->prepare("select job_ID from Job where employer = '" . $workHistory->companyName . "' and profession_field = '" . $workHistory->jobTitle . "'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $job_id = $row['job_id'];

  $stmt = $con->prepare("insert into `Job History` (job_ID, account_ID, start, end) values (?, ?, ?, ?)");
  $stmt->bindValue(1, $job_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
  $stmt->bindValue(3, "2000", PDO::PARAM_STR);
  $stmt->bindValue(4, "2018", PDO::PARAM_STR);
  $stmt->execute();

  // Finally, assign photos and resumes
  $stmt = $con->prepare("insert into Pictures (picture_ID, account_ID, date_uploaded, picture) values (?, ?, ?, ?)");
  $stmt->bindValue(1, null, PDO::PARAM_NULL);
  $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
  $stmt->bindValue(3, $dateStr, PDO::PARAM_STR);
  $stmt->bindValue(4, $photo, PDO::PARAM_STR);
  $stmt->execute();

  $stmt = $con->prepare("insert into Resumes (account_ID, resume_file) values (?, ?)");
  $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $resume, PDO::PARAM_STR);
  $stmt->execute();

  // If all that went well, set the account to disabled, and only enable it once the user clicks the link to activate their account.
  $stmt = $con->prepare("insert into Registration (account_ID, registration_code) values (?, ?)");
  $code = makeCode($user->email);
  $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $code, PDO::PARAM_STR);
  $stmt->execute();

  //Email a verification code to the email provided.
  mail($user->email, "BAConnect: Verify Your Account", "Click this link to verify your account: http://corsair.cs.iupui.edu:22891/courseproject/verify.php?code=" . $code . "&email=" . $user->email);

  $con = null;
}

class Account {
  public $username;
  public $password;

  function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }
}

class User extends Account {
  public $firstName;
  public $middleName;
  public $lastName;
  public $email;
  public $gender;
  public $phoneNumber;
  public $status;

  function __construct($username, $password, $firstName, $middleName, $lastName, $email, $gender, $phoneNumber, $status) {
    parent::__construct($username, $password);

    $this->firstName = $firstName;
    $this->middleName = $middleName;
    $this->lastName = $lastName;
    $this->email = $email;
    $this->gender = $gender;
    $this->phoneNumber = $phoneNumber;
    $this->status = $status;
  }

}

class Address {
  public $street;
  public $city;
  public $postcode;
  public $state;
  public $country;

  function __construct($street, $city, $postcode, $state, $country) {
    $this->street = $street;
    $this->city = $city;
    $this->postcode = $postcode;
    $this->state = $state;
    $this->country = $country;
  }

}

class EducationHistoryEntry {
  public $schoolName;
  public $degreeType;
  public $degreeMajor;
  public $gradYear;

  function __construct($schoolName, $degreeType, $degreeMajor, $gradYear) {
    $this->schoolName = $schoolName;
    $this->degreeType = $degreeType;
    $this->degreeMajor = $degreeMajor;
    $this->gradYear = $gradYear;
  }

}

class WorkHistoryEntry {
  public $companyName;
  public $jobTitle;

  function __construct($companyName, $jobTitle) {
    $this->companyName = $companyName;
    $this->jobTitle = $jobTitle;
  }
}

?>
