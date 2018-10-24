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

function forgot_password($code, $password) {
    try {
        $con = new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer");
        $isConnected = true;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $stmt = $con->prepare("select account_ID from `Password Recovery` where code = '".$code."'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $account_id = $row['account_ID'];

    if (!$account_id) {
        return False;
    }

    $stmt = $con->prepare("update Account set password=? where account_ID=?");
    $stmt->bindValue(1, $password, PDO::PARAM_STR);
    $stmt->bindValue(2, $account_id, PDO::PARAM_INT);
    $stmt->execute();

    return True;
}

?>
