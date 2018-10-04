<!--DOCTYPE html-->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Account Recovery</title>
		<!-- Link to css for theme colors -->
		<link href="theme/jquery-ui.css" rel="stylesheet"> 
		<link href="theme/jquery-ui.structure.css" rel="stylesheet"> 
		<link href="theme/jquery-ui.theme.css" rel="stylesheet"> 
	
		<!-- Website base CSS -->
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body id="body">
		<div id="recover">
			<h3>Recover By Email</h3>
			<div>
				<?php include "recoverE.php";?>
			</div>
			<h3>Recover By Text</h3>
			<div>
				<?php include "recoverT.php";?>
			</div>
		</div>
		<?php include "footer.php"; ?>
		<!-- link to jQuery-->
		<script src="js/jquery-3.3.1.js"></script>
		<!-- Link to jQuery Library -->
		<script src="theme/external/jquery/jquery.js"></script>
		<!-- jQuery UI functionality -->
		<script type="text/javascript" src="theme/jquery-ui.js"></script>
		<!-- Link to jQuery Validator -->
		<script type="text/javascript" src="validation/dist/jquery.validate.js"></script>
		<!--Script for website-->
		<script type="text/javascript" src="js/recover.js"></script>
	<body>
</html>