<?php
	$userName=" ";
	if (isset($_POST['connect'])) {
			$userName = trim($_POST['logUser']);
			Header ("Location:ccenter.php?uN=".$userName) ;
			
	}
?>
<form id="loginUser"  action="login.php" method="post">
	<!--Username-->
	<h3 class = "txt">Username:</h3>
	<input type="text" id="logUser" placeholder="Enter Username" name="logUser" class="tbx" required autofocus><br><br>
				
	<!--Password-->
	<h3 class = "txt">Password:</h3>
	<input type="password" id="logPassword" placeholder="Enter Password" name="logPassword" class="tbx" required><br><br>
	
	<div align="right" >	
		<!-- User Login -->
		<button name = "connect" type="submit" class="btn" id="connect"  >Login</button>
	
		<!-- Forgot Password -->
		<a href="recovery.php" align ="right"><h3 class="link">Forgot Password?</h3></a>
	</div>			
</form>
