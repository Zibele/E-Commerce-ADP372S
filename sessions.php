<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (class_exists("Store")) {
	
if (isset($_SESSION['currentStore'])){
	
$Store = unserialize($_SESSION['currentStore']);


}
else {
	
$Store = new Store("localhost","3308","root","","ecommerce");
$_SESSION['currentStore']=serialize($Store);
}

if($Store==NULL){
	echo "<p> Object is null </p>";
	
}
}
else {
$ErrorMsgs[] = "The OnlineStore class is not
available!";
$Store = NULL;
}




?>