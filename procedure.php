<?php
class procedure{
	public $username;
	private $password;
	public $accountNumber;
	public $sessionID;
	public $accountType;
	
	function __construct($username=NULL, $password=NULL, $accountNumber=NULL,$sessionID=NULL, $accountType = 0){
		$this->username=$username;
		$this->password=$password;
		$this->accountNumber=$accountNumber;
		$this->sessionID=$sessionID;
		$this->accountType =$accountType;
	}
	//checks for successful login
	public function verify($username,$password){
		$connectDB = mysql_connect("localhost", "estrayer", "estrayer","estrayer_db");
		mysql_select_db("estrayer_db");
		$query = mysql_query("SELECT * FROM Account WHERE username='$username'");
		$nRows = mysql_num_rows($query);
		if ($nRows!=0){
			//while loop
			while ($row = mysql_fetch_assoc($query)){
				if($password == $row['password']){
					$this->username = $row['username'];
					$this->accountNumber = $row['account_ID'];
					$this->accountType = $row['type'];
					ini_set('session.use_strict_mode',1);
					$this->sessionID = session_start();
					$row['session_ID'] = $this->sessionID;
				}
			}
		}else{
			//email
			$query= mysql_query("SELECT * FROM Information WHERE email_address='$username'");
			$nRows = mysql_num_rows($query);
			if ($nRows!=0){
			//while loop & change query location for next step...
			while ($row = mysql_fetch_assoc($query)){
				$this->accountNumber = $row['account_ID'];
				}
			}
			//now searching new table
			$query= mysql_query("SELECT * FROM Account WHERE account_ID='$this->accountID'");
			$nRows = mysql_num_rows($query);
			if ($nRows!=0){
			while ($row = mysql_fetch_assoc($query)){
				if($password == $row['password']){
					$this->username = $row['username'];
					$this->accountType = $row['type'];
					ini_set('session.use_strict_mode',1);
					$this->sessionID = session_start();
					$row['session_ID'] = $this->sessionID;
				}
			}
			}
		}
		$connectDB=null;
		
		
	}
	//checks for privelages
	public function GetPrivelages(){
		if(isset($this->username)){
			
			
			return $this->accountType;
		}else{
			$this->accountType=0;
			return $this->accountType;
		}
	}
	//update session id
	public function sendKeys(){
		ini_Set('session.use_strict_mode',1);
		$this->sessionID= session_start();
		$connectDB = mysql_connect("localhost", "estrayer", "estrayer","estrayer_db");
		mysql_select_db("estrayer_db");
		$query = mysql_query("UPDATE Account SET session_ID='$this->sessionID' WHERE account_ID='$this->accountNumber'");
		$connectDB = null;
	}
	//checks if your logged in
	public function GetAcc(){
		if(isset($_GET['sd'])){
			$this->sessionID= $_GET['sd'];
			$connectDB = mysql_connect("localhost", "estrayer", "estrayer","estrayer_db");
			mysql_select_db("estrayer_db");
			$query = mysql_query("SELECT * FROM Account WHERE session_ID='$this->sessionID'");
			$nRows = mysql_num_rows($query);
			if ($nRows!=0){
				//while loop
				while ($row = mysql_fetch_assoc($query)){
					$this->accountNumber = $row['account_ID'];
					$this->username= $row['username'];
					$this->accountType = $row['type'];
				}
			}
			$connectDB=null;
		}else{
			
		}	
	}
	public function logout(){
		$this->sessionID= null;
		$this->username=null;
		$this->password=null;
		$this->accountNumber=null;
		session_destroy();
		$connectDB = mysql_connect("localhost", "estrayer", "estrayer","estrayer_db");
		mysql_select_db("estrayer_db");
		$query = mysql_query("UPDATE Account SET session_ID='$this->sessionID' WHERE account_ID='$this->accountNumber'");
		$connectDB = null;
		$this->accountType =0;
	}
	public function Share($loc){
		Header ("Location:".$loc.".php?sd=".$this->sessionID);
	}
	//changes what kind of user it is
	public function changeUserType($user,$newType){
		//0 = reset
		//1 = user
		//2 = admin
		//3 = coordinator
		$connectDB = mysql_connect("localhost", "estrayer", "estrayer","estrayer_db");
		mysql_select_db("estrayer_db");
		$query = mysql_query("UPDATE Account SET type='$newType' WHERE username ='$user'");
		$connectDB = null;
	}
	//pairs the users
	public function pair($mentor, $mentee){
		
	}
	
	
}
?>
