<div class="container">
	<div class="col-md-12">
		<div id='mySwipe' style='max-width:1000px;margin:0 auto' class='swipe'>
		  <div class='swipe-wrap'>
		    <div><b>0</b></div>
		    <div><b>1</b></div>
		    <div><b>2</b></div>
		    <div><b>3</b></div>
		    <div><b>4</b></div>
		    <div><b>5</b></div>
		    <div><b>6</b></div>
		    <div><b>7</b></div>
		    <div><b>8</b></div>
		    <div><b>9</b></div>
		    <div><b>10</b></div>
		    <div><b>11</b></div>
		    <div><b>12</b></div>
		    <div><b>13</b></div>
		    <div><b>14</b></div>
		    <div><b>15</b></div>
		    <div><b>16</b></div>
		    <div><b>17</b></div>
		    <div><b>18</b></div>
		    <div><b>19</b></div>
		    <div><b>20</b></div>
		    <div><img src="http://samaxxw.com/evtManage/notes/images/eb7b922576a23f730080d4fa53abfdf11464415763.png"></div>
		  </div>
		</div>
	
	</div>
	<img src="http://samaxxw.com/evtManage/notes/images/eb7b922576a23f730080d4fa53abfdf11464415763.png" data-filename="8109436line0020.png" class="img-responsive">
</div>


<script src='<?php echo base_url(); ?>lib/swipe/swipe.js'></script>
<script>

// pure JS
var elem = document.getElementById('mySwipe');
window.mySwipe = Swipe(elem, {
   startSlide: 4,		// Start at poistion
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