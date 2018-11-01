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

if($_GET["action"] == "loadCards"){
    if(!isset($_GET["offset"])){
        $offset = 0;
    } else {
        $offset = $_GET["offset"];
    }
    $con = Connection::connect();
    $stmt = $con->prepare("SELECT `account_ID` FROM Information LIMIT 30 OFFSET " . $offset);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $con = null;
    echo json_encode($result);
}


?>
