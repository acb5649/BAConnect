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

    $num = $_POST["num"];

    $con = Connection::connect();
    $stmt = $con->prepare("SELECT `account_ID` FROM Information LIMIT " . $num . " OFFSET " . $offset);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $con = null;
    echo json_encode($result);
    die();
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
    <script src="js/registration.js"></script>
    <script src="js/closeModals.js"></script>
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

        function cardAjax(ids) {
            ids.forEach(function(id) {
                if (id["account_ID"] > 4) {
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("mentorDisplay").innerHTML += this.responseText;
                            imageAjax(id);
                        }
                    };
                    //console.log("getting card: " + id["account_ID"]);
                    xmlhttp.open("GET", "card.php?id=" + id["account_ID"], true);
                    xmlhttp.send();
                }
            });
        }

        function imageAjax(id) {
            if (id["account_ID"] > 4) {
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById(id["account_ID"]).src = this.responseText;
                    }
                };
                xmlhttp.open("GET", "image.php?account_id=" + id["account_ID"], true);
                xmlhttp.send();
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
            offset += 10;
        }

        continuallyLoadCards(30);

        window.onscroll = function(ev) {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                continuallyLoadCards()
            }
        };

    </script>
</head>

<body class="w3-light-grey" onload="init();">
<!-- Navbar -->
<?php include "header.php"; ?>
<!-- Page content -->
<div id="mentorDisplay">

</div>
</body>
</html>
