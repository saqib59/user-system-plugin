<?php 











// checking text



function user_system_test_input($data) 



{



	$data = trim($data);



	$data = stripslashes($data);



	$data = htmlspecialchars($data);



	return $data;



}



// for messaging text checking



function message_input($data)



{



	$data = trim($data);



	$data = htmlspecialchars($data);



	return $data;



}



// file download function



function user_system_file_download()



{



	if(isset($_GET['file-download'])) 



	{







		if( !empty( $_GET['file-name-download'] ) ) 



		{



			$file = $_GET['file-name-download'];



		    $file_to_download = ABSPATH.'wp-content/uploads/'.$file; // file to be downloaded



		  	header("Expires: 0");



		  	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");



			header("Cache-Control: no-store, no-cache, must-revalidate");



			header("Cache-Control: post-check=0, pre-check=0", false);



		  	header("Pragma: no-cache");  



		  	header("Content-type: application/file");



		  	header('Content-length: '.filesize($file_to_download));



		  	header('Content-disposition: attachment; filename='.basename($file_to_download));



	  	 	$chunkSize = filesize($file_to_download);



			$handle = fopen($file_to_download, 'rb');



			while (!feof($handle))



			{



			    $buffer = fread($handle, $chunkSize);



			    echo $buffer;



			    ob_flush();



			    flush();



			}



			fclose($handle);



			exit;



		}



	}



}







if( isset($_GET['page']) && ( $_GET['page'] =='user-system' || $_GET['page'] =='user-system-user' ||



	$_GET['page'] =='user-system-files' || $_GET['page'] =='user-system-files-sent' ||



	$_GET['page'] =='user-system-messages' ) )



{



	add_action( 'admin_enqueue_scripts', 'usersystem_enqueue_styles' );



}



function usersystem_enqueue_styles() 



{



	wp_enqueue_script('admin_js', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/js/jquery.min.js',false);



   	// For Child Theme



   	wp_enqueue_style( 'bootstrap_css', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/css/bootstrap.css');



   	wp_enqueue_style( 'font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');



   	wp_enqueue_script( 'bootstrap_script', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/js/bootstrap.min.js', array(), '4.0.0', true ); 



}



// Making User Role 



add_role('user_system_staff', __( 'Staff', 'staff' ));

add_role('user_system_patient', __( 'Patient', 'patient' ));

add_role('user_system_student', __( 'Student', 'student' ));







// Checking User Login



//add_action('init', 'checking_user_access');



function checking_user_access() 



{



	global $current_user;



	$redirect = home_url( '/' );



	$user_roles = $current_user->roles;



	$user_role = array_shift($user_roles);



	if($user_role === 'yuliza_user') 



	{



		exit( wp_redirect( 'https://www.google.com/' ) );



	}



}



function my_login_redirect() 



{



	global $current_user;



	$redirect = home_url( '/' );



	$user_roles = $current_user->roles;



	$user_role = array_shift($user_roles);



	if($user_role === 'universal_tax_user') 



	{  	



		wp_redirect( 'https://www.google.com/' );



	}



	else



 	{



		return;



	}



}



//add_filter('login_redirect', 'my_login_redirect');



function create_wp_tables() 



{



	global $wpdb;		



		$charset_collate = $wpdb->get_charset_collate();	



		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );



	 //* Create the files table



	 $table_name = 'user_system_files'; //$wpdb->prefix . 'univarsal_tax_files';







	 $sql = "CREATE TABLE $table_name (







	 id BIGINT(20) NOT NULL AUTO_INCREMENT,



	 user_id BIGINT(20) NOT NULL,



	 file_name VARCHAR(225),



	 file_extension VARCHAR(225),



	 file_size VARCHAR(50),



	 file_location VARCHAR(225),



	 new_file_status TINYINT,



	 created_at TIMESTAMP,



	 updated_at TIMESTAMP,



	 status TINYINT,



	 sender_id BIGINT(20),



	 receiver_id BIGINT(20),



	 PRIMARY KEY (id)



	 ) $charset_collate;";



	 dbDelta( $sql );















	 // Create universal messaging system







	  //* Create the teams table







	 $table_name_messaging = 'user_system_messaging'; //$wpdb->prefix . 'yuliza_messaging';



	 $sql_messaging = "CREATE TABLE $table_name_messaging (



	 id BIGINT(20) NOT NULL AUTO_INCREMENT,



	 message TEXT,



	 sender_id BIGINT(20) NOT NULL,



	 receiver_id BIGINT(20) NOT NULL,



	 new_message_status TINYINT,



	 created_at TIMESTAMP,



	 updated_at TIMESTAMP,



	 status TINYINT,



	 PRIMARY KEY (id)







	 ) $charset_collate;";



	 dbDelta( $sql_messaging );



}



add_action( 'after_setup_theme', 'create_wp_tables' );



// Adding Menu to admin dashboard 



add_action( 'admin_menu', 'wpse_91693_register' );







function wpse_91693_register()



{



	// Universal tax main dashboard



    add_menu_page(



        'User System',     // page title



        'User System',     // menu title



        'manage_options',   // capability



        //'universal-tax',     // menu slug



        'user-system',	// menu slug



        //'universal_tax_mainpage', // callback function



        'user_system_mainpage', //// callback function



        'dashicons-admin-site', // mene icons



        90



    );



    // Universal user 



 	add_menu_page(



        'User System - User',     // page title



        'User System - User',     // menu title



        'manage_options',   // capability



        'user-system-user',     // menu slug



        'user_system_user', // callback function



        'dashicons-admin-site', // mene icons



        120



    );



    // Universal user files



 	add_menu_page(



        'User System - Files',     // page title



        'User System - Files',     // menu title



        'manage_options',   // capability



        'user-system-files',



        'user_system_files', // callback function



        'dashicons-admin-site', // mene icons



        140



    );



   // Universal user files



 	add_menu_page(



        'User System -  Files Sent',     // page title



        'User System -  Files Sent',     // menu title



        'manage_options',   // capability



        'user-system-files-sent',     // menu slug



        'user_system_files_sent', // callback function



        'dashicons-admin-site', // mene icons



        160



    );







	   // Universal user messages



 	add_menu_page(



        'User System - Messages',     // page title



        'User System -  Messages',     // menu title



        'manage_options',   // capability



        'user-system-messages',     // menu slug



        'user_system_messages', // callback function



        'dashicons-admin-site', // mene icons



        180



    );







	// remove universal tax user page



	remove_menu_page( 'user-system-user' ); 



	// remove universal tax user files page



	remove_menu_page( 'user-system-files');



	// remove universal tax user files page



	remove_menu_page( 'user-system-files-sent' );



	// remove universal tax user messages page



 	remove_menu_page( 'user-system-messages' );



}







// universal taxt user files sent callback



function user_system_files_sent()



{



	$file = plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user-files-sent.php';



    if ( file_exists( $file ) )



    {



        require plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user-files-sent.php';



    }



    else



    {



    	echo "not exist";



    }



}



// universal tax user messages callback



function user_system_messages() 



{



	$file = plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user-messages.php';



    if ( file_exists( $file ) ){



        require plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user-messages.php';



    }



    else



    {



    	echo "not exist";



    }



} 



// universal tax user files callback



function user_system_files() 



{



	$file = plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user-files.php';



    if ( file_exists( $file ) )



    {



        require plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user-files.php';



    }



    else



    {



    	echo "not exist";



    }



} 



// universal tax mainpage callback



function user_system_mainpage()



{



	$file = plugin_dir_path( __FILE__ ).'admin-templates/included.php';



    if ( file_exists( $file ) )



    {



        require plugin_dir_path( __FILE__ ).'admin-templates/included.php';



    }



    else



    {



    	



    	echo "not exist";



    }



}



// Adding Sub menu page view user



function user_system_user() 



{



	$file = plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user.php';



    if ( file_exists( $file ) )



    {



        require plugin_dir_path( __FILE__ ).'admin-templates/universal-tax-user.php';



    }



    else



    {



    	echo "not exist";



    }



}















/*****************   SEARCH BY SLUG PAGE TITLE ****************/



// checking page is available or not







function get_page_title_for_slug($page_slug) 



{



	$page = get_page_by_path( $page_slug , OBJECT ); 



	if ( isset($page) ) 



	{



		return true;



 	}



	else



	{



		return false;



	}



}







add_action( 'wp', 'wpse69369_special_thingy' );



function wpse69369_special_thingy()



{



    global $post;



    $post_slug = $post->post_name;



    if ($post_slug == 'user-system-register' || $post_slug == 'user-system-login' || $post_slug == 'user-system-dashboard' || $post_slug == 'user-system-messaging' || $post_slug == 'user-system-admin-files' || $post_slug == 'user-system-edit-profile')



    {



    	add_action( 'wp_enqueue_scripts', 'usersystem_enqueue_front' );



    }







}







function usersystem_enqueue_front()



{



	wp_enqueue_style( 'front-style', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/css/style.css');
	wp_enqueue_style( 'front-font-style', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/css/font-awesome.min.css');



	wp_enqueue_style( 'front-datatables-style', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/css/jquery.dataTables.min.css');



	wp_enqueue_style( 'front-googlefonts-style', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/css/google-fonts.css');







	wp_enqueue_script( 'front-script', plugin_dir_url( USER_PLUGIN_DIR ).'user-system/assets/js/jquery.dataTables.min.js', true ); 



}











?>