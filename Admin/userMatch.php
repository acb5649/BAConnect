<form id="matchUser"  action="ccenter.php" method="post">
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
	<select id ="mentor" name = "mentor">
		<?php
			//using foreach to assign the label to a variable and the value to a variable
		
			foreach ($mentor as $pos => $value)
				print '	<option value = "'.$pos.'">'.$value.'</option>';
		?>
	</select>
	<select id="mentee" name = "mentee">
		<?php
			//using foreach to assign the label to a variable and the value to a variable
		
			foreach ($mentee as $pos => $value)
				print '	<option value = "'.$pos.'">'.$value.'</option>';
		?>
	</select>
	<!-- Submit -->
	<button type="submit" class="btn" name= "match" id="match">Match</button>
</form><br>