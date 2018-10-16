<?php
require_once "functions.php";

$code = filter_input(INPUT_GET, "code");
$email = filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL);

if ($code && $email) {
  if valididateCode($code, $email) {
    
    header("Location: success.php");
  } else {
    header("Location: failure.php");
  }
}

?>
