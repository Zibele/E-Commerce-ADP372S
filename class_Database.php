<?php 

/*

AUTHOR: Zibele Nqunqa

CREDITS:phpdelusions.net/pdo;
*/


class DatabaseConn{

private $dbHost='';
private $dbPort='';
private $dbName='';
private $dbUser='';
private $dbPass='';
private $dsn='';
private $connection=NULL;
private $charset='utf8mb4';
private $opt=[

   PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES   => false,

 ];



function __construct($dbHost,$dbPort,$dbUser,$dbPass,$dbName){
	/*
	This constructor(Database host,Database Port,Database username,Database password,Database Name) sets up the database(MySQL) connection using the
	PDO approach.
	
	Where YOU have to EXPLICITLY state your PORT*/
	
	
	$this->dbUser=$dbUser;
	$this->dbPass=$dbPass;
	$this->dsn="mysql:host=".$this->dbHost=$dbHost.";dbname=".$this->dbName=$dbName.";port=".$this->dbPort=$dbPort.";charset=".$this->charset;
	$this->connect();

	
}

private function connect(){
	try{
	$this->connection=new PDO($this->dsn,$this->dbUser,$this->dbPass,$this->opt);
	}
	catch(PDOException $e){
		
		echo "<p>Connection error:".$e->getMessage()."</p>";
		
	}
}

function getConnection(){
	/*returns the PDO connection */
	
	return $this->connection;
}


function getConnInfo(){
	/*Returns a string of the database connection information. */
	
	return "Host:".$this->dbHost.
	       "\nPort:".$this->dbPort.
		   "\nDatabase Username:".$this->DbUser.
		   "\nDatabase Password:".$this->DbPass.
		   "\nDatabase Name:".$this->dbName;
		   
}



function __sleep(){
	
	return array('dbUser','dbPass','dsn');
	
}


function __wakeup(){
	
	$this->connect();
	
}
}