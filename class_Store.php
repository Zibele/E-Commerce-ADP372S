<?php

class Store implements JsonSerializable{


private $storeID = "";
private $inventory = array();
private $shoppingCart = array();
private $query='';
private $totalItems;
private $lineCount=1;
private $orderCount=1;
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
 
 
 
 
function __sleep(){
	
	return array('dbUser','dbPass','dsn','storeID','inventory','shoppingCart');
	
}


function __wakeup(){
	
	$this->connect();
	
}













public function getStoreInformation() {
	

$retval = FALSE;
if ($this->storeID != "") {


$query=$this->connection->prepare("SELECT * FROM item WHERE CategoryID =:CategoryID");

$query->execute(['CategoryID'=>$this->storeID]);
$retval=$query->fetch();

}
return $retval;
}




public function setStoreID($storeID) {

if ($this->storeID != $storeID) {
	
$this->storeID = $storeID;

$query=$this->connection->prepare("SELECT * FROM item WHERE CategoryID =:CategoryID");
$query->execute(['CategoryID'=>$this->storeID]);

if ($query === FALSE) {
$this->storeID = "";
}
else {
$this->inventory = array();

$this->shoppingCart = array();
while (($Row = $query->fetch())) {

$this->inventory[$Row['ItemID']]= array();



$this->inventory[$Row['ItemID']]['Name']= $Row['Name'];

$this->inventory[$Row['ItemID']]['Description']= $Row['Description'];
$this->inventory[$Row['ItemID']]['Price']= $Row['Price'];
$this->inventory[$Row['ItemID']]['ImagePath']=$Row['ImagePath'];

$this->shoppingCart[$Row['ItemID']]= 0;
}
}
}


}




public function getProductList() {
$retval = FALSE;
$subtotal = 0;
if (count($this->inventory) > 0) {

echo "<div class='container'> <table width='100%' class='table table-hover'>\n";
echo "<thead><tr><th>Product</th><th>Description</th>" ."<th>Price Each</th><th># in Cart</th>" ."<th>Total Price</th><th>&nbsp;</th></tr></thead>\n";
foreach ($this->inventory as $ID => $Info) {
echo "<tr><td>"."<img src='".htmlentities($Info['ImagePath'])."'alt='' >\n".htmlentities($Info['Name']). "</td>\n";
echo "<td>" .htmlentities($Info['Description']) ."</td>\n";
printf("<td class='currency'>R%.2f</td>\n", $Info['Price']);
echo "<td class='currency'>" .$this->shoppingCart[$ID]."</td>\n";
printf("<td class='currency'>R%.2f</td>\n", $Info['Price'] *$this->shoppingCart[$ID]);


echo "<td> <a href='".$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add<br> \n";
  
echo "<a  href='". $_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToRemove=$ID'>Remove<br> " ."  </a></td>\n</div>";

$subtotal += ($Info['Price'] *$this->shoppingCart[$ID]);

}
echo "<tr><td colspan='4'>Subtotal</td>\n";
printf("<td class='currency'>R%.2f</td>\n",$subtotal);
echo "<td>&nbsp;</td></tr>\n";

echo "<td><a class='button' href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&EmptyCart=TRUE'>Empty " ." Cart</a></td></tr>\n";

echo "</table>";


$retval = TRUE;
}
return($retval);
}







public function jsonSerialize(){
	#this is equivalent to the php class magic functions.Returns data of what is to be converted into javascript.
	return[
	
	
	
	$this->inventory 
	 ,
	
	
	
	$this->shoppingCart
	,
	
	
	
	$this->getTotalAddedItems()
	
	
	
];
}



private function addItem() {
$ProdID = $_GET['ItemToAdd'];
if (array_key_exists($ProdID, $this->shoppingCart))
$this->shoppingCart[$ProdID] += 1;

}
private function removeItem() {
$ProdID = $_GET['ItemToRemove'];
if (array_key_exists($ProdID, $this->shoppingCart))
if ($this->shoppingCart[$ProdID]>0)
$this->shoppingCart[$ProdID] -= 1;
}


public function processUserInput() {
if (!empty($_GET['ItemToAdd']))
$this->addItem();
if (!empty($_GET['ItemToRemove']))
$this->removeItem();
if (!empty($_GET['EmptyCart']))
$this->emptyCart();
}

public function emptyCart() {
	
foreach ($this->shoppingCart as $key => $value)
$this->shoppingCart[$key] = 0;
}

public function processOrderKeys($val){
	#validates value and returns appropriate order key
    $currVal=0;
	$query=$this->connection->prepare("SELECT OrderID FROM orders");
	
	$query->execute();
	
    while(($Row=$query->fetch())){
		if($this->getLineVal($Row['OrderID'])>=$currVal)
		$currVal=$this->getLineVal($Row['OrderID']);
	}
	
		
	return $currVal>=$val ? $val=$currVal+1 : $val;
		
		
	}
	
public function checkout($Customer) {
#checks out with the passed customer id
$ProductsOrdered = 0;
$price=0;


$this->createOrder("ORDER-".$this->processOrderKeys($this->orderCount),$this->processOrderKeys($this->orderCount),"CPUT","50",$Customer);

foreach($this->shoppingCart as $productID =>$quantity) {
if ($quantity > 0) {
	
++$ProductsOrdered;
$price=$this->getPrice($productID);



$this->createOrderLine("ORDERLINE-".$this->processLineKeys($this->lineCount),"ORDER-".$this->orderCount,$productID,$quantity,$price*$quantity);


}
}

}



	
	
	
	


public function processLineKeys($value){
	#validates  the passed value and returns an appropriate key
	$currVal=0;
	$query=$this->connection->prepare("SELECT OrderLineID FROM orderline");
	
	$query->execute();
	
    while($Row=$query->fetch()){
		if($this->getLineVal($Row['OrderLineID'])>=$currVal){
		$currVal=$this->getLineVal($Row['OrderLineID']);
	}
	}
		
	return $currVal>=$value ? $value=$currVal+1 : $value;
		
 	
	
	
	
	
}

public function getLineVal($string){
	#returns the passed string with digits only.
	
	return preg_replace('/[^0-9]/', '', $string);
	
	
	
	
}



public function getTotalAddedItems(){
	#gets the total cost of the items in the inventory.
	$amount=0;
	foreach($this->shoppingCart as $key => $value){
		
		$amount+=$value;
		
	}
	return $amount;
}




private function getPrice($ID){
	#gets price of passed item-ID in inventory. 
	$price=0;
	foreach($this->inventory as $inv => $value){
		if($inv==$ID)
			$price=$value['Price'];
	}
	return $price;
}


public function createCustomer($CustomerID,$Name,$Email,$Password,$isSubscribed,$AddressID){
	#creates customer instant
$SQLstring = "INSERT INTO customer " . " (CustomerID,Name,Email,Password,isSubscribed,AddressID) " ." VALUES(:CustomerID,:Name,:Email,:Password,:isSubscribed,:AddressID)";
$QueryResult =$this->connection->prepare($SQLstring);
$QueryResult->execute(['CustomerID'=>$CustomerID,'Name'=>$Name,'Email'=>$Email,'Password'=>$Password,'isSubscribed'=>$isSubscribed,'AddressID'=>$AddressID]);

}	
	
public function createOrder($OrderID,$OrderNumber,$ShippingAddress,$DeliveryCost,$CustomerID){

$SQLstring = "INSERT INTO orders " . " (OrderID,OrderNumber, ShippingAddress,DeliveryCost,CustomerID) " ." VALUES(:OrderID,:OrderNumber,:ShippingAddress,:DeliveryCost,:CustomerID)";
$QueryResult =$this->connection->prepare($SQLstring);
$QueryResult->execute(['OrderID'=>$OrderID,'OrderNumber'=>$OrderNumber,'ShippingAddress'=>$ShippingAddress,'DeliveryCost'=>$DeliveryCost,'CustomerID'=>$CustomerID]);
	
	
}

public function createOrderLine($OrderLineID,$OrderID,$ItemID,$Quantity,$QPrice){
#creates Orderline instant.
$SQLstring = "INSERT INTO orderline " . " (OrderlineID,OrderID, ItemID, Quantity,QPrice) " ." VALUES(:orderlineID,:orderID,:itemID,:quantity,:qprice)";
$QueryResult =$this->connection->prepare($SQLstring);
$QueryResult->execute(['orderlineID'=>$OrderLineID,'orderID'=>$OrderID,'itemID'=>$ItemID,'quantity' => $Quantity,'qprice'=>$QPrice]);

}

public function createAddress($AddressID,$Street,$City,$Province,$ZipCode){
	#creates SQL address instant.
	$SQLstring = "INSERT INTO address " . " (AddressID,Street,City,Province,ZipCode)" ." VALUES(:AddressID,:Street,:City,:Province,:ZipCode)";
    $QueryResult =$this->connection->prepare($SQLstring);
	$QueryResult->execute(['AddressID'=>$AddressID,'Street'=>$Street,'City'=>$City,'Province'=>$Province,'ZipCode'=>$ZipCode]);
	}
	
	
	
	
	
	
	
	
	
	
	
	
public function getInventory(){
	#returns  current inventory.
	return $this->inventory;
}

public function getCart(){
	#returns current cart.
	return $this->shoppingCart;
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




	
	
	
	
	
	
	

}





