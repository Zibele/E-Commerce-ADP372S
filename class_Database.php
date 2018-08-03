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
private $pdo=NULL;
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
	
	try{
	$this->pdo=new PDO($this->dsn,$this->dbUser,$this->dbPass,$this->opt);
	}
	catch(PDOException $e){
		
		echo "<p>Connection error:".$e->getMessage()."</p>";
		
	}
	
}


function getPDO(){
	/*returns the PDO connection */
	
	return $this->pdo;
}


function getConnInfo(){
	/*Returns a string of the database connection information. */
	
	return "Host:".$this->dbHost.
	       "\nPort:".$this->dbPort.
		   "\nDatabase Username:".$this->DbUser.
		   "\nDatabase Password:".$this->DbPass.
		   "\nDatabase Name:".$this->dbName;
		   
}



/*function __construct($dbHost,$dbUser,$dbPass,$dbName){
	
	This constructor(Database Host,Database name,Database Username,Database Password,Database Name),
	
	sets up the database(MySQL) connection using thePDO approach.
	
	Where depending on your case you do NOT have to EXPLICITLY state your PORT
	
	$this->dbUser=$dbUser;
	$this->dbPass=$dbPass;
	$this->dsn="mysql:host=".$this->dbHost=$dbHost.";dbname=".$this->dbName=$dbName.";charset=".$this->CHAR_SET;	
	try{
		$this->pdo=new PDO($this->dsn,$this->dbUser,$this->dbPass,$this->OPT);
}catch(PDOException $e){
	
	echo "<p> Connection error:".$e->getMessage()."</p>";
}

} */

}