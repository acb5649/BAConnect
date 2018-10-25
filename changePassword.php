<?php
require_once "database.php";
require_once "session.php";

if (!isset($_SESSION['email']) || !isset($_SESSION['code'])) {
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $pw_1 = filter_input(INPUT_POST, "password_1");
    $pw_2 = filter_input(INPUT_POST, "password_2");

    if (($pw_1 == $pw_2) && changePassword($_SESSION['email'], $_SESSION['code'], $pw_1)) {
        header("Location: success.php");
    } else {
        header("Location: failed.php");
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAConnect Home</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="js/registration.js"></script>
    <script src="js/closeModals.js"></script>
</head>

<body class="w3-light-grey" onload="document.getElementById('changePassModal').style.display='block';">
<!-- Navbar -->
<?php include "header.php"; ?>
<!-- modals -->
<?php
if ($type == 1) {
    include "profile.php";
}

if ($type == 0) {
    include "login.php";
    include "register.php";
    include "forgot.php";
}

if ($type > 1) {
    include "match.php";
    include "edit.php";
    include "upgrade.php";
    include "search.php";
    include "addCountry.php";
    include "addDegreeType.php";
}
?>
<!-- Page content -->
<div id="changePassModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('changePassModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide">
                <i class="w3-margin-right"></i>Change Password
            </h2>
        </header>
        <form method="post" action="changePassword.php" class="w3-container">
            <p>
                <label>
                    <i class="fa fa-lock"></i> New Password
                </label>
            </p>
            <input class="w3-input w3-border" type="password" placeholder="" name="password_1" id="password_1">

            <p>
                <label>
                    <i class="fa fa-lock"></i> New Password again
                </label>
            </p>
            <input class="w3-input w3-border" type="password" placeholder="" name="password_2" id="password_2">

            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="submit" name="submit" id="submit">Change Password
                <i class="fa fa-check"></i>
            </button>
            <button type="button" class="w3-button w3-red w3-section" onclick="document.getElementById('changePassModal').style.display='none'">Close
                <i class="fa fa-remove"></i>
            </button>
        </form>
    </div>
</div>


<!-- End Page Content -->
</body>
</html>