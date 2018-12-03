<?php

require_once "database.php";

require_once "session.php";
if(isset($_POST["security"])){
    $_SESSION['email'] = trim($_POST['email']);
    echo "<script>document.getElementById('securityModal').style.display='block'';</script>";
    header('Location: index.php');
    die;
}

?>

<div id="forgotModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('forgotModal').style.display='none'"
                  class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Reset Password </h2>
        </header>
        <form method="post" action="index.php" class="w3-container">
            <p>
                <label>
                    <i class="fa fa-user"></i> Email associated with an account
                </label>
            </p>
            <input class="w3-input w3-border" type="text" style = "text-align:center;" placeholder="Enter Email" name="email" id="email">
            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" onclick="document.getElementById('securityModal').style.display='block'" type="submit" name="security">
                Reset Password
                <i class="fa fa-check"></i>
            </button>
            <button type="button" class="w3-button w3-red w3-section"
                    onclick="document.getElementById('forgotModal').style.display='none'">Close
                <i class="fa fa-remove"></i>
            </button>
            <p class="w3-right"><a href="#" style="color: #CDDC37;" onclick="document.getElementById('registerModal').style.display='block'">Need an account?</a></p>
        </form>
    </div>
</div>
