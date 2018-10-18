<?php
	if (isset($_POST['reset'])) {
		$code = $_GET['reset_password'];
		require_once "dbhelper.php";
		$password = $_POST['editPassword'];
		if (forgot_password($code, $password)) {
			print '<meta http-equiv="refresh" content="0;url=success.php">';
		} else {
			print '<meta http-equiv="refresh" content="0;url=failed.php">';
		}
	}
?>

<div id="resetPasswordModal" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('resetPasswordModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Reset Password
         </h2>
      </header>
      <form id="editPassword" class="w3-container" method="post">
         <?php
            // CONNECT to DB & read in MYSQL DATA

            ?>
         <!--Password-->
         <p>
            <label><i class="fa fa-user"></i>Password</label>
         </p>
         <input type="password" id="editPassword" placeholder="Enter Password" name="editPassword" class="w3-input w3-border" >
         <!-- Submit -->
         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="reset" id="reset">Submit</button>
      </form>
   </div>
</div>
<script>
   // When the user clicks anywhere outside of the modal, close it
   var editModal = document.getElementById('resetPasswordModal');
   window.onclick = function(event) {
       if (event.target == editModal) {
           editModal.style.display = "none";
       }
   }
</script>
