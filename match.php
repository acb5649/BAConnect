<div id="matchModal" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('matchModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Match Users
         </h2>
      </header>
      <form id="matchUser" class="w3-container" method="post">
         <?php
            // CONNECT to DB & read in MYSQL DATA

            /*******Associative Arrays use a name/value pair to access a value **********/

            //assign values directly
            $mentor["first"] = "uSam67";
            $mentor["second"] = "pEdna987";
            $mentor["third"] = "rMark5";
            $mentor["fourth"] = "jClayton_Star";
            $mentor["fifth"] = "FishJunior1";

            //assign values directly
            $mentee["first"] = "sSteve";
            $mentee["second"] = "uKarla98";
            $mentee["third"] = "jenSarahK67";
            $mentee["fourth"] = "Judd4";
            $mentee["fifth"] = "Marv3n";
            ?>
            <div class="w3-row-padding">
               <div class="w3-half">
                 <p>
                 <label>Mentor </label>
                 <select id="mentor" class="w3-select w3-border" name="mentor">
                 <?php
                    //using foreach to assign the label to a variable and the value to a variable

                    foreach ($mentor as $pos => $value) {
                        print '
                    <option value = "'.$pos.'">'.$value.'</option>';
                    }
                    ?>
                 </select>
                 </p>
               </div>
               <div class="w3-half">
                 <p>
                 <label>Mentee </label>
                 <select id="mentee" class="w3-select w3-border" name="mentee">
                 <?php
                    //using foreach to assign the label to a variable and the value to a variable

                    foreach ($mentee as $pos => $value) {
                        print '
                    <option value = "'.$pos.'">'.$value.'</option>';
                    }
                    ?>
                 </select>
                 </p>
               </div>

             </div>
         <!-- Submit -->
         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="match" id="match">Match</button>
      </form>
   </div>
</div>
<script>
   // When the user clicks anywhere outside of the modal, close it
   var matchModal = document.getElementById('matchModal');
   window.onclick = function(event) {
       if (event.target == matchModal) {
           matchModal.style.display = "none";
       }
   }
</script>
