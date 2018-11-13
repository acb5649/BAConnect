<?php
//require_once "database.php";

/*
    This should be for all functions that are about a physical location, so addresses, countries, states, etc.
    This includes adding, editing, or "deleting" a state or country, calculating distance, retrieving addresses,
    states, or countries, etc.
*/


/* NOTE: User-specific functions section */


function getAddressIDFromAccount($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT address_ID FROM `Address History` where account_ID = '" . $account_id . "' and isnull(end) ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['address_ID'];
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

function getApproximateLocation($account_id) {
    $con = Connection::connect();
    $stmt = $con->prepare("select address_ID from `Address History` where account_ID = '" . $account_id . "'  and isnull(end)");
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

    $con = null;

    return  $city . ", " . $row['state_name'];
}

function getAddressLine1($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT street_address FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['street_address'];
}

function getAddressLine2($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT street_address2 FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['street_address2'];
}

function getCity($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT city FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['city'];
}

function getPostCode($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT post_code FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['post_code'];
}

function getCountry($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT country_ID FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $country_ID = $result['country_ID'];

    $stmt = $con->prepare("SELECT country FROM `Countries` where country_ID = '" . $country_ID . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['country'];
}


/* NOTE: General Address functions section */


function registerNewAddress($address) {
    $con = Connection::connect();
    $stmt = $con->prepare("insert into Addresses (country_ID, state, city, post_code, street_address, street_address2) values (?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $address->country, PDO::PARAM_INT);
    $stmt->bindValue(2, $address->state, PDO::PARAM_STR);
    $stmt->bindValue(3, $address->city, PDO::PARAM_STR);
    $stmt->bindValue(4, $address->postcode, PDO::PARAM_STR);
    $stmt->bindValue(5, $address->street, PDO::PARAM_STR);
    $stmt->bindValue(6, $address->street2, PDO::PARAM_STR);
    $stmt->execute();
    $con = null;
}

function getAddressID($address) {
    $con = Connection::connect();
    $stmt = $con->prepare("select address_ID from Addresses where street_address = '" . $address->street . "' and street_address2 = '" . $address->street2 . "' and post_code = '" . $address->postcode . "' and city = '" . $address->city . "' and country_id = '" . $address->country . "' and state = '" . $address->state . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $con = null;
    return $row['address_ID'];
}




/* NOTE: Country functions section */

/* NOTE: the function getCountry($account_id) is located in the "User-specific functions" section */


function getCountryID($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT country_ID FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['country_ID'];
}

function listCountries($account_id = 0) {
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT country, country_ID FROM `Countries` WHERE enabled = 1");
    $stmt->execute();
    $list = $stmt->fetchAll();
    $con = null;
    $html = "";

    if ($account_id != 0) {
        $selected = getCountryID($account_id);
        foreach ($list as $option) {
            if ($option["country_ID"] == $selected) {
                $html = $html . '<option selected value="' . $option["country_ID"] . '"> ' . $option["country"] . ' </option> ';
            } else {
                $html = $html . '<option value="' . $option["country_ID"] . '"> ' . $option["country"] . ' </option> ';
            }
        }
    } else {
        foreach ($list as $option) {
            $html = $html . '<option value="' . $option["country_ID"] . '"> ' . $option["country"] . ' </option> ';
        }
    }

    return $html;
}

// This function will add a new country to the database, but will check if it's already in the database first
function addCountry($countryName){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT * FROM `Countries` WHERE country = '" . $countryName . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result != null){	//if there is already a country with the same name in the database
        $enabled = $result['enabled'];
        if($enabled == "0"){
            $stmt = $con->prepare("UPDATE `Countries` SET enabled = 1 WHERE country = '" . $countryName . "'");
            $stmt->execute();
        }
        $con = null;
        return true;
    }
    $stmt = null;
    $result = null;

    $stmt = $con->prepare("INSERT INTO `Countries` (country) values (?)");
    $stmt->bindValue(1, $countryName, PDO::PARAM_STR);
    $stmt->execute();

    $stmt = null;
    $con = null;
    return true;
}
// This function will edit a pre-existing country name in the database
function editCountry($id, $newName){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Countries` SET country = '" . $newName . "' WHERE country_ID = '" . $id . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}
// This function will delete a country from the database
function deleteCountry($country_ID){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `Countries` SET enabled = 0 WHERE country_ID = '" . $country_ID . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}


/* NOTE: State functions section */


function getStateID($account_id) {
    $address_id = getAddressIDFromAccount($account_id);

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT state FROM `Addresses` where address_ID = '" . $address_id . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $con = null;
    $stmt = null;

    return $result['state'];
}

function getStatesList($countryID, $account_id = -1){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT state_name, state_ID FROM States WHERE country_ID = '" . $countryID . "' AND enabled = 1");
    $stmt->execute();
    $list = $stmt->fetchAll();
    $con = null;

    if ($account_id == -1) {
        $selected = -1;
    } else {
        $selected = getStateID($account_id);
    }


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

//this function will add a new state to the database, and associate it with the given country
function addState($countryID, $stateName){
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT * FROM States WHERE state_name = '" . $stateName . "' AND country_ID = '" . $countryID . "'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result != null){	//if there is already a country with the same name in the database
        if($result['enabled'] == "0"){
            $stmt = $con->prepare("UPDATE States SET enabled = 1 WHERE state_ID = '" . $ID . "'");
            $stmt->execute();
        }
        $con = null;
        return true;
    }
    $stmt = null;
    $result = null;

    $stmt = $con->prepare("INSERT INTO States (country_ID, state_name, state_ID) values (?, ?, DEFAULT)");
    $stmt->bindValue(1, $countryID, PDO::PARAM_INT);
    $stmt->bindValue(2, $stateName, PDO::PARAM_STR);
    $stmt->execute();

    $stmt = null;
    $con = null;
    return true;
}
// This function will edit a pre-existing state name in the database
function editState($newName, $ID){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE States SET state_name = '" . $newName . "' WHERE state_ID = '" . $ID . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}
// This function will delete a state from the database
function deleteState($ID){
    $con = Connection::connect();
    $stmt = $con->prepare("UPDATE `States` SET enabled = 0 WHERE state_ID = '" . $ID . "'");
    $stmt->execute();

    $con = null;
    $stmt = null;
    return true;
}

?>
