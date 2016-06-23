<form id="accRecoverForm">
<div class="container">
<h1 class="page-header"><?php echo lang('headerAccRecoverTitle'); ?></h1>
		<div class="form-group">
	    	<label for="inputsm"><?php echo lang('headerAccRecoverEmail'); ?></label>
	    	<input class="form-control input-sm" id="inputsm" name="email" type="text">
	  	</div>	  
		<input class="btn btn-primary" type="submit" id="note-Submit" value="<?php echo lang('gblSubmit'); ?>"  />
	</div>
	
</form>
<script>
$(document).ready(function(){

    $("#accRecoverForm").submit(function(e){     
        e.preventDefault(); // Prevent the refresh

        $.ajax({
    	    type: 'post',
    	    url: 'http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/accRecover/accRecoverProcess',
    	    data: $('#accRecoverForm').serialize(),
    	    success: function (msg) {
    	    	 console.log(msg); 
    	    	 msg = msg.replace(/\s/g, ''); 
    	    	 if (msg == "failed") {
    		    	 
            		 window.location.replace('<?php echo base_url().$currentLang; ?>/accRecover/');
    	    		 // No redirect if failed
            	} else if (msg == "success") {	
            		 window.location.replace('<?php echo base_url().$currentLang; ?>/accRecover/');
            	}
            }
        });
        
    });
});
</script>