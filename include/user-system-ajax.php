<?php 
session_start();



/***   A D M I N - S E C T I O N   *****/
add_action('wp_ajax_submit_admin_message', 'submit_admin_message');
add_action('wp_ajax_nopriv_submit_admin_message', 'submit_admin_message');

function submit_admin_message(){

	date_default_timezone_set("America/New_York");


	$receiver_id = $_POST['user_id'];
	$admin_message = $_POST['admin_message'];

	/*$err['error']  = 'Message has been submitted.';
	$err['status']  = false;
	$err['receiver_id']  = $receiver_id;
	$err['admin_message']  = $admin_message;
	

	echo json_encode($err);
	wp_die();*/

	if(isset($receiver_id) && !empty($receiver_id)) {
		$universal_user =get_userdata( $receiver_id );
		if($universal_user != false) {
	
			$file_date = date("Y-m-d h:i:s");
			global $wpdb;
			$tablename = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
			$staus_table_insert = $wpdb->insert( $tablename, array(
				'message' => $admin_message,
				'sender_id' => 1001, 
				'receiver_id' => $universal_user->ID,
				'created_at' => $file_date,
				'new_message_status' => 0, 
				'status' => 1
			),
	 		array( '%s', '%d', '%d', '%s', '%d','%d') );
			if($staus_table_insert != false){

				$err['error']  = 'Message has been submitted.';
				$err['status']  = false;
				echo json_encode($err);
				wp_die();
			}
		}
		else{
				$err['error']  = 'Message is not submitted.';
				$err['status']  = true;
				echo json_encode($err);
				wp_die();
		}
	}

}

/**   GET ADMIN MESSAGES **/
add_action('wp_ajax_get_admin_messages', 'get_admin_messages');
add_action('wp_ajax_nopriv_get_admin_messages', 'get_admin_messages');

function get_admin_messages(){
	$sender_id = $_POST['user_id'];

	global $wpdb;
	$table = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
	$query = "SELECT * FROM ".$table." WHERE sender_id =".$sender_id." OR sender_id = 1001 AND receiver_id=1001 OR receiver_id =".$sender_id;
	$result = $wpdb->get_results( $query . " ORDER BY id DESC" );
 	if(count($result) > 0){ 
 		$chat=''; 
		foreach( array_reverse($result) as $message ) {
			if( $message->sender_id == $sender_id ){ 
		 		$chat .= 
		 		'<div class="incoming_msg">
					<div class="incoming_msg_img"> 
						<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> 
					</div>
					<div class="received_msg">
						<div class="received_withd_msg">
							<p>'.$message->message.'</p>
						</div>
					</div>
				</div>'; ?>
			<?php } 
			if($message->sender_id == 1001 ){
				$chat .=
				'<div class="outgoing_msg">
					<div class="sent_msg">
						<p>'.$message->message.'</p>
					</div>
				</div>';
			} 
		}  

		$err['error']  = $chat;
		$err['status']  = false;
		echo json_encode($err);
		wp_die();

	}
	/*else{
		echo "No messages right now...!!!!";	
	} */

}


/***   U S E R - S E C T I O N   *****/
add_action('wp_ajax_submit_user_message', 'submit_user_message');
add_action('wp_ajax_nopriv_submit_user_message', 'submit_user_message');

function submit_user_message(){

	date_default_timezone_set("America/New_York");
	$receiver_id = $_POST['user_id'];
	$user_message = $_POST['user_message'];

	if(isset($receiver_id) && !empty($receiver_id)) {
		$universal_user =get_userdata( $receiver_id );
		if($universal_user != false) {
			$file_date = date("Y-m-d h:i:s");
			global $wpdb;
			$tablename = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
			$staus_table_insert = $wpdb->insert( $tablename, array(
				'message' => $user_message,
				'sender_id' => $universal_user->ID, 
				'receiver_id' => 1001,
				'created_at' => $file_date,
				'new_message_status' => 0, 
				'status' => 1
			),
	 		array( '%s', '%d', '%d', '%s', '%d','%d') );
			if($staus_table_insert != false){

				$err['error']  = 'Message has been submitted.';
				$err['status']  = false;
				echo json_encode($err);
				wp_die();
			}
		}
		else{
				$err['error']  = 'Message is not submitted.';
				$err['status']  = true;
				echo json_encode($err);
				wp_die();
		}
	}

}

/**   GET USER MESSAGES **/
add_action('wp_ajax_get_user_messages', 'get_user_messages');
add_action('wp_ajax_nopriv_get_user_messages', 'get_user_messages');

function get_user_messages(){
	$sender_id = $_POST['user_id'];

	global $wpdb;
	$table = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
	$query = "SELECT * FROM ".$table." WHERE sender_id =".$sender_id." OR sender_id = 1001 AND receiver_id=1001 OR receiver_id =".$sender_id;
	$result = $wpdb->get_results( $query . " ORDER BY id DESC" );
 	if(count($result) > 0){ 
 		$chat=''; 
		foreach( array_reverse($result) as $message ) {
			if( $message->sender_id == 1001 ){ 
		 		$chat .= 
		 		'<div class="incoming_msg">
					<div class="incoming_msg_img"> 
						<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> 
					</div>
					<div class="received_msg">
						<div class="received_withd_msg">
							<p>'.$message->message.'</p>
						</div>
					</div>
				</div>'; ?>
			<?php } 
			if($message->sender_id == $sender_id ){
				$chat .=
				'<div class="outgoing_msg">
					<div class="sent_msg">
						<p>'.$message->message.'</p>
					</div>
				</div>';
			} 
		}  

		$err['error']  = $chat;
		$err['status']  = false;
		echo json_encode($err);
		wp_die();

	}
	/*else{
		echo "No messages right now...!!!!";	
	} */

}


/**  ADMIN AJAX **/
add_action('wp_ajax_send_all', 'send_all');
add_action('wp_ajax_nopriv_send_all', 'send_all');
function send_all(){

	$admin_message = $_POST['all-message-admin'];
	$all_users = $_POST['all_users']; 
	if(!isset($admin_message) || empty($admin_message)){

		$err['error']  = 'Messgae is not set or empty.';
    	$err['status'] = false;
	}
	else if(!isset($all_users) || empty($all_users)){

		$err['error']  = 'Users are not set.';
    	$err['status'] = false;
	}
	else{


		date_default_timezone_set("America/New_York");
		$user_ids =  explode(",",$all_users);  // string to array
		if(count($user_ids) > 0){
			foreach ($user_ids as $key => $value) {

				$file_date = date("Y-m-d h:i:s");
				global $wpdb;
				$tablename = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
				$staus_table_insert = $wpdb->insert( $tablename, array(
					'message' => trim($admin_message),
					'sender_id' => 1001, 
					'receiver_id' => $value,
					'created_at' => $file_date,
					'new_message_status' => 0, 
					'status' => 1
				),
		 		array( '%s', '%d', '%d', '%s', '%d','%d') );
			}
			
			$err['error']  = 'Message is sent to all selected users.';
	    	$err['status'] = true;
	    	$err['redirect_url'] = home_url().'/wp-admin/admin.php?page=messages-users';
		}
		else{
			$err['error']  = 'Message is not sent to any user';
			$err['status']  = false;
		}
	}
	echo json_encode($err);
    wp_die();
}



add_action('wp_ajax_send_to_selected', 'send_to_selected');
add_action('wp_ajax_nopriv_send_to_selected', 'send_to_selected');
function send_to_selected(){

	$admin_message = $_POST['selected-message-admin'];
	$selected_users = $_POST['selected_users']; 
	if(!isset($admin_message) || empty($admin_message)){

		$err['error']  = 'Messgae is not set or empty.';
    	$err['status'] = false;
	}
	else if(!isset($selected_users) || empty($selected_users)){

		$err['error']  = 'Selected Users are not set.';
    	$err['status'] = false;
	}
	else{


		date_default_timezone_set("America/New_York");
		$user_ids =  explode(",",$selected_users);  // string to array
		if(count($user_ids) > 0){
			foreach ($user_ids as $key => $value) {

				$file_date = date("Y-m-d h:i:s");
				global $wpdb;
				$tablename = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
				$staus_table_insert = $wpdb->insert( $tablename, array(
					'message' => trim($admin_message),
					'sender_id' => 1001, 
					'receiver_id' => $value,
					'created_at' => $file_date,
					'new_message_status' => 0, 
					'status' => 1
				),
		 		array( '%s', '%d', '%d', '%s', '%d','%d') );
			}
			
			$err['error']  = 'Message is sent to all selected users.';
	    	$err['status'] = true;
	    	$err['redirect_url'] = home_url().'/wp-admin/admin.php?page=messages-users';
		}
		else{
			$err['error']  = 'Message is not sent to any user';
			$err['status']  = false;
		}
	}
 	echo json_encode($err);
    wp_die();
}

/**  Send files to selected users **/
add_action('wp_ajax_send_files_selected', 'send_files_selected');
add_action('wp_ajax_nopriv_send_files_selected', 'send_files_selected');

function send_files_selected(){
	
	//$_FILES['file']['error']
	$selected_users = $_POST['selected_users'];

	if(!isset($selected_users) || empty($selected_users)){

		$err['error']  = 'Selected Users are not set.';
    	$err['status'] = false;
	}
	else{

		date_default_timezone_set("America/New_York");
		$user_ids =  explode(",",$selected_users);  // string to array
		//$errors = 0;


		$countfiles        = count($_FILES['admin-system-files']['name']);
		$admin_files = $_FILES['admin-system-files'];
		$check_file_exists = $admin_files['name'][0];
		if (empty($check_file_exists)) {
			
			$err['error']  = 'Kindly Select a file';
    		$err['status'] = false;
			
			//$errors += 1;

			echo json_encode($err);
			wp_die();
		}
		if ($countfiles > 10) {

			$err['error']  = "Can't select files more than 10. You selected $countfiles  files.";
    		$err['status'] = false;

			//$errors += 1;
		
			echo json_encode($err);
			wp_die();
		}


		if(count($user_ids) > 0){

			

			//$users_ids = array();
			$uu = 0;
			$files_names = array();

			foreach ($user_ids as $key => $value) {
				$uu++;
				$current_user_id = get_current_user_id();  
				$user_id = trim($value);

				
				$folder_location = ABSPATH . 'wp-content/uploads/user_system_admin';
				$folder_created  = wp_mkdir_p($folder_location);
				if ($folder_created == true) {
					date_default_timezone_set("America/New_York");
					//if($uu == 1){
					for ($file_looping = 0; $file_looping < $countfiles; $file_looping++) {
						
						$original_name      = $admin_files['name'][$file_looping];
						$attach_unique_name = $user_id.'_'.time();
						$new_name           = $attach_unique_name.'_'.$original_name;
						// Now we'll move the temporary file to this plugin's uploads directory.
				
						$source             = $admin_files['tmp_name'][$file_looping];
						$destination        = $folder_location.'/'.$new_name;

						$file_status        = move_uploaded_file($source, $destination);
						if($file_status == true){
							$files_names[] = $destination;
						}

						if($uu > 1){
								copy($_SESSION['all_files_names'][$file_looping],$destination);
						
						}
						$path_parts         = pathinfo($original_name);
						//file extension
						$fileExtension      = $path_parts['extension'];
						$file_date          = date("Y-m-d h:i:s");
					
						global $wpdb;
						$tablename          = 'user_system_files'; //$wpdb->prefix.'univarsal_tax_files';
						$staus_table_insert = $wpdb->insert($tablename, array(
						'user_id' => $current_user_id,
						'file_name' => $new_name,
						'file_extension' => $fileExtension,
						'file_size' => $admin_files['size'][$file_looping],
					  	'file_location' => 'user_system_admin',
						'created_at' => $file_date,
						'status' => 1,
						'sender_id' => 1001,
						'receiver_id' => $user_id,
						'new_file_status' => 0
						), array(
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%d',
							'%d'
						));

					}

						$_SESSION['all_files_names'] = $files_names;
				}
				else{
					$err['error']  = "Error in creating folder";
					$err['status'] = false;
					echo json_encode($err);
					wp_die();
				}
			}
					
			$err['error']  = "File's Successfully sent to selected users ";
			$err['redirect_url'] = home_url().'/wp-admin/admin.php?page=files-users';
			$err['status'] = true;
		} 
		else {
			$err['error']  = 'File is not sent to any selected user';
			$err['status']  = false;
			
		}
				
	}
			
	// $err['error']  = "File's Successfully sent to selected users ";
	// $err['extra'] = 'working properly';
	// $err['status'] = false;

	echo json_encode($err);
	wp_die();
			
	
}


/**  Send files to selected users **/
add_action('wp_ajax_send_files_all', 'send_files_all');
add_action('wp_ajax_nopriv_send_files_all', 'send_files_all');

function send_files_all(){
	
	//$_FILES['file']['error']
	$selected_users = $_POST['all_users'];

	if(!isset($selected_users) || empty($selected_users)){

		$err['error']  = 'Users are not set.';
    	$err['status'] = false;
	}
	else{

		date_default_timezone_set("America/New_York");
		$user_ids =  explode(",",$selected_users);  // string to array
		//$errors = 0;


		$countfiles        = count($_FILES['admin-system-allfiles']['name']);
		$admin_files = $_FILES['admin-system-allfiles'];
		$check_file_exists = $admin_files['name'][0];
		if (empty($check_file_exists)) {
			
			$err['error']  = 'Kindly Select a file';
    		$err['status'] = false;
			
			//$errors += 1;

			echo json_encode($err);
			wp_die();
		}
		if ($countfiles > 10) {

			$err['error']  = "Can't select files more than 10. You selected $countfiles  files.";
    		$err['status'] = false;

			//$errors += 1;
		
			echo json_encode($err);
			wp_die();
		}


		if(count($user_ids) > 0){

			

			//$users_ids = array();
			$uu = 0;
			$files_names = array();

			foreach ($user_ids as $key => $value) {
				$uu++;
				$current_user_id = get_current_user_id();  
				$user_id = trim($value);

				
				$folder_location = ABSPATH . 'wp-content/uploads/user_system_admin';
				$folder_created  = wp_mkdir_p($folder_location);
				if ($folder_created == true) {
					date_default_timezone_set("America/New_York");
					//if($uu == 1){
					for ($file_looping = 0; $file_looping < $countfiles; $file_looping++) {
						
						$original_name      = $admin_files['name'][$file_looping];
						$attach_unique_name = $user_id.'_'.time();
						$new_name           = $attach_unique_name.'_'.$original_name;
						// Now we'll move the temporary file to this plugin's uploads directory.
				
						$source             = $admin_files['tmp_name'][$file_looping];
						$destination        = $folder_location.'/'.$new_name;

						$file_status        = move_uploaded_file($source, $destination);
						if($file_status == true){
							$files_names[] = $destination;
						}

						if($uu > 1){
								copy($_SESSION['all_files_names'][$file_looping],$destination);
						
						}
						$path_parts         = pathinfo($original_name);
						//file extension
						$fileExtension      = $path_parts['extension'];
						$file_date          = date("Y-m-d h:i:s");
					
						global $wpdb;
						$tablename          = 'user_system_files'; //$wpdb->prefix.'univarsal_tax_files';
						$staus_table_insert = $wpdb->insert($tablename, array(
						'user_id' => $current_user_id,
						'file_name' => $new_name,
						'file_extension' => $fileExtension,
						'file_size' => $admin_files['size'][$file_looping],
					  	'file_location' => 'user_system_admin',
						'created_at' => $file_date,
						'status' => 1,
						'sender_id' => 1001,
						'receiver_id' => $user_id,
						'new_file_status' => 0
						), array(
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%d',
							'%d'
						));

					}

						$_SESSION['all_files_names'] = $files_names;
				}
				else{
					$err['error']  = "Error in creating folder";
					$err['status'] = false;
					echo json_encode($err);
					wp_die();
				}
			}
					
			$err['error']  = "File's Successfully sent to selected users ";
			$err['redirect_url'] = home_url().'/wp-admin/admin.php?page=files-users';
			$err['status'] = true;
		} 
		else {
			$err['error']  = 'File is not sent to any selected user';
			$err['status']  = false;
			
		}
				
	}
			


	echo json_encode($err);
	wp_die();
			
	
}

	

	


	




