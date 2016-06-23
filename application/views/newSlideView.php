<?php echo form_open_multipart('slide/slideSubmit');?> 
		
<form id="newSlide">
	<div class="container">
		<div class="form-group col-xs-12">
	    	<label for="inputsm">Slide Name</label>
	    	<input class="form-control input-sm" id="inputsm" name="slideName" type="text">
	  	</div>
	  	<div class="form-group col-xs-12">
	  		<label for="inputsm">Slide Content (Image Only)</label>
			<input type="file" name="slideImage" />
		</div>
		<div class="form-group col-xs-12">
			<label for="inputsm">Visibility</label><br>
            <input type="checkbox" id="visible" name="visible" data-toggle="toggle" data-on="Yes" data-off="No">
        </div>
        <div class="form-group col-xs-6">
			<label for="inputsm">Order</label>
            <input class="form-control input-sm" id="order" name="order" type="number" placeholder="Only accept numberic format" />
        </div>
        <div class="form-group col-xs-12">
			<input class="btn btn-primary" type="submit" id="note-Submit" value="Save" /> 
			<a href="<?php echo base_url().$currentLang."/slide/slideList"; ?>"><input class="btn btn-warning" type="button" id="slide-Cancel" value="Cancel" onClick="" /></a>
		</div>
		<input type="hidden" name="noteOption" value="insert" />
	</div>
</form>

<script>
$(function () {
	$('#visible').bootstrapToggle('on'); // Set the toggle on while rendering
	
});
</script>

	
