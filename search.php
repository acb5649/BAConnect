<div id="searchModal" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('searchModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Search User Database
         </h2>
      </header>
      <form id="searchDBform" class="w3-container" method="post">
         <?php
            // CONNECT to DB & read in MYSQL DATA

            ?>
         <!--First name-->
         <div class="w3-row-padding">
            <div class="w3-quarter">
              <p><label>First Name </label><input type="text" id="searchFirst" placeholder="" name="SearchFirst" class="w3-input w3-border"></p>
            </div>
            <div class="w3-quarter">
              <p><label>Last Name </label><input type="text" id="searchLast" placeholder="" name="searchLast" class="w3-input w3-border"></p>
            </div>
            <div class="w3-quarter">
              <p><label>Username </label><input type="text" id="searchUser" placeholder="" name="searchUser" class="w3-input w3-border"></p>
            </div>
            <div class="w3-quarter">
              <p><label>Email </label><input type="Email" id="searchEmail" placeholder="" name="searchEmail" class="w3-input w3-border"></p>
            </div>
          </div>

          <div class="w3-row-padding w3-panel w3-border w3-round-small">
             <p>
             <label>Filter: </label>
             <label><input class="w3-check w3-border" type="checkbox" name="student" value="1">Student</label>
             <label><input class="w3-check w3-border" type="checkbox" name="working_professional" value="1">Working Professional</label>
             <label><input class="w3-check w3-border" type="checkbox" name="mentee" value="1">Mentee</label>
             <label><input class="w3-check w3-border" type="checkbox" name="mentor" value="1">Mentor</label>
             </p>
          </div>

         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right"  name="searchDB" id="searchDB" disabled>Search</button>
         <br>
         <fieldset>
            <br></br>
         </fieldset>

      </form>
   </div>
</div>
