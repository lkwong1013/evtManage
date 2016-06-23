<div class="container">
<div class="row">
<div class="col-md-12">
<a href="<?php echo base_url().$currentLang."/threads/noteEditor/" ?>"><input class="btn btn-primary" type="button" value="New Thread" /></a>
<table class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>Notest Thread</th>
<th>Last Modify</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<!-- Handling out of range -->

<?php if ($page < $maxRow) { ?>
	<?php for ($i = 0; $i < count($notelist); ++$i) { ?>
                    <tr>
                        <td><?php echo ($page+$i+1); ?></td>
                        <td><a href="<?php echo base_url().$currentLang."/threads/noteContent/".$notelist[$i]->SEQ; ?>"><?php echo $notelist[$i]->NOTES_THREAD; ?></a></td>
                        <td><?php echo $notelist[$i]->LAST_MODIFY; ?></td>
                        <td><button type="button" class="btn btn-danger" onClick="delNote()">Delete</button></td>
                    </tr>
    <?php } ?>
<?php } else { ?>
                    <tr>
                        <td colspan='4'>No data found</td>                        
                    </tr>

<?php } ?>
                 
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
    </div>
    <div class="row">
     <div class="col-md-12 text-center">
            <?php if(isset($meesageData)) { print_r($messageData); } ?>
        </div>
    </div>
</div>
<script>
function delNote() {

	$.ajax({
	    type: 'post',
	    url: 'http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/threads/delNote',
	    data: $('form').serialize(),
	    success: function (msg) {
	    	 console.log(msg); 
	    		 // Redirect to home page
	    		 window.location.replace('http://samaxxw.com/evtManage/<?php echo $currentLang; ?>/threads/index/<?php echo $page; ?>');

	        }
	    }); 
	
}

</script>