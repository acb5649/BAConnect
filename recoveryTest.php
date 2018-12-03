<?php
require_once "database.php";

require_once "session.php";

	
	function loadOnSecurity($accountID){
		$con = Connection::connect();
		$set=0;
		$question = '<select name="security_question" id="security_question" required><option value="">Select Question</option>';
		$query = "SELECT * FROM RecoveryQuestions WHERE account_ID = '".$accountID."' ORDER BY question_Number ASC";
		$statement = $con->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$question .= '<option value="'.$row['question_Number'].'">'.$row['question'].'</option>';
			$set= 1;
		}
		$question .= '</select>';
		if($set != 1){
			$con = null;
			header('Location: index.php');//skips questions
			return False;
		}
		$con = null;
		return $question;
	}//loadOnSecurity
	
	function enableNewSecurity($accountID, $question, $answer){// var for new questions and answers
		$con = Connection::connect();
		$isFirst = 0;
		$stmt = $con->prepare("SELECT question_Number FROM RecoveryQuestions WHERE account_ID = '" . $accountID . "' ORDER BY question_Number ASC");
		$stmt->execute();
		$search = $stmt->fetchAll();
		foreach($search as $row)
		{
			if($isFirst < $row['question_Number']){
				$isFirst = $row['question_Number'];
			}
		}
		if(!$isFirst){
			 $stmt = $con->prepare("insert into `RecoveryQuestions` (`account_ID`, `question_Number`, question, answer) values (?, ?, ?, ?)");
			 $stmt->bindValue(1, $accountID, PDO::PARAM_INT);
			 $stmt->bindValue(2, 1, PDO::PARAM_INT);
			 $stmt->bindValue(3, $question, PDO::PARAM_STR);
			 $stmt->bindValue(4, $answer, PDO::PARAM_STR);
			 $stmt->execute();
		}else{
			$stmt = $con->prepare("insert into `RecoveryQuestions` (`account_ID`, `question_Number`, question, answer) values (?, ?, ?, ?)");
			 $stmt->bindValue(1, $accountID, PDO::PARAM_INT);
			 $stmt->bindValue(2, $isFirst + 1, PDO::PARAM_INT);
			 $stmt->bindValue(3, $question, PDO::PARAM_STR);
			 $stmt->bindValue(4, $answer, PDO::PARAM_STR);
			 $stmt->execute();
			
		}
		$con = null;
		return true;
	}//enableNewSecurity
	function checkSecurityQ($accountID, $question_Num, $answer){
		$con = Connection::connect();
		$timeout= 1209600;// 2 weeks
		$time= $_SERVER['REQUEST_TIME'];//current
		$ct= 0;
		$stmt = $con->prepare("SELECT request_date FROM `Password Recovery` WHERE account_ID = '" . $accountID . "' ORDER BY request_date ASC");
		$stmt->execute();
		$search = $stmt->fetchAll();
		foreach($search as $row)
		{
			if($time - $row['request_date'] <= 1209600){
				$ct= $ct + 1;
			}
		}
		if(ct < 2){
			$stmt = $con->prepare("SELECT * FROM RecoveryQuestions WHERE account_ID = '" . $accountID . "' AND question_Number = '" . $question_Num . "' ORDER BY question_Number ASC");
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(strcasecmp($answer,$row['answer'])==0){
				$stmt = $con->prepare("insert into `Password Recovery` (account_ID, code) values (?, ?)");
				$stmt->bindValue(1, $accountID, PDO::PARAM_INT);
				$stmt->bindValue(2, $code, PDO::PARAM_STR);
				$stmt->execute();
				$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				//$url = str_replace("forgot.php", "verify.php", $url);//fix
				mail($email, "BAConnect: Reset Your Password", "Click this link to reset your password: http://" . $url . "?code=" . $code . "&email=" . urlencode($email) . "&type=reset");
				$con = null;
				return True;
				//return new Report("Success!", "An email has been sent to the address registered with your account.", "", TRUE);
			}else{
				$stmt = $con->prepare("insert into `Password Recovery` (account_ID, code) values (?, ?)");
				$stmt->bindValue(1, $accountID, PDO::PARAM_INT);
				$stmt->bindValue(2, $code, PDO::PARAM_STR);
				$stmt->execute();
				$con = null;
				return False;
				//return new Report("FAILURE", "There was an error validating your identity!", "", FALSE);
			}
		} else {
			$con = null;
			return False;
		}
	}
	$answerA = "";
?>
<html>
 <head>
  <title>Forgot Password Security System</title>
 </head>
 <body>
 <form action="recoveryTest.php" method="post">
 	<div>
 		<?php echo loadOnSecurity(1); ?>
 		<input type="password" maxlength = "150" value="<?php print $answerA; ?>" name="answerQuestion_A" id="answer_Q1" placeholder="Enter Answer!" required  /><br/>
 	</div>
 	<input name="enter" class="btn" type="submit" value="Submit" /><br/>
</form>
 </body>
</html>