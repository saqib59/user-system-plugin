<?php
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    global $wpdb;
    $tablenamee          = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
    //var_dump($tablenamee);
    $status_table_update = $wpdb->update($tablenamee, array(
        'new_message_status' => 1
    ), array(
        'sender_id' => $userId
    ), array(
        '%s' // value1
    ), array(
        '%d'
    ));
}
if (isset($_GET['msg_submit'])) {
    date_default_timezone_set("America/New_York");
    $receiver = $_GET['user_id'];
    if (isset($receiver) && !empty($receiver)) {
        $universal_user = get_userdata($receiver);
        if ($universal_user != false) {
            $userMsg   = $_GET['msg_send'];
            $userMsg   = message_input($userMsg);
            $file_date = date("Y-m-d h:i:s");
            global $wpdb;
            $tablename          = 'user_system_messaging'; //$wpdb->prefix.'univarsal_tax_messaging';
            $staus_table_insert = $wpdb->insert($tablename, array(
                'message' => $userMsg,
                'sender_id' => 1001,
                'receiver_id' => $universal_user->ID,
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
                $page    = $_GET['page'];
                $user_id = $_GET['user_id'];
                wp_redirect(admin_url('admin.php?page=' . $page . '&user_id=' . $user_id));
                exit();
            }
        }
    }
}
?>

<style type="text/css">
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
   width: 60%;
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
<div class="container">
   <div class="row">
      <div class="col">
         <h3 class=" text-center">Messaging</h3>
      </div>
      <div class="col">
         <a style="text-decoration: none;" class="btn btn-primary" href="<?php echo home_url('/wp-admin/admin.php?page=user-system-user&user_id=') . $_GET['user_id']; ?>">BACK</a>
      </div>
      <div class="col"></div>
   </div>
   <div class="row">
      <div class="col">
      </div>
   </div>
   <?php
    if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
      $ustatus        = true;
      $universal_user = get_userdata($_GET['user_id']);
      if ($universal_user == false) {
        $ustatus = false;
      } 
      else {
        $ustatus = true;
      }
    } 
    else {
      $ustatus = false;
    }
?>
   <div class="row">
      <div class="col">
         <div class="messaging">
            <span><?php echo isset($fileErr) ? $fileErr : '';?></span>
            <div class="inbox_msg">
               <div class="inbox_people">
                  <div class="headind_srch">
                     <div class="recent_heading">
                        <h4>
                          <?php
                          if (isset($ustatus) && $ustatus == true) {
                            echo $universal_user->data->user_login;
                          } else {
                            echo "User Not Found";
                          }
                          ?>
                        </h4>
                     </div>
                
                  </div>
                  <div class="inbox_chat">
                     <div class="chat_list active_chat">
                        <div class="chat_people">
                           <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                           <div class="chat_ib">
                              <h5>
                                 <?php
                              if (isset($ustatus) && $ustatus == true) {
                              echo $universal_user->data->user_login;
                              } else {
                              echo "User Not Found";
                              }
                            ?> 
                  
                              </h5>
                           </div>
                        </div>
                     </div>
          
                  </div>
               </div>
               <div class="mesgs">
                  <div class="msg_history">


    
       
                    <!--  <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="received_msg">
                           <div class="received_withd_msg">
                              <p>asda</p>
                           </div>
                        </div>
                     </div> -->
                    
                     <!-- <div class="outgoing_msg">
                        <div class="sent_msg">
                          <p>dfg</p>
                          
                        </div>
                     </div> -->
     
                  </div>
         
                  <div class="type_msg">
                     <div class="input_msg_write">
                       
                        <form id="admin_msg" action="javascript:" method="post">
                           <input type="hidden" name="page" value="user-system-messages">
                           <input type="hidden" name="user_id" value="<?php echo $universal_user->ID; ?>">
                           <input type="hidden" name="msg_submit">  
                           <input type="text" class="write_msg" name="msg_send" placeholder="Type a message" />
                           <button type="button" class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                        </form>
                      
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
    //alert('Load Chat');
    var user_id = $('input[name=user_id]').val();
    var data = {
      'action': 'get_admin_messages',
      'post_type': 'POST',
      'user_id': user_id,
    };


    $.post(adminpath.ajaxurl, data, function(response) {

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
      // alert('enter is pressed');
      admin_message_submit();
    }
  });

  function admin_message_submit(){
    var message = $('.write_msg').val();  /**  Admin Message **/
    var user_id = $('input[name=user_id]').val();

    if(message == '' || message.trim().length == 0){
      return;
    }
    
    $('.write_msg').val('');

    var data = {
      'action': 'submit_admin_message',
      'post_type': 'POST',
      'user_id': user_id,
      'admin_message' : message,
    };
    //alert(adminpath.ajaxurl);
    $.post(adminpath.ajaxurl, data, function(response) {
      //console.log(response);
      if(response.status){
        alert(response.error);
      }
      else if(response.status == false){
        LoadChat(); 
        //$('.write_msg').val('');
        //alert(response.error);
      }
      

    },'json');
  
  }

  $('.msg_send_btn').click(function (e) {
    e.preventDefault();
    admin_message_submit();
  });


   


});
</script>
