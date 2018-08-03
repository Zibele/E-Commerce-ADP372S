<?php
session_start();
require_once('class_Store.php');


$storeID = "COFFEE";
$storeInfo = array();
unset($_SESSION['currentStore']);

if (class_exists("Store")) {
	
if (isset($_SESSION['currentStore'])){
	echo "<p> currentStore is set</p>";
$Store = unserialize($_SESSION['currentStore']);


}
else {
	
$Store = new Store();
}

$Store->setStoreID($storeID);

$storeInfo = $Store->getStoreInformation();

}

else {
$ErrorMsgs[] = "The OnlineStore class is not
available!";
$Store = NULL;
}


?>



<!DOCTYPE HTML>
<Head>
<Title> Test Connection type </title>
</Head>

<Body>

<?php
if ($Store !== NULL)
echo "<p>Successfully instantiated an object of " .
" the OnlineStore class.</p>\n";





?>

</body>

</html>
<?php
$Store->getProductList();
$_SESSION['currentStore'] = serialize($Store);
?>