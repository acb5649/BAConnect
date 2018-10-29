<?php

include_once "database.php";
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

    if (isset($_POST['addr1'])) {
        setAddressLine1($account_id, $_POST['addr1']);
    }

    if (isset($_POST['addr2'])) {
        setAddressLine2($account_id, $_POST['addr2']);
    }

    if (isset($_POST['city'])) {
        setCity($account_id, $_POST['city']);
    }

    if (isset($_POST['state'])) {
        setStateID($account_id, $_POST['state']);
    }

    if (isset($_POST['postcode'])) {
        setPostCode($account_id, $_POST['postcode']);
    }

    if (isset($_POST['country'])) {
        setCountry($account_id, $_POST['country']);
    }

    $con = null;
    header("location: profile.php");
}