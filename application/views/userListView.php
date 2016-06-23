<div class="container">
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend class="legendStyle">
	        	<a data-toggle="collapse" data-target="#searchBox" href="#">Search Criteria</a>
			</legend>
			<form id="userSearchBox">
				<div class="row collapse" id="searchBox">
					<div class="col-md-2">
						<label class="labelStyle">User Name</label>
					</div>
					<div class="col-md-4">
						<input class="form-control input-sm" id="inputsm" name="userName" type="text">
					</div>
					<div class="col-md-2">
						<label class="labelStyle">Role</label>
					</div>
					<div class="col-md-4">						
						<select class="userRoleList" style="width:100%" name="userRole">
						<?php for ($i = 0; $i < count($userRoleList); $i++) { ?>
							<option value="<?php echo $userRoleList[$i]->CODE;?>"><?php echo $userRoleList[$i]->CODE_VALUE;?></option>
						<?php } ?>
						</select>
					</div>
					<div class="col-md-12" style="margin-bottom:5px;"></div>
					<div class="col-md-2">
					</div>
					<div class="col-md-4">
						<input class="btn btn-primary" type="submit" id="user-search" value="Submit" /> 
						<input class="btn btn-warning" type="button" id="user-search-clear" value="Clear" />
					</div>
	            </div>
	          </form>
		</fieldset>
	</div>
</div>
<div class="row" style="margin-bottom:5px;">
</div>
<div class="row">
<div class="col-md-12 table-responsive" style="min-height:500px;overflow-x:initial;index-z:10">
<table class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>User Name</th>
<th>Last Name</th>
<th>First Name</th>
<th>Email</th>
<th>Role</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<!-- Handling out of range -->

<?php if ($page < $maxRow && $maxRow > 0) { ?>
	<?php for ($i = 0; $i < count($userlist); ++$i) { ?>
                    <tr>
                        <td><?php echo ($page+$i+1); ?></td>
                        <td><a href="<?php echo base_url().$currentLang."/user/userInfo/".$userlist[$i]->ID; ?>"><?php echo $userlist[$i]->USER_NAME; ?></a></td>
                        <td><?php echo $userlist[$i]->LAST_NAME; ?></td>
                        <td><?php echo $userlist[$i]->FIRST_NAME; ?></td>
                        <td><?php echo $userlist[$i]->EMAIL; ?></td>
                        <td><?php echo $userlist[$i]->ROLE; ?></td>
                        <td><div class="btn-group">
								  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Action <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu">
								    <li><a href="">Edit</a></li>
								    <li><a href="">Change Password Request</a></li>
								    <li><a href="">Delete</a></li>
								  </ul>
								</div>			
						</td>
                    </tr>
    <?php } ?>
<?php } else { ?>
                    <tr>
                        <td colspan='7'>No data found</td>                        
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
$(document).ready(function() {
	  $(".userRoleList").select2({
		  minimumResultsForSearch: Infinity
	  });
});
</script>
<script>
$("#userSearchBox").submit(function(e){     
    e.preventDefault(); // Prevent the refresh
		$.ajax({
		    type: 'post',
		    url: '<?php echo base_url(); ?>en/user/filterUserList',
		    data: $('#userSearchBox').serialize(),
		    success: function (msg) {
		    	 console.log(msg); 
	 	    	 msg = msg.replace(/\s/g, ''); 
	 	    	 window.location.replace('<?php echo base_url().$currentLang; ?>/user/userList');
	        }
	    }); 
});
$("#user-search-clear").click(function(){   
	$.ajax({
	    type: 'post',
	    url: '<?php echo base_url(); ?>en/user/clearFilterUserList',	   
	    success: function (msg) {
	    	 console.log(msg); 
 	    	 msg = msg.replace(/\s/g, ''); 
 	    	 window.location.replace('<?php echo base_url().$currentLang; ?>/user/userList');
        }
    }); 
});

</script>
