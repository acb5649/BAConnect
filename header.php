<?php
require_once "session.php";
?>

<div style="position: sticky; position: -webkit-sticky; padding-bottom: 16px;" class="w3-top">
    <div class="w3-bar w3-lime w3-card">
        <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="toggleNav()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
        <!-- The homepage will have a feed of the newest users and updated users -->
        <a class="w3-bar-item w3-button w3-padding-large" href="/courseproject">BAConnect</a>
        <!-- If user is logged in, don't show this link -->
        <?php
        if($type == 0){
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'loginModal'".').style.display='."'block'".'">LOG IN</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'registerModal'".').style.display='."'block'".'">REGISTER</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'forgotModal'".').style.display='."'block'".'">FORGOT LOGIN</a>';
        }

        if($type > 0){
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" href="profile.php">PROFILE</a>';
            print '<a class="w3-bar-item w3-button w3-hover-red w3-padding-large w3-hide-small w3-right" href="logout.php">LOG OUT</a>';
        }

        if($type > 1){
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'matchModal'".').style.display='."'block'".'">MATCH USERS</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'editModal'".').style.display='."'block'".'">EDIT ACCOUNTS </a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'upgradeModal'".').style.display='."'block'".'">UPGRADE ACCOUNTS</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'searchModal'".').style.display='."'block'".'">USER SEARCH</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'addCountryModal'".').style.display='."'block'".'">ADD COUNTRY</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'addDegreeModal'".').style.display='."'block'".'">ADD DEGREE TYPE</a>';
            print '<a class="w3-bar-item w3-button w3-padding-large w3-hide-small" onclick="document.getElementById('."'addStateModal'".').style.display='."'block'".'">ADD STATE</a>';
        }
        ?>
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
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'matchModal'".').style.display='."'block'".'">MATCH USERS</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'editModal'".').style.display='."'block'".'">EDIT ACCOUNTS </a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'upgradeModal'".').style.display='."'block'".'">UPGRADE ACCOUNTS</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'searchModal'".').style.display='."'block'".'">USER SEARCH</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'addCountryModal'".').style.display='."'block'".'">ADD COUNTRY</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'addDegreeModal'".').style.display='."'block'".'">ADD DEGREE TYPE</a>';
        print '<a class="w3-bar-item w3-button w3-padding-large" onclick="toggleNav();document.getElementById('."'addStateModal'".').style.display='."'block'".'">ADD STATE</a>';
    }
    ?>
</div>