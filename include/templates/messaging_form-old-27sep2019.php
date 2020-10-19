<?php 







$current_user = wp_get_current_user();



if($current_user->ID == 0)



{



  $redirect_url = home_url('/user-system-login/');



  wp_redirect($redirect_url);



  exit();



}



else if($current_user->ID != 0){



  $user_role = $current_user->roles[0];



  if($user_role == 'administrator')



  {



    $redirect_url = home_url();



    wp_redirect($redirect_url);



    exit();



  }



}







if(isset($_GET['msg_submit'])) 



{



	date_default_timezone_set("America/New_York");



	if($current_user->ID != 0)



  	{



		$userID  = $current_user->ID; // uiser id



		//echo $userID;



		$userMsg = $_GET['msg_send'];



		$userMsg = message_input($userMsg); 



		$file_date = date("Y-m-d h:i:s");



		global $wpdb;



		$tablename = 'user_system_messaging'; //$wpdb->prefix.'yuliza_messaging';



		$staus_table_insert = $wpdb->insert( $tablename, array(



			'message' => $userMsg,



			'sender_id' => $current_user->ID, 



			'receiver_id' => 1001,



			'created_at' => $file_date,



			'new_message_status' => 0,  



			'status' => 1



		),



		array( '%s', '%d', '%d', '%s', '%d','%d') );



		if($staus_table_insert != false)



		{



			$redirect_url = home_url('/user-system-messaging/');



			wp_redirect($redirect_url);



			exit();



		} 







  }



}











if($current_user->ID != 0)



{



  $ustatus = true;



  global $wpdb;



  $tablenamee = 'user_system_messaging'; //$wpdb->prefix.'yuliza_messaging';



  $status_table_update = $wpdb->update( $tablenamee, array(



    'new_message_status' => 1



    ),



    array( 'sender_id' => 1001), 



    array( 



      '%s'  // value1



    ), 



    array( '%d' )  



  ); 



}



else



{



  $ustatus = true;



}







// getting messages



global $wpdb;



$table = 'user_system_messaging'; // $wpdb->prefix.'yuliza_messaging';



$query = "SELECT * FROM ".$table." WHERE sender_id =".$current_user->ID." OR sender_id = 1001 AND receiver_id=1001 OR receiver_id =".$current_user->ID;



$total_query = "SELECT COUNT(*) FROM ".$table." WHERE sender_id =".$current_user->ID." OR sender_id = 1001 AND receiver_id=1001 OR receiver_id =".$current_user->ID;



$total = $wpdb->get_var( $total_query );



$items_per_page = 10;



$page = isset( $_GET['yuliza-msg'] ) ? abs( (int) $_GET['yuliza-msg'] ) : 1;



$offset = ( $page * $items_per_page ) - $items_per_page;



$result = $wpdb->get_results( $query . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}" );



$result_sidebar = $wpdb->get_results( $query . " ORDER BY id DESC LIMIT 0, 1" );



$totalPage =  ceil($total / $items_per_page);











?>







<div id="header-top">



	<!-- <div id="logo"><a href="#"><img src="images/logo.png"></a></div> -->



	<div id="toggle-area">



		<div class="dropdown">



			<button onclick="myFunction()" class="dropbtn">Member<i class="fa fa-caret-down"></i></button>



			<div id="myDropdown" class="dropdown-content">



				<a href="<?php echo home_url('/user-system-edit-profile/'); ?>" target="_blank">Edit Profile</a>



				<a href="<?php echo home_url('/user-system-admin-files/'); ?>">Files</a>



				<a href="<?php echo home_url('/user-system-messaging/'); ?>">Messaing</a>



				<a href="<?php echo home_url('/user-system-dashboard/'); ?>">Upload Files</a>



			</div>



		</div>



	</div>



	<div class="clearfix"></div>



</div>



	



<div id="main-area">







	<div id="content-lhs">



	<!--



	<img src="images/profile-pic.jpg">



	<a href="#" class="profilename">Jhon Smith</a>



	<span class="status"><strong>Status</strong> : Free</span>



	-->



		<ul>



			<li><a href="<?php echo home_url('/user-system-dashboard/'); ?>">Dashboard</a></li>



			<li><a href="<?php echo home_url('/user-system-messaging/'); ?>">Messaging</a></li>



			<li><a href="<?php echo home_url('/user-system-admin-files/'); ?>">Admin Files</a></li>



			<li><a href="<?php echo wp_logout_url(); ?>">Logout</a></li>



		</ul>







	</div>



	<div id="content-rhs">



		<h1 class="hdg"><?php echo $current_user->user_login; ?></h1>



		<div id="card" class="nopadding">



			<div class="chat-boxsmain">



			<?php 



			if(count($result) > 0)



			{ 



				foreach( array_reverse($result) as $message ) 



				{



					if( $message->sender_id == 1001 )



					{ ?>



						<!-- admin -->



						<div class="user-chat-containar">



						<img src="<?php echo plugin_dir_url(USER_PLUGIN_DIR).'user-system/assets/images/admin_pic.jpg'; ?>" class="my-chat-img" alt="">



						<span class="span-chat">



						<?php echo $message->message  ?>



						</span>



						<!-- <span class="date-time-span">Sarah Deceomber 12, 09;45 pm</span> -->



						</div><!--col end-->



			  <?php } 



					if($message->sender_id == $current_user->ID )



					{ ?>



					<!--user chat container end-->



					<div class="my-chat-containar">



					<!-- <img src="http://dev57.onlinetestingserver.com/yuliza-net/wp-content/uploads/2019/03/msg-img-2_03.png" class="my-chat-img-right" alt=""> -->



					<img src="<?php echo plugin_dir_url(USER_PLUGIN_DIR).'user-system/assets/images/client_pic.jpg'; ?>" class="my-chat-img-right" alt="">



					<span class="span-chat">



					<?php echo $message->message  ?>



					</span>



					<div class="clearfix"></div>



					<!-- <span class="date-time-span">Sarah Deceomber 12, 09;45 pm</span> -->



					</div>







				<?php } 



				}



			}  



			else



			{ ?>



				<span style="color: black">



					No messages right now...!!!!



				</span>



	  <?php } ?>



			<!-- Pagination -->



			<?php 



			if($totalPage > 1) 



			{



				$customPagHTML     =  '<div><span>Page '.$page.' of '.$totalPage.'</span> -&nbsp;&nbsp'. paginate_links( array(



				'base' => add_query_arg( 'yuliza-msg', '%#%' ),



				'format' => '',



				'prev_text' => __('&laquo;'),



				'next_text' => __('&raquo;'),



				'total' => $totalPage,



				'current' => $page



				)).'</div>';



			}                   



			echo $customPagHTML;



			echo "<br>";



			?>







				<div class="chat-boxs clearfix">



					<form>



						<input type="hidden" name="msg_submit"> 



						<textarea  name="msg_send" placeholder="Send a Message"></textarea>



						<button type="Submit">SUBMIT</button>



					</form>



				</div>



			</div>



		</div>



	</div>



</div>







<script>



/* When the user clicks on the button, 



toggle between hiding and showing the dropdown content */



function myFunction() 



{



  document.getElementById("myDropdown").classList.toggle("show");



}







// Close the dropdown if the user clicks outside of it



window.onclick = function(event) 



{



  if (!event.target.matches('.dropbtn')) 



  {



    var dropdowns = document.getElementsByClassName("dropdown-content");



    var i;



    for (i = 0; i < dropdowns.length; i++) 



    {



      var openDropdown = dropdowns[i];



      if (openDropdown.classList.contains('show')) 



      {



        openDropdown.classList.remove('show');



      }



    }



  }



}



jQuery(document).ready(function() 



{



  jQuery('#example').DataTable();



} );



        



</script>



	



