<?php
require_once "database.php";
require_once "session.php";

if(!isset($_REQUEST["action"])){
    echo "";
    die();
}

if($_REQUEST["action"] == "refreshState"){
    if(!isset($_REQUEST["country"])){
        header("Location:index.php");
    }
    $countryID = $_REQUEST["country"];
    $options = getStatesList($countryID);

    echo $options;
}

if($_REQUEST["action"] == "getDegrees"){
    echo listDegreeTypes();
}

if($_REQUEST["action"] == "getUsernames" && $type > 1){
    if (isset($_REQUEST["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `Account` where username LIKE '%".$_REQUEST["matching"]."%'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = "";
        foreach($row as $rw) {
            $result .= '<option> ' . $rw["username"] . ' </option> ';
        }
        echo $result;
    } else {
        echo "";
    }
}
if($_REQUEST["action"] == "getMentees" && $type > 1){
    if (isset($_REQUEST["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `MenteeOptions` where username LIKE '%".$_REQUEST["matching"]."%'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = "";
        foreach($row as $rw) {
            $result .= '<option> ' . $rw["username"] . ' </option> ';
        }
        echo $result;
    } else {
        echo "";
    }
}
if($_REQUEST["action"] == "getMentors" && $type > 1){
    if (isset($_REQUEST["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `MentorOptions` where username LIKE '%".$_REQUEST["matching"]."%'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = "";
        foreach($row as $rw) {
            $result .= '<option> ' . $rw["username"] . ' </option> ';
        }
        echo $result;
    } else {
        echo "";
    }
}

if($_REQUEST['action'] == "adminStartPair"){
    $_SESSION['pair_user'] = $_SESSION["profile_ID"];
    echo formatAdminPairingBox();
    die();
}

if($_REQUEST['action'] == "adminFinishPair"){
    $user1 = $_SESSION['pair_user'];
    $user2 = $_SESSION["profile_ID"];
    unset($_SESSION['pair_user']);
    echo "mentor=" . getUsernameFromAccountID($user1) . "&mentee=" . getUsernameFromAccountID($user2) . "&match=";
    die();
}

if($_REQUEST['action'] == "adminClearPair"){
    unset($_SESSION['pair_user']);
    echo formatAdminPairingBox();
    die();
}
?>
