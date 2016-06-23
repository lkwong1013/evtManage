<div class="container">
	<div class="row">
		<div class="col-md-12">
			<a href="<?php echo base_url().$currentLang."/slide/newSlide"; ?>"><button type="button" class="btn btn-primary">New Slide</button></a>
		</div>
		<div class="col-md-12">
			<table class="table table-striped table-hover tablesorter" id="slideList">
				<thead>
					<tr>
						<th>#</th>
						<th>Slide Name</th>
						<th>Last Modify</th>
						<th>Visible</th>
						<th>Order</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if (count($slidelist) > 0) { ?>
					<?php for ($i = 0; $i < count($slidelist); ++$i) { ?>
	                    <tr>
	                        <td><?php echo ($i+1); ?></td>
	                        <td><a href="<?php echo base_url().$currentLang."/slide/editSlide/".$slidelist[$i]->SEQ; ?>"><?php echo $slidelist[$i]->SLIDE_NAME; ?></a></td>
	                        <td><?php echo $slidelist[$i]->MODIFY_DATE; ?></td>
	                        <td><?php echo $slidelist[$i]->VISIBLE; ?></td>
	                        <td><?php echo $slidelist[$i]->ORDER; ?></td>
	                        <td>
								<div class="btn-group">
								  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Action <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu">
								    <li><a href="<?php echo base_url().$currentLang."/slide/editSlide/".$slidelist[$i]->SEQ; ?>">Edit</a></li>
								    <li><a href="<?php echo base_url().$currentLang."/slide/delSlide/".$slidelist[$i]->SEQ; ?>">Delete</a></li>
								  </ul>
								</div>											
							</td>
	                    </tr>
	    			<?php } ?>
    			<?php } else {  ?>
    			
    			        <tr>
                        	<td colspan='5'>No data found</td>                        
                    	</tr>

				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-12">
			<span>*It is ordered by sequence</span>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() 
		    { 
		        $("#slideList").tablesorter({headers: {5: { sorter: false}}}); 
		    } 
		); 
</script>

