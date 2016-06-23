<form id="registerForm">
	<div class="container">
		<div class="form-group">
		
			<label for="inputsm"><?php echo lang('registerLastName'); ?></label>
		    <input class="form-control input-sm" id="inputsm" name="lastName" type="text" value="<?php echo $registerFormData['lastName']; ?>" />
		
			<label for="inputsm"><?php echo lang('registerFirstName'); ?></label>
		    <input class="form-control input-sm" id="inputsm" name="firstName" type="text" value="<?php echo $registerFormData['firstName']; ?>" />
		
		    <label for="inputsm"><?php echo lang('registerUserName'); ?></label>
		    <input class="form-control input-sm" id="inputsm" name="userName" type="text" value="<?php echo $registerFormData['userName']; ?>" />
		    
		    <label for="inputsm"><?php echo lang('registerPwd'); ?></label>		    
		    <input class="form-control input-sm" id="inputsm" name="pwd" type="password" />
		    
		    <label for="inputsm"><?php echo lang('registerReTypePwd'); ?></label>		    
		    <input class="form-control input-sm" id="inputsm" name="confirmPwd" type="password" />
	
			<label for="inputsm"><?php echo lang('registerEmail'); ?></label>		    
		    <input class="form-control input-sm" id="inputsm" name="email" type="text" value="<?php echo $registerFormData['email']; ?>" />
			<br>
			<div class="input-group">
				<input class="btn btn-primary" type="button" id="note-Submit" value="<?php echo lang('registerSubmit'); ?>" onClick="submitRegister()" />
			</div>
		</div>
	</div>
</form>
<script>
function submitRegister() {
	
    $.ajax({
	    type: 'post',
	    url: 'http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/register/registerSubmit',
	    data: $('#registerForm').serialize(),
	    success: function (msg) {
	    	 console.log(msg); 
 	    	 msg = msg.replace(/\s/g, '');  	

 	    	 if (msg == "insert-failed") {
 	 	    	 // Remain register page
 	 	    	 window.location.replace('<?php echo base_url().$currentLang; ?>/register/'); 				 	 	    	 
 	    	 }  else if (msg == "insert-success") {
 	 	    	 // Redirect to welcome page (index for testing)
 	    		 window.location.replace('<?php echo base_url().$currentLang; ?>/welcome/');
 	    	 }
        }
    });
	
}
</script>