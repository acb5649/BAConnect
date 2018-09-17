<?php
	require_once "functions.php";
	$msg = "";

	//if the user fails to log in, then the password an username input boxes will
	//contain what they previously entered.
	$pwdDefault = "";
	$uNameDefault = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- Name: Eric Strayer -->
<!-- Date: 9/16/2018 -->
<!-- File Name: form.php -->

<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Functions Demo</title>
	<style type = "text/css">
  		h1, h2 {
    		text-align: center;
  		}
	</style>

	</head>

	<body>

		<?php
			if (isset($_POST['enter']))
			{
				//just naming the variables now

				$password = "";
				$username = "";

				$uError = false;
				$pError = false;

				$username =  trim($_POST['userName']);
				$password =  trim($_POST['pwd']);

				if(strlen($username) == 0) {
					$uError = true;
					$msg = $msg . "please enter a username<br />";
				}
				else {
					$uNameDefault = $username;
				}
				if(strlen($password) == 0){
					$pError = true;
					$msg = $msg . "please enter a password<br />";
				}
				else {
					$pwdDefault = $password;
				}

				if(!$uError && !$pError){
					if(isValidLogin($username, $password)){
						$msg = "Login successful<br />";
						//log the user in
					}
					else {
						$msg = "Username and/or password are invalid";
					}
				}
			}
		?>

		<form action="LogIn.php" method="post">
			<?php
				print $msg;
				$msg = "";
			?>
			<br />

			Username: <input type="text" maxlength = "50" value = "<?php echo $uNameDefault; ?>" name="userName" id="userName"   /> <br />

			Password: <input type="password" maxlength = "50" value = "<?php echo $pwdDefault; ?>" name="pwd" id="pwd"   /> <br />
			<br />

			<br />
			<input name="enter" class="btn" type="submit" value="LogIn" />
		</form>
	</body>
</html>
