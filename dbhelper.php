<?php
require_once "database.php";

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
