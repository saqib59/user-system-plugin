<?php 

if(!session_id()) 
{
	session_start();
}


$current_user = wp_get_current_user();
if($current_user->ID == 0)
{ ?>
<div id="register_area">
		<h1>login</h1>
		<?php 
		if(isset($emailErr) || isset($passwordErr))
		{
			if(isset($emailErr)) 
			{
				$errMsg = $emailErr;
			}
			else if(isset($passwordErr))
			{
				$errMsg = $passwordErr;
			}
		?>
		<div class="clearfix" style="color: red;">
			<strong>Oops!</strong> <?php echo $errMsg; ?>
		</div>
  <?php } ?>
	<form id="login-form">
		<div class="filed">
			<i class="fa fa-user"></i>
			<input type="text" name="user_system_email" id="user_system_email" placeholder="User name">
			<span style="color: red;" id="user_system_email_err"></span>
		</div>
		<div class="filed">
			<i class="fa fa-lock"></i>
			<input type="password" id="user_system_password" name="user_system_password" placeholder="password">
			<span style="color: red;" id="user_system_password_err"></span>
		</div>
		<div class="field myremember">
			<label class="containerform">Remember me
  			  	<input type="checkbox" id="user_system_remember" name="user_system_remember">
				<span class="checkmark"></span>
			</label>
			<a href="<?php echo wp_lostpassword_url(); ?>" class="forgot-pass">Forgot Password?</a>
			<div class="clearfix"></div>
		</div>
		<input type="hidden" name="action" value="login_form">
		<p id="login-msg" style="text-align: center;"></p>
		<!-- <input type="submit" class="button-submit" value="Login" style="color: black"> -->
		<button type="submit" class="button-submit"style="color: black">Login</button>
	</form>
<!-- 	<small class="filed"> 
			<a style="color: blue;" href="<?php  echo home_url('/user-system-register/') ?>">Register</a>
	</small> -->
</div>

<?php 
}
else
{
	wp_redirect(home_url('/user-system-dashboard'));						
}

?>

