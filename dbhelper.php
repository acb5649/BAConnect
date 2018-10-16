<?php
require_once "functions.php";

try {
    $con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
    $isConnected = true;
} catch (PDOException $e) {
    echo $e->getMessage();
}

function registerUser($user, $address, $educationHistory, $workHistory, $photo, $resume) {
  // First create an Account in the DB
  $stmt = $con->prepare("insert into Account (account_ID, username, password, type, active) values (?, ?, ?, ?, ?)");
  $stmt->bind_param("issii", null, $user->username, $user->email, 0, 0);
  $stmt->execute();

  // Now get account_ID to link with other tables.
  $stmt = $con->prepare("select account_ID from Account where username = " . $user->username);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $account_id = $row['account_ID'];

  // Next, send basic user information. Many things are stored in different tables, so this might be a little verbose.
  $stmt = $con->prepare("insert into Information (account_ID, first_name, middle_name, last_name, gender, email) values (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssis", $account_id, $user->firstName, $user->middleName, $user->lastName, $user->gender, $user->email);
  $stmt->execute();

  // make Address table entry, then get it's address_ID, then save that ID and the user ID to the Address History table.
  $stmt = $con->prepare("insert into Addresses (address_ID, country_ID, state/province, city, post_code, street_address) values (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iissis", null, $address->{getCountryCode()}, $address->state, $address->city, $address->postcode, $address->street);
  $stmt->execute();

  $stmt = $con->prepare("select account_ID from Addresses where street_address = " . $address->street . " and post_code = " . $address->postcode . " and city = " . $address->city);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $address_id = $row['address_ID'];

  $stmt = $con->prepare("insert into `Address History` (address_ID, account_ID, start, end) values (?, ?, ?, ?)");
  $stmt->bind_param("iiss", $address_id, $account_id, now(), null);
  $stmt->execute();

  // send phone number info to db
  $stmt = $con->prepare("insert into `Phone Numbers` (address_ID, phone_type_ID, phone_number) values (?, ?, ?)");
  $stmt->bind_param("iii", $account_id, 0, $user->phoneNumber);
  $stmt->execute();

  // Third, send Education and Work History
  foreach($educationHistory as $educationElement) {
    $stmt = $con->prepare("insert into Degrees (account_ID, degree_type_ID, school, major, graduation_year) values (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $account_id, $educationElement->{getDegreeType()}, $educationElement->schoolName, $educationElement->degreeMajor, $educationElement->gradYear);
    $stmt->execute();
  }

  foreach($workHistory as $workElement) {
    $stmt = $con->prepare("insert into Job (job_ID, employer, state/profession_field) values (?, ?, ?)");
    $stmt->bind_param("iss", null, $workElement->companyName, $workElement->jobTitle);
    $stmt->execute();

    $stmt = $con->prepare("select job_ID from Job where employer = " . $workElement->companyName . " and profession_field = " . $workElement->jobTitle);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $job_id = $row['job_id'];

    $stmt = $con->prepare("insert into `Job History` (job_ID, account_ID, start, end) values (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $job_id, $account_id, $workElement->startYear, $workElement->endYear);
    $stmt->execute();
  }

  // Finally, assign photos and resumes
  $stmt = $con->prepare("insert into Pictures (picture_ID, account_ID, date_uploaded, picture) values (?, ?, ?, ?)");
  $stmt->bind_param("iiss", null, $account_id, now(), $picture);
  $stmt->execute();

  $stmt = $con->prepare("insert into Resumes (account_ID, resume_file) values (?, ?)");
  $stmt->bind_param("is", $account_id, $resume);
  $stmt->execute();

  // If all that went well, set the account to disabled, and only enable it once the user clicks the link to activate their account.
  $stmt = $con->prepare("insert into Registration (account_ID, registration_code) values (?, ?)");
  $code = getCode($user->email);
  $stmt->bind_param("is", $account_id, $code);
  $stmt->execute();

  //Email a verification code to the email provided.
  mail($user->email, "BAConnect: Verify Your Account", "Click this link to verify your account: http://corsair.cs.iupui.edu:22891/courseproject/verify.php?code=" . $code);

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

  function getCountryCode() {

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

  function getDegreeType() {

  }

}

class WorkHistoryEntry {
  public $companyName;
  public $jobTitle;
  public $startYear;
  public $endYear;

  function __construct($companyName, $jobTitle, $startYear, $endYear) {
    $this->companyName = $companyName;
    $this->jobTitle = $jobTitle;
    $this->startYear = $startYear;
    $this->endYear = $endYear;
  }
}

?>
