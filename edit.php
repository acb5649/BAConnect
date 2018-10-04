<div id="editModal" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('editModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Edit Account
         </h2>
      </header>
      <form id="editAcc" class="w3-container" method="post">
         <?php
            // CONNECT to DB & read in MYSQL DATA

            ?>
         <!--Username-->
         <p>
            <label><i class="fa fa-user"></i> Username</label>
         </p>
         <input type="text" id="editSearchUser" placeholder="Enter Username" name="editSearchUser" class="w3-input w3-border" required autofocus>
         <!-- Submit -->
         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="editSearch" id="editSearch">Search</button>
      </form>
   </div>
</div>
<script>
   // When the user clicks anywhere outside of the modal, close it
   var editModal = document.getElementById('editModal');
   window.onclick = function(event) {
       if (event.target == modal) {
           editModal.style.display = "none";
       }
   }
</script>
