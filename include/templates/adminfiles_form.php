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
global $wpdb;
$table  = 'user_system_files'; // $wpdb->prefix.'yuliza_files';
$query  = "SELECT * from " . $table . " where sender_id=1001 and  receiver_id =" . $current_user->ID . " and status=1";
$offset = 1;
$result = $wpdb->get_results($query . " ORDER BY id DESC");
?>
<div id="header-top">
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
      <ul>
         <li><a href="<?php echo home_url('/user-system-dashboard/'); ?>">Dashboard</a></li>
         <li><a href="<?php echo home_url('/user-system-messaging/'); ?>">Messaging</a></li>
         <li><a href="<?php echo home_url('/user-system-admin-files/'); ?>">Admin Files</a></li>
         <li><a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
      </ul>
   </div>
   <div id="content-rhs">
      <h1>Admin Files</h1>
      <div id="card">
         <!--         <h1>Jhon Smith files</h1>-->
         <div class="table-main">
            <table id="example" class="cell-border" style="width:100%">
               <thead>
                  <tr>
                     <th>Serial No.</th>
                     <th>Name</th>
                     <th>File Size</th>
                     <th>Download File</th>
                  </tr>
               </thead>
               <tbody>
                  <?php 
                     foreach($result as $row) 
                     
                     
                     
                     { ?>
                  <tr>
                     <td><?php echo $offset; $offset++; ?></td>
                     <td><i class="fa fa-circle" aria-hidden="true"></i><?php echo $row->file_name; ?></td>
                     <td><?php echo $row->file_size; ?> bytes </td>
                     <td>
                        <?php 
                           $file = 'user_system_admin/'.$row->file_name;
                           
                           
                           
                           $file_to_download = 'wp-content/uploads/'.$file;
                           
                           
                           
                           ?>
                        <a class="view-btn" href="<?php echo home_url('/'.$file_to_download); ?>" download>
                        DOWNLOAD
                        </a>
                     </td>
                  </tr>
                  <?php } ?> 
               </tbody>
            </table>
            <div class="clearfix"></div>
         </div>
         <div class="clearfix"></div>
      </div>
   </div>
</div>
<script>
   /* When the user clicks on the button, 
   toggle between hiding and showing the dropdown content */
   function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
   }
   // Close the dropdown if the user clicks outside of it
   window.onclick = function(event) {
     if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
         }
       }
     }
   }
   
   jQuery(document).ready(function() {
       jQuery('#example').DataTable();
  
   });
</script>