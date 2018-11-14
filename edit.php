<?php
require_once "session.php";
require_once "database.php";

?>

<div id="editModal" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('editModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Edit Account
         </h2>
      </header>
      <form id="editAcc" class="w3-container" method="post" action="index.php">
         <p>
            <label><i class="fa fa-user"></i> Username</label>
         </p>
          <input type="text" list="users" id="username" name="username" value="" class="w3-input w3-border" placeholder = "Enter a Username" onkeyup="getUserHints(this.value)" required autofocus />
          <datalist id="users" >
          </datalist>
         <!-- Submit -->
         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="editSearch" id="editSearch">Go to Profile</button>
      </form>
   </div>
</div>
