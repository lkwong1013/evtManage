<form id="calForm">
	<div class="container">
		<div class="form-group">
		    <label for="inputsm">Event Title</label>
		    <input class="form-control input-sm" id="inputsm" name="eventTitle" type="text" value="<?php echo $calFormData['eventTitle']; ?>">
		    
		    <label for="inputsm">活動名稱</label>		    
		    <input class="form-control input-sm" id="inputsm" name="eventTitleTc" type="text" value="<?php echo $calFormData['eventTitleTc']; ?>">
		    
		    <label for="inputsm">Event Start</label>
		    <div class='input-group date' id='datetimepicker1'>
            	<input type='text' class="form-control" name="eventStart" value="<?php echo $calFormData['eventStart']; ?>" />
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <label for="inputsm">Event End</label>
		    <div class='input-group date' id='datetimepicker2'>
            	<input type='text' class="form-control" name="eventEnd" value="<?php echo $calFormData['eventEnd']; ?>" />
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <label for="inputsm">Full day event?</label>
            <div class="input-group">
            	<input type="checkbox" id="fullDayEvent" name="fullDayEvent" data-toggle="toggle" data-on="Yes" data-off="No"> *Yes will ignore the time
            </div>
            </br>            
       		<input class="btn btn-primary" type="button" id="note-Submit" value="Save" onClick="submitEvent()" /> 
            <a href="<?php echo base_url().$currentLang."/event/calTest/" ?>"><input class="btn btn-warning" type="button" value="Cancel" /></a>
            
	  	</div>
	</div>
</form>

<script>
$(function () {
	$('#datetimepicker1').datetimepicker({
		format: 'Y-M-D HH:mm:ss'
		});
    $('#datetimepicker2').datetimepicker({
		format: 'Y-M-D HH:mm:ss'
	});
    <?php if (isset($calFormData['fullDayEvent']) && $calFormData['fullDayEvent'] == true) { ?>
    	$('#fullDayEvent').bootstrapToggle('on'); // Set the toggle on while rendering
    <?php } ?>
});
</script>
<script>
function submitEvent() {
	
    $.ajax({
	    type: 'post',
	    url: 'http://samaxxw.com/evtManage/en/event/calSubmitTest',
	    data: $('#calForm').serialize(),
	    success: function (msg) {
	    	 console.log(msg); 
 	    	 msg = msg.replace(/\s/g, ''); 
 	    	 window.location.replace('<?php echo base_url().$currentLang; ?>/welcome/calFormTest/');
        }
    }); 
}
</script>