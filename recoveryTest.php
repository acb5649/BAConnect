<?php
require_once "database.php";

require_once "session.php";

	
	function loadOnSecurity($accountID){
		$con = Connection::connect();
		$set=0;
		$question = '';
		$query = "SELECT * FROM RecoveryQuestions WHERE account_ID = '".$accountID."' ORDER BY question_Number ASC";
		$statement = $con->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$question .= '<option value='.$row['question_Number'].'>'.$row['question'].'</option>';
			$set= 1;
		}
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
	}//end checkSecurity
	function getSet($accountID){
		$con = Connection::connect();
		$set=0;
		$question = '';
		$query = "SELECT * FROM RecoveryQuestions WHERE account_ID = '".$accountID."' ORDER BY question_Number ASC";
		$statement = $con->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$question .= '<option value="'.$row['question_Number'].'">'.$row['question'].'</option>';
			$set= $set + 1;
		}
		$con = null;
		return $set;
	}
	$countSet = getSet(1);
	$answerA = "";
	$answerB = "";
	$answerC = "";
	$questionA = 0;
	$questionB = 0;
	$questionC = 0;
	$continue = 1;
	$msg = "";
	if ((isset($_POST['enter']))|| ($countSet === 0)){
		if(isset($_POST['answerQuestion_A'])){
			$answerA = trim($_POST['answerQuestion_A']);
		}
		if(isset($_POST['answerQuestion_B'])){
			$answerA = trim($_POST['answerQuestion_B']);
		}
		if(isset($_POST['answerQuestion_C'])){
			$answerA = trim($_POST['answerQuestion_C']);
		}
		if ($countSet >0){
			if(isset($_POST['security_question_A']) != 0){
				$questionA = trim($_POST['security_question_A']);
			}
		}
		if($countSet > 1){
			if(isset($_POST['security_question_B'])){
				$questionB = trim($_POST['security_question_B']);
				if ($questionB == $questionA){
					$answerA = "";
					$answerB = "";
					$answerC = "";
					$questionA = 0;
					$questionB = 0;
					$questionC = 0;
					$msg .= "<span style='color:red'><br/>Question 1 and Question 2 are identical please change one or both of them!<br/></span>";
				}
			}
		}
		if($countSet > 2){
			if(isset($_POST['security_question_C'])){
				$questionC = trim($_POST['security_question_C']);
				if ($questionA == $questionC){
					$answerA = "";
					$answerB = "";
					$answerC = "";
					$questionA = 0;
					$questionB = 0;
					$questionC = 0;
					$msg .= "<span style='color:red'><br/>Question 1 and Question 3 are identical please change one or both of them!<br/></span>";
				}
				if ($questionB == $questionC){
					$answerA = "";
					$answerB = "";
					$answerC = "";
					$questionA = 0;
					$questionB = 0;
					$questionC = 0;
					$msg .= "<span style='color:red'><br/>Question 2 and Question 3 are identical please change one or both of them!<br/></span>";
				}

			}
		}
		if($continue != 0 || $countSet === 0){
			//send recover to mailer code goes here
		}
	}
	print $msg;
	$msg = "";
?>
<html>
 <head>
  <title>Forgot Password Security System</title>
 </head>
 <body>
 <form action="recoveryTest.php" method="post">
 	<div>
		<?php if($countSet > 0){
	 				print "<b>Question 1.</b><br/><br/>";
					print '<select name="security_question_A" id="security_question_A" required><option value="">Select A Security Question</option>'; 
 					echo loadOnSecurity(1); 
					print "</select><br/>";
					print '<input type="password" maxlength = "150" value="'.$answerA.'" name="answerQuestion_A" id="answer_Q1" placeholder="Enter Answer Here" required  /><br/><br/><br/>';
				 }
			if($countSet > 1){
				print "<b>Question 2.</b><br/><br/>";
 				print '<select name="security_question_B" id="security_question_B" required><option value="">Select A Security Question</option>';
 				echo loadOnSecurity(1);
				print "</select><br/>";
				print '<input type="password" maxlength = "150" value="'.$answerB.'" name="answerQuestion_B" id="answer_Q2" placeholder="Enter Answer Here" required  /><br/><br/><br/>';
			}
			if($countSet > 2){
		 		print "<b>Question 3.</b><br/><br/>";
 				print '<select name="security_question_C" id="security_question_C" required><option value="">Select A Security Question</option>';
 				echo loadOnSecurity(1);
				print"</select><br/>";
		 		print'<input type="password" maxlength = "150" value="'.$answerC.'" name="answerQuestion_C" id="answer_Q3" placeholder="Enter Answer Here" required  /><br/><br/><br/>';
			}
			?>
 	</div><br/>
 	<input name="enter" class="btn" type="submit" value="Submit" /><br/>
</form>
 </body>
</html>