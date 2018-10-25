<?php
include_once "database.php";

if (isset($_GET['account_id'])) {
    $con = Connection::connect();
    $result = $con->prepare("SELECT resume_file FROM Resumes where account_ID = ?");
    if ($result->execute(array($_GET['account_id']))) {
        $row = $result->fetch();
        echo $row['resume_file'];
    }
}