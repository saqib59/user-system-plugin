<?php
   $current_user = wp_get_current_user();
   if ($current_user->ID == 0) {
       $redirect_url = home_url('/user-system-login/');
       wp_redirect($redirect_url);
       exit();
   } else if ($current_user->ID != 0) {
       $user_role = $current_user->roles[0];
       if ($user_role == 'administrator') {
           $redirect_url = home_url();
           wp_redirect($redirect_url);
           exit();
       }
   }
   if (isset($_GET['msg_submit'])) {
       date_default_timezone_set("America/New_York");
       if ($current_user->ID != 0) {
           $userID    = $current_user->ID; // uiser id
           //echo $userID;
           $userMsg   = $_GET['msg_send'];
           $userMsg   = message_input($userMsg);
           $file_date = date("Y-m-d h:i:s");
           global $wpdb;
           $tablename          = 'user_system_messaging'; //$wpdb->prefix.'yuliza_messaging';
           $staus_table_insert = $wpdb->insert($tablename, array(
               'message' => $userMsg,
               'sender_id' => $current_user->ID,
               'receiver_id' => 1001,
               'created_at' => $file_date,
               'new_message_status' => 0,
               'status' => 1
           ), array(
               '%s',
               '%d',
               '%d',
               '%s',
               '%d',
               '%d'
           ));
           if ($staus_table_insert != false) {
               $redirect_url = home_url('/user-system-messaging/');
               wp_redirect($redirect_url);
               exit();
           }
       }
   }
   if ($current_user->ID != 0) {
       $ustatus = true;
       global $wpdb;
       $tablenamee          = 'user_system_messaging'; //$wpdb->prefix.'yuliza_messaging';
       $status_table_update = $wpdb->update($tablenamee, array(
           'new_message_status' => 1
       ), array(
           'sender_id' => 1001
       ), array(
           '%s' // value1
       ), array(
           '%d'
       ));
   } else {
       $ustatus = true;
   }
   // getting messages
   global $wpdb;
   $table          = 'user_system_messaging'; // $wpdb->prefix.'yuliza_messaging';
   $query          = "SELECT * FROM " . $table . " WHERE sender_id =" . $current_user->ID . " OR sender_id = 1001 AND receiver_id=1001 OR receiver_id =" . $current_user->ID;
   $total_query    = "SELECT COUNT(*) FROM " . $table . " WHERE sender_id =" . $current_user->ID . " OR sender_id = 1001 AND receiver_id=1001 OR receiver_id =" . $current_user->ID;
   $total          = $wpdb->get_var($total_query);
   $items_per_page = 10;
   $page           = isset($_GET['yuliza-msg']) ? abs((int) $_GET['yuliza-msg']) : 1;
   $offset         = ($page * $items_per_page) - $items_per_page;
   $result         = $wpdb->get_results($query . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}");
   $result_sidebar = $wpdb->get_results($query . " ORDER BY id DESC LIMIT 0, 1");
   $totalPage      = ceil($total / $items_per_page);
   ?>
<style type="text/css">
	#main-area {
    display: flex;
    min-height: auto;
}	
   .container{max-width:1170px; margin:auto;}
   img{ max-width:100%;}
   .inbox_people {
   background: #f8f8f8 none repeat scroll 0 0;
   float: left;
   overflow: hidden;
   width: 40%; border-right:1px solid #c4c4c4;
   }
   .inbox_msg {
   border: 1px solid #c4c4c4;
   clear: both;
   overflow: hidden;
   }
   .top_spac{ margin: 20px 0 0;}
   .recent_heading {float: left; width:40%;}
   .srch_bar {
   display: inline-block;
   text-align: right;
   width: 60%; 
   padding:0;
   }
   .headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}
   .recent_heading h4 {
   color: #05728f;
   font-size: 21px;
   margin: auto;
   }
   .srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
   .srch_bar .input-group-addon button {
   background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
   border: medium none;
   padding: 0;
   color: #707070;
   font-size: 18px;
   }
   .srch_bar .input-group-addon { margin: 0 0 0 -27px;}
   .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
   .chat_ib h5 span{ font-size:13px; float:right;}
   .chat_ib p{ font-size:14px; color:#989898; margin:auto}
   .chat_img {
   float: left;
   width: 11%;
   }
   .chat_ib {
   float: left;
   padding: 0 0 0 15px;
   width: 88%;
   }
   .chat_people{ overflow:hidden; clear:both;}
   .chat_list {
   border-bottom: 1px solid #c4c4c4;
   margin: 0;
   padding: 18px 16px 10px;
   }
   .inbox_chat { height: 550px; overflow-y: scroll;}
   .active_chat{ background:#ebebeb;}
   .incoming_msg_img {
   display: inline-block;
   width: 6%;
   }
   .received_msg {
   display: inline-block;
   padding: 0 0 0 10px;
   vertical-align: top;
   width: 92%;
   }
   .received_withd_msg p {
   background: #ebebeb none repeat scroll 0 0;
   border-radius: 3px;
   color: #646464;
   font-size: 14px;
   margin: 0;
   padding: 5px 10px 5px 12px;
   width: 100%;
   }
   .time_date {
   color: #747474;
   display: block;
   font-size: 12px;
   margin: 8px 0 0;
   }
   .received_withd_msg { width: 57%;}
   .mesgs {
   float: left;
   padding: 30px 15px 0 25px;
   width: 100%;
   }
   .sent_msg p:after {
    content: "\f00c";
    font-family: fontawesome !important;
    float: right;
  }
   .sent_msg p {
   background: #05728f none repeat scroll 0 0;
   border-radius: 3px;
   font-size: 14px;
   margin: 0; color:#fff;
   padding: 5px 10px 5px 12px;
   width:100%;
   }
   .outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
   .sent_msg {
   float: right;
   width: 46%;
   }
   .input_msg_write input {
   background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
   border: medium none;
   color: #4c4c4c;
   font-size: 15px;
   min-height: 48px;
   width: 100%;
   }
   .type_msg {border-top: 1px solid #c4c4c4;position: relative;}
   .msg_send_btn {
   background: #05728f none repeat scroll 0 0;
   border: medium none;
   border-radius: 50%;
   color: #fff;
   cursor: pointer;
   font-size: 17px;
   height: 33px;
   position: absolute;
   right: 0;
   top: 11px;
   width: 33px;
   }
   .messaging { padding: 0 0 50px 0;}
   .msg_history {
	   height: 516px;
	   overflow-y: auto;
   }


	</style>




<div id="header-top">
   <!-- <div id="logo"><a href="#"><img src="images/logo.png"></a></div> -->
   <div id="toggle-area">
      <div class="dropdown">
         <button onclick="myFunction()" class="dropbtn">Member<i class="fa fa-caret-down"></i></button>
         <div id="myDropdown" class="dropdown-content">
            <a href="<?php
               echo home_url('/user-system-edit-profile/');
               ?>" target="_blank">Edit Profile</a>
            <a href="<?php
               echo home_url('/user-system-admin-files/');
               ?>">Files</a>
            <a href="<?php
               echo home_url('/user-system-messaging/');
               ?>">Messaing</a>
            <a href="<?php
               echo home_url('/user-system-dashboard/');
               ?>">Upload Files</a>
         </div>
      </div>
   </div>
   <div class="clearfix"></div>
</div>
<div id="main-area">
	   <div id="content-lhs">

	      <ul>
	         <li><a href="<?php
	            echo home_url('/user-system-dashboard/');
	            ?>">Dashboard</a></li>
	         <li><a href="<?php
	            echo home_url('/user-system-messaging/');
	            ?>">Messaging</a></li>
	         <li><a href="<?php
	            echo home_url('/user-system-admin-files/');
	            ?>">Admin Files</a></li>
	         <li><a href="<?php
	            echo wp_logout_url(home_url());
	            ?>">Logout</a></li>
	      </ul>
	   </div>
   <div id="content-rhs">
		<h1 class="hdg"><?php echo $current_user->user_login; ?></h1>
		<div class="container">
		   <div class="row">
		      <div class="col">
		         <div class="messaging">
		 
		            <div class="inbox_msg">
		              
		               <div class="mesgs">
		                  <div class="msg_history">
		                  
		          			   </div>
                       <div class="user_typing" style="color: red;"></div>
		                  <div class="type_msg">
		                     <div class="input_msg_write">
		                        <form id="admin_msg" action="javascript:" method="post">
		                           <input type="hidden" name="user_id" value="<?php echo $current_user->ID; ?>">
		                           <input type="text" class="write_msg msg_box" name="msg_send" placeholder="Type a message" />
		                           <button type="button" class="msg_send_btn" type="button">
		                       			<i class="fa fa-paper-plane-o" aria-hidden="true"></i>
		                   			</button>
		                        </form>
		                     </div>
		                  </div>
		               </div>
		            </div>
		         </div>
		      </div>
		   </div>
		</div>
   </div>
</div>



<script type="text/javascript">
jQuery(document).ready(function ($) {

    LoadChat();
    setInterval(function () 
    {
        LoadChat();
    }, 1000);

  function LoadChat() {

    var user_id = $('input[name=user_id]').val();
    var data = {
      'action': 'get_user_messages',
      'post_type': 'POST',
      'user_id': user_id,
    };


    $.post(ajaxpath.ajaxurl, data, function(response) {

      //console.log(response);
      var scrollpos = $('.msg_history').scrollTop();
      var scrollpos = parseInt(scrollpos) + 420;
      var scrollHeight = $('.msg_history').prop('scrollHeight');

      $('.msg_history').html(response.error);
      if (scrollpos < scrollHeight){

      } 
      else{
        $('.msg_history').scrollTop($('.msg_history').prop('scrollHeight'));
      }

    },'json');
  }

  $('.write_msg').keyup(function(e){
    //alert(e.which);
    e.preventDefault();
    if (e.which == 13){
    
      user_message_submit();
    }
  });

  function user_message_submit(){

    var message = $('.write_msg').val();  //  User Message 
    var user_id = $('input[name=user_id]').val();

    if(message == '' || message.trim().length == 0){
      return;
    }
    $('.write_msg').val('');
    var data = {
      'action': 'submit_user_message',
      'post_type': 'POST',
      'user_id': user_id,
      'user_message' : message,
    };
    //alert(ajaxpath.ajaxurl);
    $.post(ajaxpath.ajaxurl, data, function(response) {
      //console.log(response);
      if(response.status){
        alert(response.error);
      }
      else if(response.status == false){
        LoadChat(); 
        //$('.write_msg').val('');
      }
      

    },'json');
  
  }

  $('.msg_send_btn').click(function (e) {
    e.preventDefault();
	user_message_submit();
  });


});
</script>

   
   
   
  