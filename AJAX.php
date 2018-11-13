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

?>
