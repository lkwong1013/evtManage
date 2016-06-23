


<!-- <div class="container-fluid">

<div class="main">

	<div class="row">
		<h1>Hi Samantha Lau, Will you marry me?</h1>
	</div>
	<div class="row">
		<input class="btn btn-primary" type="submit" id="note-Submit" value="Yes"  style="width:900px;height:500px;font-size: 200px" />
	</div>
</div>
</div>
<script>
function btn() {
	
}
</script> -->

<div class="container">
<form id="accRecoverForm">
<div class="container">
<h1 class="page-header"><?php echo lang('headerAccRecoverResetPwd'); ?></h1>
		<div class="form-group">
	    	<label for="inputsm"><?php echo lang('headerAccRecoverPwd'); ?></label>
	    	<input class="form-control input-sm" id="inputsm" name="pwd" type="password">
	  	</div>
	  	<div class="form-group">
	    	<label for="inputsm"><?php echo lang('headerAccRecoverConfirmPwd'); ?></label>
	    	<input class="form-control input-sm" id="inputsm" name="confirmPwd" type="password">
	  	</div>	  	  
	  		<input class="form-control input-sm" id="inputsm" name="token" type="hidden" value="<?php echo $recoveryToken; ?>">
		<input class="btn btn-primary" type="submit" id="note-Submit" value="<?php echo lang('gblSubmit'); ?>"  />
	</div>
	
</form>
</div>

<script>
$(document).ready(function(){

    $("#accRecoverForm").submit(function(e){     
        e.preventDefault(); // Prevent the refresh

        $.ajax({
    	    type: 'post',
    	    url: 'http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/accRecover/resetPwd',
    	    data: $('#accRecoverForm').serialize(),
    	    success: function (msg) {
    	    	 console.log(msg); 
    	    	 msg = msg.replace(/\s/g, ''); 
    	    	 if (msg == "failed") {
    		    	 
            		 window.location.replace('<?php echo base_url().$currentLang; ?>/accRecover/doRecover/<?php echo $recoveryToken; ?>');
    	    		 // No redirect if failed
            	} else if (msg == "success") {	
            		 window.location.replace('<?php echo base_url().$currentLang; ?>/welcome/testFront');
            	}
            }
        });
        
    });
});
</script>