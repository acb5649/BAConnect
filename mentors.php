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
    <title>BAConnect Mentors</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="js/registration.js"></script>
    <script src="js/closeModals.js"></script>
    <script src="js/cardHandler.js"></script>
</head>

<body class="w3-light-grey" onload="init();">
<!-- Navbar -->
<?php include "header.php"; ?>
<!-- Page content -->
<div id="cardDisplay" class="flex-container" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: stretch; align-content: flex-start;">

</div>
</body>
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

    function continuallyLoadCards(num) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let array = JSON.parse(this.responseText);
                //console.log(array);
                cardAjax(array);
            }
        };
        let params = "action=loadCards&num=" + num +"&offset=" + offset + "&pref=0";
        xmlhttp.open("POST", "search.php", true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send(params);
        offset += num;
    }

    continuallyLoadCards(30);

    function searchCards(num, startOver) {
        if (startOver) {
            document.getElementById("cardDisplay").innerHTML = "";
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
                let array = JSON.parse(this.responseText);
                //console.log(array);
                //cardAjax([...new Set(array)]);
                cardAjax(array);
            }
        };

        let params = "action=loadCards&num=" + num +"&offset=" + offset + "&search=" + term + "&pref=0";
        xmlhttp.open("POST", "search.php", true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send(params);
        offset += num;
    }

    window.compatibleInnerHeight= function(){
        if(window.innerWidth != undefined){
            return window.innerHeight;
        }
        else{
            var B= document.body,
                D= document.documentElement;
            return Math.max(D.clientHeight, B.clientHeight);
        }
    };

    $(window).on("load", function(){
        $(window).on("scroll", function(){
            if (($(window).scrollTop() - ($(document).height() - $(window).height()) <= 5) && ($(window).scrollTop() - ($(document).height() - $(window).height()) >= -5)) {
                let term = document.getElementById("searchBox").value;
                if (term == "") {
                    continuallyLoadCards(10);
                } else {
                    searchCards(10, false);
                }
            }
        });
    });

</script>
</html>