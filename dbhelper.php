<?php

try {
    $con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
    $isConnected = true;
} catch (PDOException $e) {
    echo $e->getMessage();
}

function registerUser($user, $address, $educationHistory, $workHistory) {
  // First create an Account in the DB, and get the Account_ID.

  // Next, send basic user information. Many things are stored in different tables, so this might be a little verbose.

  // Third, send Education and Work History

  // If all that went well, set the account to disabled, and only enable it once the user clicks the link to activate their account.

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
  public $zipcode;
  public $state;
  public $country;

  function __construct($street, $city, $zipcode, $state, $country) {
    $this->street = $street;
    $this->city = $city;
    $this->zipcode = $zipcode;
    $this->state = $state;
    $this->country = $country;
  }
}

class History {
    public $startYear;
    public $endYear;

    function __construct($startYear, $endYear) {
      $this->startYear = $startYear;
      $this->endYear = $endYear;
    }
}

class EducationHistoryEntry extends History {
  public $schoolName;
  public $degreeType;
  public $degreeMajor;

  function __construct($schoolName, $degreeType, $degreeMajor, $startYear, $endYear) {
    parent::__construct($startYear, $endYear);

    $this->schoolName = $schoolName;
    $this->degreeType = $degreeType;
    $this->degreeMajor = $degreeMajor;
  }

}

class WorkHistoryEntry extends History {
  public $companyName;
  public $jobTitle;

  function __construct($companyName, $jobTitle, $startYear, $endYear) {
    parent::__construct($startYear, $endYear);

    $this->companyName = $companyName;
    $this->jobTitle = $jobTitle;
  }
}

?>
