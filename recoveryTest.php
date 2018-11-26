<?php
	class Connection {
		public static function connect() {
			try {
				return new PDO("mysql:host=localhost;dbname=estrayer_db", "estrayer", "estrayer", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
			return null;
		}
	}
	function loadOnSecurity($accountID){
		$con = Connection::connect();
		$qestion = '';
		$query = "SELECT account_ID FROM RecoveryQuestions WHERE account_ID = "'.$accountID.'" ORDER BY question_Number ASC";
		$statement = $con->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$question .= '<option value="'.$row['question_Number'].'">'.$row['question'].'</option>';
		}
		if(!$question){
			$con = null;
			return header('Location: index.php');//skips security questions in none are set //*note change page
		}
		$con = null;
		return $question;
	}//loadOnSecurity
	
	function enableNewSecurity($accountID, $question, $answer){// var for new questions and answers
		$con = Connection::connect();
		$isFirst = 0;
		$stmt = $con->prepare("SELECT question_Number FROM RecoveryQuestions WHERE account_ID = '" . $accountID . "' ORDER BY question_Number ASC");
		$stmt->execute();
		$search = $statement->fetchAll();
		foreach($search as $row)
		{
			if($isFirst < $row['question_Number']){
				$isFirst = $row['question_Number'];
			}
		}
		if(!$isFirst){
			 $stmt = $con->prepare("insert into `RecoveryQuestions` (`account_ID`, `question_Number`, question, answer) values (?, ?, ?, ?)");
			 $stmt->bindValue(1, $accountID, PDO::PARAM_INT);
			 $stmt->bindValue(2, 0, PDO::PARAM_INT);
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
		$con = $null;
		return true;
	}//enableNewSecurity
?>