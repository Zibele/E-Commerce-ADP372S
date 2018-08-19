<?php

class Store implements JsonSerializable{

private $DbConn=NULL;
private $storeID = "";
private $inventory = array();
private $shoppingCart = array();
private $query='';
private $totalItems;
function __construct(){

$this->setConnection();

}



public function getStoreInformation() {
	

$retval = FALSE;
if ($this->storeID != "") {

#$query=$this->DbConn->getConnection()->prepare("SELECT * FROM store_info WHERE storeID =:storeID");
$query=$this->DbConn->getConnection()->prepare("SELECT * FROM item WHERE CategoryID =:CategoryID");
#$query->execute(['storeID'=>$this->storeID]);
$query->execute(['CategoryID'=>$this->storeID]);
$retval=$query->fetch();

}
return $retval;
}




public function setStoreID($storeID) {

if ($this->storeID != $storeID) {
	
$this->storeID = $storeID;
#$query=$this->DbConn->getConnection()->prepare("SELECT * FROM inventory WHERE storeID =:storeID");
$query=$this->DbConn->getConnection()->prepare("SELECT * FROM item WHERE CategoryID =:CategoryID");
$query->execute(['CategoryID'=>$this->storeID]);

if ($query === FALSE) {
$this->storeID = "";
}
else {
$this->inventory = array();

$this->shoppingCart = array();
while (($Row = $query->fetch())) {
	#var_dump($Row);
#$this->inventory[$Row['productID']]= array();
$this->inventory[$Row['ItemID']]= array();

#$this->inventory[$Row['productID']]['name']= $Row['name'];

$this->inventory[$Row['ItemID']]['Name']= $Row['Name'];

$this->inventory[$Row['ItemID']]['Description']= $Row['Description'];
$this->inventory[$Row['ItemID']]['Price']= $Row['Price'];
$this->inventory[$Row['ItemID']]['ImagePath']=$Row['ImagePath'];

$this->shoppingCart[$Row['ItemID']]= 0;
}
}
}


}

/*public function getProductList() {
$retval = FALSE;
$subtotal = 0;
if (count($this->inventory) > 0) {
echo "<div class='container'> <table width='100%' class='table table-hover'>\n";
echo "<thead><tr><th>Product</th><th>Description</th>" ."<th>Price Each</th><th># in Cart</th>" ."<th>Total Price</th><th>&nbsp;</th></tr></thead>\n";
foreach ($this->inventory as $ID => $Info) {
echo "<tr><td>"."<img src='".htmlentities($Info['ImagePath'])."'alt='some text' >\n".htmlentities($Info['Name']). "</td>\n";
echo "<td>" .htmlentities($Info['Description']) ."</td>\n";
printf("<td class='currency'>R%.2f</td>\n", $Info['Price']);
echo "<td class='currency'>" .$this->shoppingCart[$ID] ."</td>\n";
printf("<td class='currency'>R%.2f</td>\n", $Info['Price'] *$this->shoppingCart[$ID]);

#echo "<td>  <a href='".$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID' onClick='cart(".'test'.")'>Add"."</a>\n";
echo "<td> <a href='".$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add \n";
#echo "<a href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add" ." Item</a></br>\n";
#echo "<button> <i class='fas fa-minus'> </i> </button>\n</td>";   
echo "<a  href='". $_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToRemove=$ID'>Remove " ."  </a></td>\n</div>";

$subtotal += ($Info['Price'] *$this->shoppingCart[$ID]);

}
#echo "<tr><td colspan='4'>Subtotal</td>\n";
#printf("<td class='currency'>$%.2f</td>\n",$subtotal);
#echo "<td>&nbsp;</td></tr>\n";
echo "<p>".$_SERVER['SCRIPT_NAME']."<p>";
echo "<td><a href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&EmptyCart=TRUE'>Empty " ." Cart</a></td></tr>\n";

echo "</table>";

echo "<p><a href='Checkout.php?PHPSESSID='".session_id()."&CheckOut=$this->storeID'>"."Check it out</a></p>\n";
//?PHPSESSID=".session_id()
$retval = TRUE;
}
return($retval);
}

*/


public function getProductList() {
$retval = FALSE;
$subtotal = 0;
if (count($this->inventory) > 0) {
if(isset($_POST['shoppingCart']))
	echo "<p>".$_POST['shoppingCart']."<p>";

$qty=(isset($_POST['shoppingCart'])) ? $_POST['shoppingCart'][$ID] : 0;
echo "<div class='container'> <table width='100%' class='table table-hover'>\n";
echo "<thead><tr><th>Product</th><th>Description</th>" ."<th>Price Each</th><th># in Cart</th>" ."<th>Total Price</th><th>&nbsp;</th></tr></thead>\n";
foreach ($this->inventory as $ID => $Info) {
echo "<tr><td>"."<img src='".htmlentities($Info['ImagePath'])."'alt='some text' >\n".htmlentities($Info['Name']). "</td>\n";
echo "<td>" .htmlentities($Info['Description']) ."</td>\n";
printf("<td class='currency'>R%.2f</td>\n", $Info['Price']);
echo "<td class='currency'>" .$this->shoppingCart[$ID]."</td>\n";
printf("<td class='currency'>R%.2f</td>\n", $Info['Price'] *$this->shoppingCart[$ID]);

#echo "<td>  <a href='".$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID' onClick='cart(".'test'.")'>Add"."</a>\n";
echo "<td> <a href='".$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add \n";
#echo "<a href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add" ." Item</a></br>\n";
#echo "<button> <i class='fas fa-minus'> </i> </button>\n</td>";   
echo "<a  href='". $_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToRemove=$ID'>Remove " ."  </a></td>\n</div>";

$subtotal += ($Info['Price'] *$this->shoppingCart[$ID]);

}
#echo "<tr><td colspan='4'>Subtotal</td>\n";
#printf("<td class='currency'>$%.2f</td>\n",$subtotal);
#echo "<td>&nbsp;</td></tr>\n";
echo "<p>".$_SERVER['SCRIPT_NAME']."<p>";
echo "<td><a href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&EmptyCart=TRUE'>Empty " ." Cart</a></td></tr>\n";

echo "</table>";

echo "<p><a href='Checkout.php?PHPSESSID='".session_id()."&CheckOut=$this->storeID'>"."Check it out</a></p>\n";
//?PHPSESSID=".session_id()
$retval = TRUE;
}
return($retval);
}







public function jsonSerialize(){
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

private function emptyCart() {
foreach ($this->shoppingCart as $key => $value)
$this->shoppingCart[$key] = 0;
}


public function checkout() {

$ProductsOrdered = 0;
$price=0;
foreach($this->shoppingCart as $productID =>$quantity) {
if ($quantity > 0) {
++$ProductsOrdered;
$price=$this->getPrice($productID);

echo "<p> itemID:".$productID.",quantity:".$quantity.",price:".$price."</p>";
$this->createAddress(session_id(),"Zonnebloem","Cape Town","Western Cape","8005");
$this->createCustomer(session_id(),"Yoda","Yoda@hotmail.com","Yoda",TRUE,session_id());
$this->createOrder(session_id(),session_id(),"CPUT","50",session_id());
$this->createOrderLine(session_id(),session_id(),$productID,$quantity,$price*$quantity);
}
}
echo "<p><strong>Your order has been " ."recorded.</strong></p>\n";
}

public function getTotalAddedItems(){
	$amount=0;
	foreach($this->shoppingCart as $key => $value){
		
		$amount+=$value;
		
	}
	return $amount;
}

private function setConnection(){
	include('DbConn.php');

$this->DbConn=$storeDB;
}

function __sleep(){
	
	return array("storeID","inventory","shoppingCart");
}

function __wakeup(){
	$this->setConnection();
}


private function getPrice($ID){
	$price=0;
	foreach($this->inventory as $inv => $value){
		if($inv==$ID)
			$price=$value['Price'];
	}
	return $price;
}


public function createCustomer($CustomerID,$Name,$Email,$Password,$isSubscribed,$AddressID){
	
$SQLstring = "INSERT INTO customer " . " (CustomerID,Name,Email,Password,isSubscribed,AddressID) " ." VALUES(:CustomerID,:Name,:Email,:Password,:isSubscribed,:AddressID)";
$QueryResult =$this->DbConn->getConnection()->prepare($SQLstring);
$QueryResult->execute(['CustomerID'=>$CustomerID,'Name'=>$Name,'Email'=>$Email,'Password'=>$Password,'isSubscribed'=>$isSubscribed,'AddressID'=>$AddressID]);

}	
	
public function createOrder($OrderID,$OrderNumber,$ShippingAddress,$DeliveryCost,$CustomerID){

$SQLstring = "INSERT INTO orders " . " (OrderID,OrderNumber, ShippingAddress,DeliveryCost,CustomerID) " ." VALUES(:OrderID,:OrderNumber,:ShippingAddress,:DeliveryCost,:CustomerID)";
$QueryResult =$this->DbConn->getConnection()->prepare($SQLstring);
$QueryResult->execute(['OrderID'=>$OrderID,'OrderNumber'=>$OrderNumber,'ShippingAddress'=>$ShippingAddress,'DeliveryCost'=>$DeliveryCost,'CustomerID'=>$CustomerID]);
	
	
}

public function createOrderLine($OrderLineID,$OrderID,$ItemID,$Quantity,$QPrice){
$SQLstring = "INSERT INTO orderline " . " (OrderlineID,OrderID, ItemID, Quantity,QPrice) " ." VALUES(:orderlineID,:orderID,:itemID,:quantity,:qprice)";
$QueryResult =$this->DbConn->getConnection()->prepare($SQLstring);
$QueryResult->execute(['orderlineID'=>$OrderLineID,'orderID'=>$OrderID,'itemID'=>$ItemID,'quantity' => $Quantity,'qprice'=>$QPrice]);

}

public function createAddress($AddressID,$Street,$City,$Province,$ZipCode){
	$SQLstring = "INSERT INTO address " . " (AddressID,Street,City,Province,ZipCode)" ." VALUES(:AddressID,:Street,:City,:Province,:ZipCode)";
    $QueryResult =$this->DbConn->getConnection()->prepare($SQLstring);
	$QueryResult->execute(['AddressID'=>$AddressID,'Street'=>$Street,'City'=>$City,'Province'=>$Province,'ZipCode'=>$ZipCode]);
	}
	
	
	
	
	
	
	
	
	
	
	
	
public function getInventory(){
	return $this->inventory;
}

public function getCart(){
	return $this->shoppingCart;
}
	
	
	
	
	
	
	
	
	
}





