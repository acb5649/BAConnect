<?php
require_once "database.php";

require_once "session.php";
function enableNewSecurity($accountID, $question, $answer){// var for new questions and answers
        $con = Connection::connect();
        $isFirst = 0;
        $stmt = $con->prepare("SELECT question FROM `RecoveryQuestionsList` WHERE question = ?");
        $stmt->bindValue(1, $question, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['question'] != null){
            $con = null;
		    return false;
        }
        $stmt = $con->prepare("SELECT answer FROM `RecoveryQuestions` WHERE account_ID = ? AND question = ? ");
        $stmt->bindValue(1, $accountID, PDO::PARAM_INT);
		$stmt->bindValue(2, $question, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['answer'] != null){
            $con = null;
		    return false;
        }
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
function loadSecurityOptions(){
		$con = Connection::connect();
		$set=0;
		$question = '';
		$query = "SELECT DISTINCT * FROM RecoveryQuestionsList WHERE active = '1' ORDER BY questions ASC";
		$statement = $con->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$question .= '<option value='.$row['questions'].'>'.$row['questions'].'</option>';
			$set= 1;
		}
		$con = null;
		return $question;
}//loadOnSecurity
$answer="";
$question="";
$msg = "";
if (isset($_POST['enter'])){
    if(isset($_POST['answerQuestion'])){
        $question= trim($_POST['set_question']);
        $answer = trim($_POST['answerQuestion']);
        $response=enableNewSecurity(4, $question, $answer);//change 4 to account id
        if(!$response){
            $msg="<span style='color:red'><br/>Error, overlapping question!<br/></span>";
        }else{
            $msg="<span style='color:green'><br/>Success!<br/></span>";
        }
    }
}
print $msg;
$msg = "";
?>
<html>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Update My Security Questions</title>
	</head>
    <body>
   	    <center>
 	        <form action="updateQuestions.php" method="post">
                <div>
                <?php 
                    print '<select name="set_question" id="set_question" required><option value="">Select A Security Question</option>';
                    echo loadSecurityOptions();
                    print"</select><br/>"; 
                    print'<input type="password" maxlength = "150" value="'.$answer.'" name="answerQuestion" id="answer_Q" placeholder="Enter Answer Here" required  /><br/><br/><br/>';
                ?>
                </div><br/>
                <input name="enter" class="btn" type="submit" value="Submit" /><br/>
            </form>
        </center>
    </body>