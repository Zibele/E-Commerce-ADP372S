$(document).ready(function(){
    console.log("New function was called");

	
    console.log(phpArray);
	var inventory=phpArray[0];
    var shoppingCart=phpArray[1];
    var totalItems=phpArray[2];
    console.log(totalItems);
	
	 $.ajax({
        type:"POST",
        url:'cart_management.php',
	
	    async:true,
        data:{
          total_cart_items:totalItems
        },
        success:function(response) {
			
          document.getElementById("total_items").value=response;
        },
		
		      error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                  }
		
      });
	
	
	
    
});



	
	 function show_cart()
	 
    {
	
		
		
	  console.log("Showing cart");
      $.ajax({
      type:'post',
      url:'cart_management.php',
	  async:true,
      data:{
        showcart:"cart"
      },
      success:function(response) {
		  $(window).scrollTop(0);
        document.getElementById("mycart").innerHTML=response;
        $("#mycart").slideToggle();
	  
      }
     });

    }
	
	function checkOut()
    {

	 $.ajax({
      type:'post',
      url:'cart_management.php',
	  async:true,
      data:{
        Checkout:"checkout"
      },
      success:function(response) {
		 console.log(response);
        
        
	   $( "#dialog" ).dialog();
	   document.getElementById("dialog").innerHTML=response;
	   
	  $('#dialog').on('dialogclose', function(event) {
    
	       window.location=("http://localhost/zibele/Project/test.php");

	
        });
	  
      }
     });
	}
    