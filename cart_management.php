<?php
include('sessions.php');
require_once('class_Store.php');

if(isset($_SESSION['currentStore']))
$StoreManage=unserialize($_SESSION['currentStore']);
 

 
 
 
 
 
if(isset($_POST['total_cart_items'])){
$totItems=$_POST['total_cart_items'];

echo $totItems;
exit();
}




if(isset($_POST['showcart']))
  {
	
	
	$inventoryManage=$StoreManage->getInventory();
	$shoppingCartManage=$StoreManage->getCart();
	$grandTotal=0;
	

    foreach($inventoryManage as $key => $value)
    {
      
	
	  if($shoppingCartManage[$key]>0){
		  $itemTotal=$shoppingCartManage[$key]*$value['Price'];
		  $grandTotal+=$itemTotal;
	  echo "<div class='cart_items'>";
      echo "<img src='".$value['ImagePath']."'>";
      echo "<p>Name:".$value['Name']."</p>";
      echo "<p>Price:R".$value['Price']."</p>";
	  echo "<p>Quantity:".$shoppingCartManage[$key]."</p>";
	  echo "<p> Total:R".$itemTotal."</p>";
	  echo "</div>";
	  }
     
    }
	
	if($StoreManage->getTotalAddedItems()==0){
		echo "<div class='cart_items'>";
		echo "<p> Your cart is empty </p>";
		echo "</div>";
	}
	else{
		
		
		
		
		
		echo "<div class='cart items'>";
		echo "<div id='GrandTotal'> Grand Total=R".$grandTotal."</div>";
		echo "<div class='checkout' id='checkout'></div>";	
		echo '<script type="text/javascript">
		
		
			function checkOut()
          {
	      console.log("Checking out..new");	
	      $.ajax({
           type:"post",
          url:"cart_management.php",
	      async:true,
         data:{
           Checkout:"checkout"
         },
		 

        success:function(response) {
		 
    
        $( "#dialog" ).dialog();
	
    
        }
       });
       	}
		</script>';
		
		 echo "<button type='type' ' class='button' data-toggle='modal' data-target='#myModal' onClick='checkOut();'> Check Out</button>";
	      echo '<div id="dialog" title="Check Out"> </div>';

   exit();	
  }
  }

  if(isset($_POST['Checkout'])){ 
		
		$StoreManage->checkout("CUST001");
	    $StoreManage->emptyCart();
		 echo'<p>You order was successful</p>';
		
	
		
		$_SESSION['currentStore']=serialize($StoreManage);
		
		exit();
  }













?>