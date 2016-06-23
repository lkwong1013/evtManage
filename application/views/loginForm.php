<form id="loginForm">
	<div class="container">
		<h1 class="page-header"><?php echo lang('headerLoginTitle'); ?></h1>
		<div class="form-group">
	    	<label for="inputsm"><?php echo lang('headerFormUserName'); ?></label>
	    	<input class="form-control input-sm" id="inputsm" name="userName" type="text">
	  	</div>
	  	<div class="form-group">
	    	<label for="inputsm"><?php echo lang('headerFormPwd'); ?></label>
	    	<input class="form-control input-sm" id="inputsm" name="password" type="password">
	  	</div>
	  	<div class="form-group">
	  		<a href="<?php echo base_url().$currentLang."/accRecover"?>"><?php echo lang('loginFormForgetPwd'); ?></a>	
	  	</div>
		<input class="btn btn-primary" type="submit" id="note-Submit" value="Login" />
	</div>
	
</form>
<script>
$(document).ready(function(){

    $("#loginForm").submit(function(e){     
        e.preventDefault(); // Prevent the refresh
        loginValidation('<?php echo $currentLang; ?>');
    });
});

</script>
