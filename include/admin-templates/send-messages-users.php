


<table id="example" class="table table-striped table-bordered" style="width:100%">
	<?php 
          
 	$args = array(
		'role' => 'user_system',
        'number' => -1
  	);
	$user_system_users = new WP_User_Query($args);
	$users_system_get = $user_system_users->get_results();
	$users_counts = count($users_system_get); // total users from universal tax user
	if($users_counts > 0){

	?>
    <thead>
    	<tr>
    		<th colspan="3">
    		<div style="padding: 0 0 0 500px;">	
				<button type="button" class="btn btn-primary modal-sendall" data-toggle="modal" data-target="#sendall">Send All</button>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-info modal-sendselected" data-toggle="modal" data-target="#sendtoselected">Send to Selected Users</button>
			

			</div>
    		</th>
    	</tr>
        <tr>
        	
            <th>Name</th>
            <th>Status</th>
         	<th>
        		Send Message 
        	</th>
   
        </tr>
    </thead>
    <tbody>
    <?php 
    	$all_users_ids = array();
    	foreach ($users_system_get as $user) {
    		$all_users_ids[] = $user->ID; 
    		$user_status = get_user_meta($user->ID, 'user_system_user_status', true);
			if (empty($user_status)) {
				$user_role_available = false;
				$ustatus     = 'table-danger';
			} 
			else {
				$user_role_available = true;
				if ($user_status == 2) {
					$ustatus     = 'table-success';
					$ustatus_msg = "Need Approval";
				} 
				elseif ($user_status == 1) {
					$ustatus     = 'table-active';
					$ustatus_msg = "Active";
				} 
				elseif ($user_status == 3) {
					$ustatus     = 'table-danger';
					$ustatus_msg = "Disabled";
				}
			}
    	?>
    		
  
         <tr class="<?php echo $ustatus;?>">
            <td><?php echo 	$user->data->user_login; ?></td>
            <td><?php echo ($user_role_available) ? $ustatus_msg : 'Not Found'; ?></td>
            <td>&nbsp;&nbsp;<input type="checkbox" class="single-user-id" name="user_id" value="<?php echo $user->ID; ?>" /></td>
       
        </tr>
 		<?php } ?>
    </tbody>

   
    <input type="hidden" id="selected-users" name="selected-users" value="0" />
    <input type="hidden" id="all-users" name="all-users" value="<?php echo implode(',',$all_users_ids); ?>" />
    <tfoot>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Send Message </th>
          
        </tr>
    </tfoot>
<?php }
else{ ?>

	<div class="alert alert-danger" role="alert">
		<h4 class="alert-heading">Oops!</h4>
		<p>No Users Found !</p>
		
	</div>
<?php } ?>
</table>



<!---  Admin Message Modal  -->
<!--  Send All -->
<div class="modal fade" id="sendall" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send All</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form id="send-all">
	      <div class="modal-body">
	        
	         
	          <div class="form-group">
	            <label for="message-text" class="col-form-label">Message:</label>
	            <textarea class="form-control" id="all-message-admin" name="all-message-admin"></textarea>
	            <small id="all-message-err" style="color: red;"></small>
	          </div>
	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Send message</button>
	      </div>
	      	<input type="hidden" name="all_users" value="0" />
	    	<input type="hidden" name="action" value="send_all" />
     </form>
    </div>
  </div>
</div>


<!--  Send to selected users -->
<div class="modal fade" id="sendtoselected" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send to selected users</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="send-to-selected">
	      <div class="modal-body">
	        
	         
	          <div class="form-group">
	            <label for="message-text" class="col-form-label">Message:</label>
	            <textarea class="form-control" id="selected-message-admin" name="selected-message-admin"></textarea>
	            <small id="selected-message-err" style="color: red;"></small>
	          </div>
	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Send message</button>
	      </div>
	      <input type="hidden" name="selected_users" value="0" />
	      <input type="hidden" name="action" value="send_to_selected" />
     </form>
    </div>
  </div>
</div>