<?php 


if(isset($_GET['user_system_registered'])) 
{
	$errors = 0;
	if (empty($_GET["user_system_name"])) 
	{
	  $nameErr = "Name is required";
	  $errors += 1;
	}
	else {
	  $user_system_name = user_system_test_input($_GET["user_system_name"]); 
	  if(strlen($user_system_name) > 60) {
	    $nameErr = "Can't exceed more than 60";
	    $errors += 1;
	  }
	}

	if (empty($_GET["user_system_email"])) {
	  $emailErr = "Email is required";
	  $errors += 1;
	} 
	else {
	  $user_system_email = user_system_test_input($_GET["user_system_email"]);
	  // check if e-mail address is well-formed
	  if (!filter_var($user_system_email, FILTER_VALIDATE_EMAIL)) {
	    $emailErr = "Invalid email format";
	    $errors += 1; 
	  }
	}

	if (empty($_GET["user_system_password"])) {
	  $passwordErr = "Password is required";
	  $errors += 1;
	}
	else {
	  $user_system_password = user_system_test_input($_GET["user_system_password"]);
	}

	if($errors == 0) {
	  $userdata = array(
	      'user_login'  => $user_system_name,
	      'user_pass'   =>  $user_system_password,  // When creating a new user, `user_pass` is expected.
	      'user_email' => $user_system_email,
	      'role' => 'user_system'
	  );
	  $user_id = wp_insert_user( $userdata ) ;
	  //On success
		if ( ! is_wp_error( $user_id ) ) {

			add_user_meta( $user_id, 'user_system_user_status',1, true );
			$redirect_url = home_url('/user-system-register/?user_status=User%20Successfully%20Created');
			wp_redirect($redirect_url);
		}
	  	else 
		{
			$redirect_url = home_url('/user-system-register/?user_status=Oops!%20User%20Not%20Created');
			wp_redirect($redirect_url);
	  }
	}
}



$current_user = wp_get_current_user();
if($crrent_user->ID == 0)
{ ?>

	<div id="register_area">
		<h1>register</h1>
	  	<?php 
	    if(isset($_GET['user_status'])) 
	    {
    		if($_GET['user_status'] == 'User Successfully Created')
    		{ ?>
			<div class="clearfix" style="color: green;">
				<strong>Weldone!</strong> <?php echo $_GET['user_status'];   ?>
			</div>
    	<?php
    		}
    		else
			{ ?>
			<div class="clearfix" style="color: red;">
				<strong>Oh !</strong> <?php echo $_GET['user_status']; ?>
			</div>
      <?php } 
		} ?>
		<form>
			<div class="filed">
				<i class="fa fa-user"></i>
				<input type="text" name="user_system_name" id="user_system_name" placeholder="User name" value="<?php echo isset($user_system_name) ? $user_system_name : ''  ?>">
				<small style="color: red">
    				<?php echo isset($nameErr) ? $nameErr : ''  ?>
				</small>
			</div>
			<div class="filed">
				<i class="fa fa-envelope"></i>
				<input type="email" name="user_system_email" id="user_system_email" placeholder="Email" value="<?php echo isset($user_system_email) ? $user_system_email : ''  ?>">
				<small style="color: red">
					<?php echo isset($emailErr) ? $emailErr : ''  ?>
				</small>
			</div>

			<div class="filed">
				<i class="fa fa-lock"></i>
				<input type="password" name="user_system_password" id="user_system_password" placeholder="password" value="<?php echo isset($user_system_password) ? $user_system_password : ''  ?>">
				<small style="color: red">
    				<?php echo isset($passwordErr) ? $passwordErr : ''  ?>
				</small>
			</div>
			
			<input type="hidden" name="user_system_registered">
			<input type="submit" class="button-submit" style="color: black" value="Register">
		</form>
<!-- 		<small class="filed"> 
			<a style="color: blue;" href="<?php  echo home_url('/user-system-login/') ?>">Login</a>
		</small> -->
	</div>

<?php
}
else
{
	wp_redirect(home_url());					

}

?>











