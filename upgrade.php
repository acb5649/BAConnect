<?php
require_once "session.php";
require_once "database.php";

?>

<script>

    function getUserHints(str) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("users").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "AJAX.php?action=getUsernames&matching=" + str, true);
        xmlhttp.send();
    }


</script>

<div id="upgradeModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('upgradeModal').style.display='none'"
                  class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide">
                <i class="w3-margin-right"></i>Upgrade Account
            </h2>
        </header>
        <form id="upgradeAcc" class="w3-container" method="post" action="index.php">
            <!--Username-->
            <p>
                <label><i class="fa fa-user"></i> Username</label>
            </p>
            <input type="text" list="users" id="username" name="username" value="" class="w3-input w3-border"
                   onkeyup="getUserHints(this.value)" required autofocus/>
            <datalist id="users">

            </datalist>
            <br>
            <select id="type" class="w3-select w3-border" name="type">
                <?php

                //using foreach to assign the label to a variable and the value to a variable
                $list = "";

                //foreach ($type as $pos => $value) {
                $rank = $type;

                $types = array(
                    1 => "User",
                    2 => "Coordinator",
                    3 => "Admin",
                );

                for ($x = 1; $x < 4 && $x < $rank; $x++) {
                    $list = $list . '<option value = "' . $x . '">' . $types[$x] . '</option>';
                }
                print $list;
                ?>
            </select>
            <!-- Submit -->
            <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="upgrade"
                    id="upgrade">Upgrade
            </button>
        </form>
    </div>
</div>
