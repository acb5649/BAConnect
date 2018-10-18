<?php
class procedure{
	public $username;
	private $password;
	public $accountNumber;
	public $sessionID;
	public $accountType;
	private $connectDB;
	
	function __construct($username=NULL, $password=NULL, $accountNumber=NULL,$sessionID=NULL, $accountType = -1, $connectDB=NULL){
		$this->username=$username;
		$this->password=$password;
		$this->accountNumber=$accountNumber;
		$this->sessionID=$sessionID;
		$this->accountType =$accountType;
		$this->connectDB=$connectDB;
	}
	public function Connect(){
		try {
			$this->connectDB = new PDO("mysql:host=localhost;dbname=youngjon_db", "youngjon", "youngjon");
			$isConnected = true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function disDB(){
		$this->connectDB=null;
	}
	public function verify($username,$password){
		
	}
	public function GetPrivelages(){
		if(isset($this->username)){
			
			
			return $this->accountType;
		}else{
			$this->accountType=null;
			return $this->accountType;
		}
	}
	public function GetSESSIONID(){
		if(isset($_GET['sd'])){
			$this->sessionID= $_GET['sd'];
		}
	}
	public function Share($loc){
		Header ("Location:".$loc.".php?sd=".$this->sessionID);
	}
}
?>