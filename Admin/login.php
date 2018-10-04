<!--DOCTYPE html-->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		<!-- Link to css for theme colors -->
		<link href="theme/jquery-ui.css" rel="stylesheet"> 
		<link href="theme/jquery-ui.structure.css" rel="stylesheet"> 
		<link href="theme/jquery-ui.theme.css" rel="stylesheet"> 
	
		<!-- Website base CSS -->
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body id="body" onload="addField();">
		<div id="userData">
			<ul>
				<li><a href="#login">Login</a></li>
				<li><a href="#register">Register</a></li>
			</ul>
			<div id="login" >
				<?php include "loginpage.php";?>
				<?php include "footer.php"; ?>
			</div>
			<div id="register">
				<?php include "registerpage.php";?>
				<?php include "footer.php"; ?>
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
		<script type="text/javascript" src="js/login.js"></script>
		
		<!--Script for registration-->
		<script type="text/javascript" src="js/school.js"></script>
		
	<body>
</html>