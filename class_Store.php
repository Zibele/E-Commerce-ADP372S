<?php

class Store{

private $DbConn=NULL;
private $storeID = "";
private $inventory = array();
private $shoppingCart = array();
private $query='';
function __construct(){

include('DbConn.php');

$this->DbConn=$storeDB;

}



public function getStoreInformation() {
	
	
$retval = FALSE;
if ($this->storeID != "") {

$query=$this->DbConn->getPDO()->prepare("SELECT * FROM store_info WHERE storeID =:storeID");
$query->execute(['storeID'=>$this->storeID]);
$retval=$query->fetch();

}
return $retval;
}



public function setStoreID($storeID) {
	
if ($this->storeID != $storeID) {
	
$this->storeID = $storeID;
$query=$this->DbConn->getPDO()->prepare("SELECT * FROM inventory WHERE storeID =:storeID");
$query->execute(['storeID'=>$this->storeID]);

if ($query === FALSE) {
$this->storeID = "";
}
else {
$this->inventory = array();

$this->shoppingCart = array();
while (($Row = $query->fetch()!==FALSE)) {
	var_dump($Row);
$this->inventory[$Row['productID']]
= array();
$this->inventory[$Row['productID']]['name']= $Row['name'];
$this->inventory[$Row['productID']]['description']= $Row['description'];
$this->inventory[$Row['productID']]['price']= $Row['price'];
$this->shoppingCart[$Row['productID']]= 0;
}
}
}

echo "entered function";
}

public function getProductList() {
$retval = FALSE;
$subtotal = 0;
if (count($this->inventory) > 0) {
echo "<table width='100%'>\n";
echo "<tr><th>Product</th><th>Description</th>" ."<th>Price Each</th><th># in Cart</th>" ."<th>Total Price</th><th>&nbsp;</th></tr>\n";
foreach ($this->inventory as $ID => $Info) {
echo "<tr><td>" .htmlentities($Info['name']). "</td>\n";
echo "<td>" .htmlentities($Info['description']) ."</td>\n";
printf("<td class='currency'>$%.2f</td>\n", $Info['price']);
echo "<td class='currency'>" .$this->shoppingCart[$ID] ."</td>\n";
printf("<td class='currency'>$%.2f</td>\n", $Info['price'] *$this->shoppingCart[$ID]);

echo "<td><a href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add " ." Item</a></td>\n";
$subtotal += ($Info['price'] *$this->shoppingCart[$ID]);
}
echo "<tr><td colspan='4'>Subtotal</td>\n";
printf("<td class='currency'>$%.2f</td>\n",$subtotal);
echo "<td>&nbsp;</td></tr>\n";
echo "</table>";
$retval = TRUE;
}
return($retval);
}


}


