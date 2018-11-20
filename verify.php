<?php
require_once "database.php";
require_once "session.php";

$code = filter_input(INPUT_GET, "code");
$email = filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL);
$type = filter_input(INPUT_GET, "type");

if ($code && $email) {
    if ($type == "reg") {
        if (verifyCode($code, $email)) {
            echo "<script> console.log(" . $email . ") </script>";
            $con = Connection::connect();
            $stmt = $con->prepare("select account_ID from Information where email_address = ?");
            $stmt->bindValue(1, $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $account_id = $row['account_ID'];

            $stmt = $con->prepare("UPDATE Account SET active = '1' WHERE account_ID = ?");
            $stmt->bindValue(1, $account_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: success.php");
        } else {
            header("Location: failure.php");
        }
    } elseif ($type == "reset") {
        $_SESSION['email'] = $email;
        $_SESSION['code'] = $code;
        header("Location: changePassword.php");
    }
} else {
    header("Location: failed.php");
}
