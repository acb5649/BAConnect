<?php
include_once "database.php";

if (isset($_GET['account_id'])) {
    $con = Connection::connect();
    $result = $con->prepare("SELECT picture FROM Pictures where account_ID = ?");
    if ($result->execute(array($_GET['account_id']))) {
        $row = $result->fetch();
        if ($row['picture'] && ($row['picture'] != "")) {
            echo base64_encode($row['picture']);
        } else {
            echo base64_encode(file_get_contents ('https://soulcore.com/wp-content/uploads/2018/01/profile-placeholder.png'));
        }
    } else {
        echo base64_encode(file_get_contents ('https://soulcore.com/wp-content/uploads/2018/01/profile-placeholder.png'));
    }
}