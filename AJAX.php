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
    $options = getStates($countryID);

    echo "<select class="w3-select w3-border" name="newState" id = "newState">";

    echo $options;
    echo "</select>";
}


?>
