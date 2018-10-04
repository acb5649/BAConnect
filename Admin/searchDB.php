<form id="searchDBform"  action="ccenter.php" method="post">
					<?php
						// CONNECT to DB & read in MYSQL DATA
						
						
					
					?>
					<div align ="right">
						<!--First name-->
						<label class = "txt">First Name:</label>
						<input type="text" id="searchFirst" placeholder="Enter Last Name" name="SearchFirst" class="tbx">
						<!--Last name-->
						<label class = "txt">Last Name:</label>
						<input type="text" id="searchLast" placeholder="Enter First Name" name="searchLast" class="tbx">
						<!--Username-->
						<label class = "txt">Username:</label>
						<input type="text" id="searchUser" placeholder="Enter Username" name="searchUser" class="tbx"><br>
						<!--Email-->
						<label class = "txt">Email:</label>
						<input type="Email" id="searchEmail" placeholder="Enter Email" name="searchEmail" class="tbx">
						<!-- Submit -->
						<button type="submit" class="btn" name="searchDB" id="searchDB" disabled>Search</button>
					</div><br>
					<fieldset>
					<br>
					</br>
					</fieldset>
					<div class="cbox">
						<label class = "txt">Filter:</label>
						<label class"cbox"><input type="checkbox"   name = "student" value="1">Student</label>
						<label class"cbox"><input type="checkbox"  name = "Working Professional" value="1">Working Professional</label>
						<label class"cbox"><input type="checkbox"   name = "Mentee" value="1">Mentee</label>
						<label class"cbox"><input type="checkbox"  name = "Mentor" value="1">Mentor</label><br>
					</div>
					
</form><br>