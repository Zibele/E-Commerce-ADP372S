<?php

include('sessions.php');
require_once ('class_Store.php');
require_once('cart_management.php');



//unset($_SESSION['currentStore']);



$storeID = "STATUE";
$storeInfo = array();

$Store->setStoreID($storeID);

$storeInfo = $Store->getStoreInformation();
$Store->processUserInput();


?>



<!DOCTYPE HTML>
<Head>


<Title> Test Connection type </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="jquery-3.3.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">


<link rel="stylesheet" type="text/css" href="cart_style.css">

</Head>

<body>


<p id="cart_button" onclick="show_cart();">

  <img src="shop.png">
  <input type="button" id="total_items" value="">
</p>
<div id="mycart">
</div>

<?php
if ($Store !== NULL)
$_SESSION['currentStore'] = serialize($Store);
?>

</body>

</html>


<?php
$Store->getProductList();

$_SESSION['currentStore'] = serialize($Store);


?>
<script type="text/javascript"  >

var phpArray = <?php echo strip_tags(json_encode($Store,JSON_PRETTY_PRINT)); ?>;


</script>
 
<script type="text/javascript" src='cart_react.js' >
</script>

