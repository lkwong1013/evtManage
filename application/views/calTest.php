<!-- Main jumbotron for a primary marketing message or call to action -->
<script>

$(document).ready(function() {



// Testing for retriving schedule data
	$.ajax({
	    type: 'post',
	    url: 'http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/event/getSchedule',
	    success: function (msg) {
	    	 console.log(msg); 
	    	 
	        }
	    }); 
	

    // page is now ready, initialize the calendar...
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
    $('#calendar').fullCalendar({
        // put your options and callbacks here
        header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
		},
        editable: true,
        height:800,
        events: "http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/event/getSchedule"

    })

});
</script>

<div class="container">
<a href="<?php echo base_url().$currentLang."/event/calFormTest/" ?>"><input class="btn btn-primary" type="button" value="New Event" /></a> 
<a href="<?php echo base_url().$currentLang."/event/eventList/" ?>"><input class="btn btn-primary" type="button" value="Event List" /></a>
<div class="row"><p>&nbsp;</p></div>
<div id='calendar'></div>

</div>

