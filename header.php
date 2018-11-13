<?php
require_once "session.php";
?>

<script>
    function openSearch(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>

<div style="position: sticky; position: -webkit-sticky; padding-bottom: 16px;" class="w3-top">
    <div class="w3-bar w3-lime w3-card" style="z-index: 0;">
        <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="toggleNav()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
        <!-- The homepage will have a feed of the newest users and updated users -->
        <a class="w3-bar-item w3-button w3-padding-large" href="index.php">BAConnect</a>
        <!-- If user is logged in, don't show this link -->
        <?php
        if($type == 0){
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'loginModal'".').style.display='."'block'".'">LOG IN</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'registerModal'".').style.display='."'block'".'">REGISTER</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'forgotModal'".').style.display='."'block'".'">FORGOT LOGIN</a>';
        }

        if($type > 0){
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" href="profile.php">PROFILE</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="openSearch(\'search\')">SEARCH</a>';
            print '<a class="w3-bar-item w3-button w3-hover-red w3-padding-large w3-hide-small w3-right" href="logout.php">LOG OUT</a>';
        }

        if($type > 1){
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'registerModal'".').style.display='."'block'".'">ADD USER</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'matchModal'".').style.display='."'block'".'">MATCH USERS</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'editModal'".').style.display='."'block'".'">EDIT ACCOUNTS </a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'upgradeModal'".').style.display='."'block'".'">UPGRADE ACCOUNTS</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'addCountryModal'".').style.display='."'block'".'">ADD COUNTRY</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'addDegreeModal'".').style.display='."'block'".'">ADD DEGREE TYPE</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'addStateModal'".').style.display='."'block'".'">ADD STATE</a>';
        }
        ?>
    </div>
    <div id="search" class="w3-center w3-hide w3-container w3-card w3-dark-grey w3-animate-top w3-padding-16" style="width: 50%; margin: auto; z-index: -1;">
        <input id="searchBox" class="w3-input w3-border" type="text" placeholder="Search..." style="width: 100%" onkeyup="searchCards()">
    </div>
</div>

<div id="navMobile" class="w3-bar-block w3-black w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
    <?php
    if($type == 0){
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'loginModal'".').style.display='."'block'".'">LOG IN</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'registerModal'".').style.display='."'block'".'">REGISTER</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'forgotModal'".').style.display='."'block'".'">FORGOT LOGIN</a>';
    }

    if($type > 0){
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();" href="profile.php">PROFILE</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" href="logout.php">LOG OUT</a>';
    }

    if($type > 1){
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'registerModal'".').style.display='."'block'".'">ADD USER</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'matchModal'".').style.display='."'block'".'">MATCH USERS</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'editModal'".').style.display='."'block'".'">EDIT ACCOUNTS </a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'upgradeModal'".').style.display='."'block'".'">UPGRADE ACCOUNTS</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'addCountryModal'".').style.display='."'block'".'">ADD COUNTRY</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'addDegreeModal'".').style.display='."'block'".'">ADD DEGREE TYPE</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'addStateModal'".').style.display='."'block'".'">ADD STATE</a>';
    }
    ?>
</div>

<?php

include "register.php";

if ($type == 0) {
    include "login.php";
    include "forgot.php";
}

if ($type > 1) {
    include "match.php";
    include "edit.php";
    include "upgrade.php";
    include "addCountry.php";
    include "addDegreeType.php";
    include "addState.php";
}
?>
