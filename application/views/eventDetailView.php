<style>
	label {
		margin-bottom:0px;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<a href="<?php echo base_url().$currentLang."/event/calTest"; ?>"><input class="btn btn-warning" type="button" id="slide-Cancel" value="Back" onClick="" /></a>
		</div><div class="col-md-8"></div>
	</div>
	<div class="col-md-4">
		<label for="inputsm">Event Title</label>		
	</div>
	<div class="col-md-8">
		<?php echo $eventData[0]['EVENT_TITLE']; ?>
	</div>
	<div class="col-md-4">
		<label for="inputsm">活動名稱</label>
	</div>
	<div class="col-md-8">
		<?php echo $eventData[0]['EVENT_TITLE_CHT']; ?>
	</div>
	<div class="col-md-4">
		<label for="inputsm">Event Start</label>
	</div>
	<div class="col-md-8">
		<?php echo $eventData[0]['START']; ?>
	</div>
	<div class="col-md-4">
		<label for="inputsm">Event End</label>
	</div>
	<div class="col-md-8">
		<?php echo $eventData[0]['END']; ?>
	</div>

</div>
