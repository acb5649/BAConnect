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
	public function Connect(){
		try {
			$connectDB = new PDO("mysql:host=localhost;dbname=youngjon_db", "youngjon", "young");
			$isConnected = true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function verify($username,$password){
		$connectDB = mysql_connect("localhost", "youngjon", "young","youngjon_db");
		mysql_select_db("youngjon_db");
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
		}
		$connectDB=null;
		
		
	}
	public function GetPrivelages(){
		if(isset($this->username)){
			
			
			return $this->accountType;
		}else{
			$this->accountType=0;
			return $this->accountType;
		}
	}
	public function GetSESSIONID(){
		if(isset($_GET['sd'])){
			$this->sessionID= $_GET['sd'];
			$connectDB = mysql_connect("localhost", "youngjon", "young","youngjon_db");
			mysql_select_db("youngjon_db");
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
			print "<script>document.getElementById('loginModal').style.display='block';</script>";
		}	
	}
	public function Share($loc){
		Header ("Location:".$loc.".php?sd=".$this->sessionID);
	}
}
?>