<!-- Hidden all summernote component -->
<style>
.note-editor.note-frame .note-editing-area .note-editable[contenteditable="false"] {
	background-color: #FFF;
}

.note-editor.note-frame {
	border: 0;
}

.note-resizebar {
	display: none;
}

.note-control-selection {
	display: none;
	border: 0;
}

.note-control-holder {
	display: none;
}

.note-control-selection-bg {
	background-color: #FFF
}

.note-control-selection-info {
	display: none;
}	

.note-control-se {
	display: none;
}
</style>

<div class="container">
	<div class="col-md-12">
		<h2><?php echo $noteContent[0]->NOTES_THREAD; ?></h2>
	</div>
	<div class="col-md-12">
		<textarea id="noteContent" style = "background-color: #FFF">
		<?php echo $noteContent[0]->NOTES_CONTENT; ?>
		</textarea>
	</div>
	<div class="col-md-12">
		<a href="<?php echo base_url().$currentLang."/threads/noteEditor/".$noteContent[0]->SEQ; ?>"><input class="btn btn-primary" type="button" value="Edit" /></a> 
		<?php //print_r($noteContent); ?>
	</div>
</div>

<script>
$(document).ready(function() {
	
		//$('#noteContent').destroy();
        $('#noteContent').prop('disabled', true);
         
        $('#noteContent').summernote({
        	disableDragAndDrop: true,
        	disabled: true,
            focus: true,
            styleWithSpan: false,
            toolbar: [],
            popover: {
                image: [],
                link: [],
                air: []
            }              
        });
        $('#noteContent').summernote('disable');


        $("body").click(function() {
        	$(".note-control-selection").removeAttr("style");
        });
        
});
</script>