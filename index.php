<?php
require_once "session.php";
require_once "database.php";
require_once "card.php";
?>
<!-- template from: https://www.w3schools.com/w3css/w3css_templates.asp -->
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

<body onload="init();">
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
<div style="position: fixed;" class="w3-row-padding" id="mentorDisplay">
    <?php for ($k = 0; $k < 15; $k++) {
        $card = createCard(0);
        echo '<div class="w3-col m4 l3 w3-center">' . $card . '</div>';
    }; ?>
</div>

<!-- End Page Content -->
</body>
</html>
