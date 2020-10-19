<?php 
ob_start();
$current_user = wp_get_current_user();
if($current_user->ID == 0){ ?>
<div id="register_area">
   <h1>register</h1>
   
   <span class="clearfix success-messages" style="color: green;">
   </span>
   <center>
   <span class="clearfix failure-messages" style="color: red;">

   </span>
   </center>
   <!-- Used to display Element errors. -->
   <div id="card-errors" role="alert"></div>
   <form id="register-form" class="myform">
      <span class="mainn-errorr" style="color:red;"></span>
      <div class="group">
         <label>
            <div class="outcome">
               <div class="error"></div>
               <div class="success"></div>
            </div>
         </label>
         <label>
            <span>User Name</span>
            
            <input type="text" id="user-name" name="user-name" class="field" placeholder="User Name" />
           
         </label>
         
         <label>
            <span>Email </span>
            <input type="email" id="user-email" name="user-email" class="field" placeholder="Email" />
         </label>
         <label>
            <span>Passoword </span>
            <input id="user-password" name="user-password" type="password" class="field" placeholder="Passoword"/>
         </label>
         <!--<label>-->
         <!--   <span>Select User Role</span>-->
         <!--   <select class="field" id="user-role" name="user-role">-->
         <!--      <option value="staff">Staff</option>-->
         <!--      <option value="patient">Patient</option>-->
         <!--      <option value="student">Student</option>-->
         <!--   </select>-->
         <!--</label>-->
      </div>
      <input type="hidden" name="action" value="register_form">
      <p id="register-msg" style="text-align: center;"></p>
      <span id="register-span">
      <button type="submit" id="reg-btn">Register</button>
      </span>
      
   </form>
</div>
<!-- <script type='text/javascript' src='https://granteaze.com/wp-content/plugins/user-system/assets/js/stripe_js.js?ver=5.1.1'></script> -->
<?php
}
else{
	wp_redirect(home_url('/user-system-dashboard'));						
}
   ?>