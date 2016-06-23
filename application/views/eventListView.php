<div class="container">
<div class="row">
<div class="col-md-12 table-responsive" style="min-height:1000px;overflow-x:initial;index-z:10">
<table class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>Event Title</th>
<th>活動名稱</th>
<th>Start</th>
<th>End</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<!-- Handling out of range -->

<?php if ($page < $maxRow) { ?>
	<?php for ($i = 0; $i < count($eventList); ++$i) { ?>
                    <tr>
                        <td><?php echo ($page+$i+1); ?></td>
                        <td><a href="<?php echo base_url().$currentLang."/event/eventDetail/".$eventList[$i]['EVENT_ID']; ?>"><?php echo $eventList[$i]['EVENT_TITLE']; ?></a></td>
                        <td><?php echo $eventList[$i]['EVENT_TITLE_CHT']; ?></td>
                        <td><?php echo $eventList[$i]['START']; ?></td>
                        <td><?php echo $eventList[$i]['END']; ?></td>                        
                        <td><div class="btn-group">
								  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Action <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu">
								    <li><a href="">Edit</a></li>
								    <li><a href="">Delete</a></li>
								  </ul>
								</div>			
						</td>
                    </tr>
    <?php } ?>
<?php } else { ?>
                    <tr>
                        <td colspan='6'>No data found</td>                        
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
