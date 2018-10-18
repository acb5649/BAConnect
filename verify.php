<?php
require_once "functions.php";

$code = filter_input(INPUT_GET, "code");
$email = filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL);

if ($code && $email) {
  if (verifyCode($code, $email)) {
    $stmt = $con->prepare("select account_ID from Information where email_address = '" . $email . "'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $account_id = $row['account_ID'];

    $stmt = $con->prepare("UPDATE Account SET active = '1' WHERE account_ID = '" . $account_id . "'");
  	$stmt->execute();

    header("Location: success.php");
  } else {
    header("Location: failure.php");
  }
} else {
  header("Location: failure.php");
}

?>
