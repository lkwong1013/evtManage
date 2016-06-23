<div class="container">
	<div class="form-group col-xs-12">
		<div id='mySwipe' style='max-width:1000px;max-height:300px;margin:0 auto' class='swipe'>
			<div class='swipe-wrap'>
			<?php if (count($slideContent) > 0) { ?>
			<?php 	for ($i = 0 ; $i < count($slideContent); $i++) {?>
				<div><img src="<?php echo base_url()."notes/slides/".$slideContent[$i]->SLIDE_CONTENT; ?>" class="img-responsive" /></div>
			<?php 	}
				  } else {
				  	echo "<p>No slide found</p>";
				  }			
			?>				
			</div>
		</div>
	</div>
</div>
<script src='<?php echo base_url(); ?>lib/swipe/swipe.js'></script>
<script>

// pure JS
var elem = document.getElementById('mySwipe');
window.mySwipe = Swipe(elem, {
   startSlide: 0,		// Start at poistion
   auto: 3000			// Auto swap in xxxx millisecond
   //continuous: true
  // disableScroll: true,
  // stopPropagation: true,
  // callback: function(index, element) {},
  // transitionEnd: function(index, element) {}
});

$(document).ready(function() {
	$("#mySwipe").on("swiperight",function(){
	    mySwipe.prev();
	    console.log("swipe right");
	});  
	$("#mySwipe").on("swipeleft",function(){
	    mySwipe.next();
	    console.log("swipe left");
	});  
// 	$("#mySwipe").on("click",function(){
// 	    mySwipe.next();
// 	    console.log("swipe left");
// 	});  

 	$("#mySwipe").on("contextmenu",function(){
       return false;
    }); 
	$('#mySwipe').mousedown(function(event) {
	    switch (event.which) {
	        case 1:
	            //alert('Left Mouse button pressed.');
	            mySwipe.next();
	            console.log("swipe left");
	            break;
	        case 2:
	            //alert('Middle Mouse button pressed.');
	            break;
	        case 3:
	            //alert('Right Mouse button pressed.');
	            mySwipe.prev();
	            console.log("swipe right");
	            break;
	        default:
	            //alert('You have a strange Mouse!');
	        	 console.log("You have a strange Mouse!");
	    }
	});

	
});
// with jQuery
// window.mySwipe = $('#mySwipe').Swipe().data('Swipe');

</script>