<?php

require_once "dbhelper.php";
include_once "session.php";

if (isset($_SESSION["account_ID"])) {
    $account_id = $_SESSION["account_ID"];
} else {
    header("location: index.php");
}

if (isset($_POST['submit'])) {
    $con = Connection::connect();

    if (isset($_POST['gender'])) {
        $stmt = $con->prepare("UPDATE Information set gender = ? where account_ID = '" . $account_id . "'");
        $stmt->bindValue(1, $_POST['gender'], PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST['status'])) {
        $stmt = $con->prepare("UPDATE Information set status = ? where account_ID = '" . $account_id . "'");
        $stmt->bindValue(1, $_POST['status'], PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST['email'])) {
        $stmt = $con->prepare("UPDATE Information set email_address = ? where account_ID = '" . $account_id . "'");
        $stmt->bindValue(1, $_POST['email'], PDO::PARAM_STR);
        $stmt->execute();
    }

    if (isset($_POST['phone'])) {
        $stmt = $con->prepare("UPDATE `Phone Numbers` set phone_number = ? where account_ID = '" . $account_id . "'");
        $stmt->bindValue(1, $_POST['phone'], PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST['addr1']) && isset($_POST['addr2']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['postcode']) && isset($_POST['country'])) {
        $address = new Address($_POST['addr1'], $_POST['addr2'], $_POST['city'], $_POST['postcode'], $_POST['state'], $_POST['country']);

        $old_address_id = getAddressIDFromAccount($account_id);
        $stmt = $con->prepare("UPDATE `Address History` set end = CURRENT_TIMESTAMP where address_id = ?");
        $stmt->bindValue(1, $old_address_id, PDO::PARAM_INT);
        $stmt->execute();

        updateUserAddress($account_id, $address);
    }

    $con = null;
    header("location: profile.php");
}