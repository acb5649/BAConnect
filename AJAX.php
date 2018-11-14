<?php
require_once "database.php";
if(!isset($_GET["action"])){
    header("Location:index.php");
}


if($_GET["action"] == "refreshState"){
    if(!isset($_GET["country"])){
        header("Location:index.php");
    }
    $countryID = $_GET["country"];
    $options = getStatesList($countryID);

    echo $options;
}

if($_GET["action"] == "getDegrees"){
    echo listDegreeTypes();
}

if($_GET["action"] == "getUsernames"){
    if (isset($_GET["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `Account` where username LIKE '%".$_GET["matching"]."%'");
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
if($_GET["action"] == "getMentees"){
    if (isset($_GET["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `MenteeOptions` where username LIKE '%".$_GET["matching"]."%'");
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
if($_GET["action"] == "getMentors"){
    if (isset($_GET["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `MentorOptions` where username LIKE '%".$_GET["matching"]."%'");
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

?>
