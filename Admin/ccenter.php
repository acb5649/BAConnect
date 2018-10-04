<!--DOCTYPE html-->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Admin Control Panel</title>
		<!-- Link to css for theme colors -->
		<link href="theme/jquery-ui.css" rel="stylesheet"> 
		<link href="theme/jquery-ui.structure.css" rel="stylesheet"> 
		<link href="theme/jquery-ui.theme.css" rel="stylesheet"> 
	
		<!-- Website base CSS -->
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body id="body">
		<?php 
		$userName = $_GET['uN'];
		
		if (isset($_POST['logout'])) {
				$userName="";
			header ("Location:login.php?");
		}
		?>
		<div align="right">
			<?php print '<label>Welcome '.$userName.'!</label>';?>
			<button id="settings" name = "settings"  type = "button">Settings</button>
			<a href="login.php"><button id="logout" name = "logout"  type = "button">Logout</button></a>
		</div>
		<div id="command">
			<h3>Match Users</h3>
			<div>
				<?php include "userMatch.php";?>
			</div>
			<h3>Edit Account(s)</h3>
			<div>
				<?php include "editAcc.php";?>
			</div>
			<h3>Upgrade Account(s)</h3>
			<div>
				<?php include "upgradeAcc.php";?>
			</div>
			<h3>Search User Database</h3>
			<div>
				<?php include "searchDB.php";?>
			</div>
		</div>
		
		<!-- link to jQuery-->
		<script src="js/jquery-3.3.1.js"></script>
		
		<!-- Link to jQuery Library -->
		<script src="theme/external/jquery/jquery.js"></script>
		
		<!-- jQuery UI functionality -->
		<script type="text/javascript" src="theme/jquery-ui.js"></script>
		
		<!-- Link to jQuery Validator -->
		<script type="text/javascript" src="validation/dist/jquery.validate.js"></script>
		
		<!--Script for website-->
		<script type="text/javascript" src="js/cpanel.js"></script>
		
	<body>
</html>