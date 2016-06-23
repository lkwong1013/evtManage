    
<form id="noteTest">
	<div class="container">
		<div class="form-group">
	    	<label for="inputsm">Notes Thread</label>
	    	<input class="form-control input-sm" id="inputsm" name="noteThread" type="text" value="<?php echo $noteContent[0]->NOTES_THREAD; ?>">
	  	</div>
		<textarea id="noteContent" name="noteContent" style="display: inline-block;"><?php echo $noteContent[0]->NOTES_CONTENT; ?></textarea>
		<input type="hidden" name="noteSeq" value="<?php echo $noteContent[0]->SEQ; ?>" />
		<input type="hidden" name="noteOption" value="update" />
		<input class="btn btn-primary" type="button" id="note-Submit" value="Save" onClick="submitNote()" />
	</div>
	
</form>
<script>
$(document).ready(function() {
	
		$('#noteContent').summernote({

      	});
	  
});
</script>
<script>
function submitNote() {

    $.ajax({
	    type: 'post',
	    url: 'http://samaxxw.com/evtManage/en/threads/noteSubmit',
	    data: $('#noteTest').serialize(),
	    success: function (msg) {
	    	 console.log(msg); 
	    	 msg = msg.replace(/\s/g, ''); 
	    	 if (msg == "insert-failed") {
		    	 
	    		 testBox("danger", '<?php echo lang('boxMsgUpdateNoteFail'); ?>',2000);
	    		 // No redirect if failed
        	} else if (msg == "thread-missing") {
            	msgBox("danger", '<?php echo lang('boxMsgNoteThreadMiss'); ?>',2000);
        	} else if (msg == "insert-success") {	
        		 window.location.replace('<?php echo base_url().$currentLang; ?>/threads/noteEditor/<?php echo $noteContent[0]->SEQ; ?>');
        	}
        }
    }); 
	
}
</script>