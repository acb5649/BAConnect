<?php
require_once "session.php";
require_once "database.php";
require_once "card.php";

if(isset($_POST["action"]) && $_POST["action"] == "loadCards"){
    if(!isset($_POST["offset"])){
        $offset = 0;
    } else {
        $offset = $_POST["offset"];
    }

    if(!isset($_POST["search"])){
        $num = $_POST["num"];

        $con = Connection::connect();
        $stmt = $con->prepare("SELECT `account_ID` FROM Information LIMIT " . $num . " OFFSET " . $offset);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $con = null;
        echo json_encode($result);
        die();
    } else {
        $num = $_POST["num"];
        $search = $_POST["search"];

        $con = Connection::connect();
        $stmt = $con->prepare("SELECT `account_ID` FROM UserAddressView where '%" . $search . "%' IN (`state`, `country`, `state_name`, `city`, `post_code`, `street_address`, `street_address2`, `first_name`, `middle_name`, `last_name`, `gender`, `facebook`, `linkedin`, `mentorship_preference`, `dob`, `phone_number`) LIMIT " . $num . " OFFSET " . $offset);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $con = null;
        echo json_encode($result);
        die();
    }
}

if(isset($_POST["action"]) && $_POST["action"] == "openModal"){
    echo "<script>document.getElementById('" . $_POST["modal"] . "').style.display='block';</script>";
}

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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="js/registration.js"></script>
    <script src="js/closeModals.js"></script>
    <script src="js/cardHandler.js"></script>
    <script>
        // Used to toggle the menu on small screens when clicking on the menu button
        function toggleNav() {
            let x = document.getElementById("navMobile");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }

        var offset = 0;

        function continuallyLoadCards(num = 10) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var array = JSON.parse(this.responseText);
                    console.log(array);
                    cardAjax(array);
                }
            };
            let params = "action=loadCards&num=" + num +"&offset=" + offset;
            xmlhttp.open("POST", "index.php", true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(params);
            offset += num;
        }

        continuallyLoadCards(30);

        function searchCards(num = 30, startOver = true) {
            if (startOver) {
                document.getElementById("mentorDisplay").innerHTML = "";
                offset = 0;
            }

            let term = document.getElementById("searchBox").value;
            if (term === "") {
                continuallyLoadCards(30);
                return;
            }

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var array = JSON.parse(this.responseText);
                    console.log(array);
                    cardAjax([...new Set(array)]);
                }
            };

            let params = "action=loadCards&num=" + num +"&offset=" + offset + "&search=" + term;
            xmlhttp.open("POST", "index.php", true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(params);
            offset += num;
        }

        window.onscroll = function(ev) {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                let term = document.getElementById("searchBox").value;
                if (term = "") {
                    continuallyLoadCards(10);
                } else {
                    searchCards(10, false);
                }
            }
        };

    </script>
</head>

<body class="w3-light-grey" onload="init();">
<!-- Navbar -->
<?php include "header.php"; ?>
<!-- Page content -->
<div id="mentorDisplay" class="flex-container" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: stretch; align-content: flex-start;">

</div>
</body>
</html>
