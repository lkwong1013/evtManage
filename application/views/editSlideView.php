<?php echo form_open_multipart('slide/editSlideSubmit');?> 
		
<form id="editSlide">
	<div class="container">
		<div class="form-group col-xs-12">
	    	<label for="inputsm">Slide Name</label>
	    	<input class="form-control input-sm" id="inputsm" name="slideName" type="text" value="<?php echo $slideContent[0]->SLIDE_NAME; ?>">
	  	</div>
	  	<div class="form-group col-xs-12">
	  		<label for="inputsm">Current Slide Content</label><br>
	  		<img src="<?php echo base_url()."notes/slides/".$slideContent[0]->SLIDE_CONTENT; ?>" />
  		</div>
	  	<div class="form-group col-xs-12">
	  		<label for="inputsm">Upload Slide Content (Image Only)</label>
			<input type="file" name="slideImage" />
		</div>
		
		<div class="form-group col-xs-12">
			<label for="inputsm">Visibility</label><br>
            <input type="checkbox" id="visible" name="visible" data-toggle="toggle" data-on="Yes" data-off="No">
        </div>
		 <div class="form-group col-xs-6">
			<label for="inputsm">Order</label>
            <input class="form-control input-sm" id="order" name="order" type="number" value="<?php echo $slideContent[0]->ORDER; ?>" placeholder="Only accept numberic format" />
        </div>
        <div class="form-group col-xs-12">
			<input class="btn btn-primary" type="submit" id="slide-Submit" value="Save" onClick="" /> 
			<a href="<?php echo base_url().$currentLang."/slide/slideList"; ?>"><input class="btn btn-warning" type="button" id="slide-Cancel" value="Cancel" onClick="" /></a>
		</div>
		<input type="hidden" name="seq" value="<?php echo $slideContent[0]->SEQ; ?>" />
		<input type="hidden" name="content" value="<?php echo $slideContent[0]->SLIDE_CONTENT; ?>" />
	</div>
</form>
<script>
$(function () {
	<?php if (strcmp($slideContent[0]->VISIBLE, "T") == 0) { ?>
	$('#visible').bootstrapToggle('on'); // Set the toggle on while rendering
<?php } else { ?>
	$('#visible').bootstrapToggle('off'); // Set the toggle on while rendering
<?php } ?>
});

</script>
	
