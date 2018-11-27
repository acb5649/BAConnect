<?php
require_once "session.php";
require_once "database.php";

?>
<script>
    function getMentorHints(str) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("mentors").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "AJAX.php?action=getMentors&matching=" + str, true);
        xmlhttp.send();
    }
    function getMenteeHints(str) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("mentees").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "AJAX.php?action=getMentees&matching=" + str, true);
        xmlhttp.send();
    }
</script>
<div id="matchModal" action="index.php" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('matchModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Match Users
         </h2>
      </header>
      <form id="matchUser" action="index.php" class="w3-container" method="post">
         
            <div class="w3-row-padding">
               <div class="w3-half">
                 <p>
					<label>Mentor </label>
					<input type="text" list="mentors" id="mentor" name="mentor" value="" class="w3-input w3-border" placeholder="Enter a Mentor Username" onkeyup="getMentorHints(this.value)" required autofocus />
					<datalist id="mentors" >
					</datalist>
                 </p>
               </div>
               <div class="w3-half">
                 <p>
					<label>Mentee </label>
					<input type="text" list="mentees" id="mentee" name="mentee" value="" class="w3-input w3-border" placeholder="Enter a Mentee Username" onkeyup="getMenteeHints(this.value)" required />
					<datalist id="mentees" >
					</datalist>
                 </p>
               </div>

             </div>
         <!-- Submit -->
         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="match" id="match">Match</button>
      </form>
   </div>
</div>
