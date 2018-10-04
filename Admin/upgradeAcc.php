<form id="upgradeAcc"  action="ccenter.php" method="post">
					<?php
						// CONNECT to DB & read in MYSQL DATA
						
						
						/*******Associative Arrays use a name/value pair to access a value **********/

						//assign values directly
						$type["1"] = "User";
						$type["2"] = "Mentee";			
						$type["3"] = "Mentor";
						$type["4"] = "Admin";
					?>
					<!--Username-->
					<label class = "txt">Username: </label>
					<input type="text" id="upAcc" placeholder="Enter Username" name="upAcc" class="tbx" required autofocus><br><br>
					<select id ="type" name = "type">
						<?php
						//using foreach to assign the label to a variable and the value to a variable
		
							foreach ($type as $pos => $value)
								print '	<option value = "'.$value.'">'.$value.'</option>';
						?>
					</select>
					<!-- Submit -->
					<button type="submit" class="btn" name="upgrade" id="upgrade">Upgrade</button>
</form><br>