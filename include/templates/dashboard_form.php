<?php 

$current_user = wp_get_current_user();

if($current_user->ID == 0)

{

  $redirect_url = home_url('/user-system-login/');

  wp_redirect($redirect_url);

  exit();

}

else if($current_user->ID != 0)

{

  $user_role = $current_user->roles[0];

  if($user_role == 'administrator')

  {

    $redirect_url = home_url();

    wp_redirect($redirect_url);

    exit();

  }

}

if(isset($_POST['user_system_files_submitted'])) 

{

  $errors = 0;

  $countfiles = count($_FILES['user_system_files']['name']);

  $check_file_exists = $_FILES['user_system_files']['name'][0];

  if(empty($check_file_exists)) 

  {

     $fileErr = "Kindly Select a file";

     $errors += 1;

  }



  if($countfiles > 10) 

  {

    $fileErr = "Can't select files more than 10. You selected $countfiles  files.";

    $errors += 1;

  }

  if($errors == 0) 

  {

    $folder_location = ABSPATH.'wp-content/uploads/'.$current_user->data->user_email;

    $folder_created = wp_mkdir_p($folder_location);

    if($folder_created == true)

    {



      date_default_timezone_set("America/New_York");

      for ($file_looping=0; $file_looping < $countfiles ; $file_looping++) 

      { 

        //$original_name = array_shift($_FILES['yuliza_tax_files']['name']);

        $original_name = $_FILES['user_system_files']['name'][$file_looping];

        $attach_unique_name = uniqid(rand(), true);

        $new_name = $attach_unique_name.'_'.$original_name;

        // Now we'll move the temporary file to this plugin's uploads directory.

        //$source = array_shift($_FILES['yuliza_tax_files']['tmp_name']);

        $source = $_FILES['user_system_files']['tmp_name'][$file_looping];

        $destination = $folder_location.'/'.$new_name;

        //$destination = $folder_location.'/'.$_FILES['yuliza_tax_files']['name'];

        $file_status = move_uploaded_file( $source, $destination ); 

        $path_parts = pathinfo($original_name);

        //file extension

        $fileExtension = $path_parts['extension'];

        $file_date = date("Y-m-d h:i:s");

        

        if($file_status == true)

        {

            global $wpdb;

              $tablename = 'user_system_files'; //$wpdb->prefix.'yuliza_files';

              $staus_table_insert = $wpdb->insert( $tablename, array(

                'user_id' => $current_user->ID, 

                'file_name' => $new_name ,

                'file_extension' => $fileExtension, 

                'file_size' => $_FILES['yuliza_files']['size'][$file_looping],

                'file_location' => $current_user->data->user_email,

                'new_file_status' => 0,

                'created_at' => $file_date, 

                'status' => 1,

                'sender_id' => $current_user->ID,

                'receiver_id' => 1001,

                  ),

                  array( '%d', '%s', '%s', '%s', '%s','%d', '%s','%d','%d','%d') );

          

        }

        else

        {

          $fileErr = "File not uploaded";

        }

      }

      if($staus_table_insert != false)

      {

        //$fileErr = "Data Successfully Inserted";

        $redirect_url = home_url('/user-system-dashboard');

        wp_redirect($redirect_url);

        exit();

      }

  

    }

    else

    {

      $fileErr = "Error in creating folder";

    }

    

    //var_dump($current_user->data->user_email);

  }

} 



global $wpdb;

$table = 'user_system_files'; // $wpdb->prefix.'yuliza_files';

$query = "SELECT * from ".$table." where sender_id =".$current_user->ID." and receiver_id=1001 and status=1";

$offset = 1;

$result = $wpdb->get_results( $query . " ORDER BY id DESC");



?>



<div id="header-top">



 <!--    <div id="logo">

        <a href="#" alt="">

            <img src="https://dev3.onlinetestingserver.com/american-academy/wp-content/uploads/2019/04/logo.png">

        </a>

    </div> -->



    <div id="toggle-area">

        <div class="dropdown">

            <button onclick="myFunction()" class="dropbtn">Member <i class="fa fa-caret-down"></i></button>

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

          

        </ul>

          <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>

    </div>

    <div id="content-rhs">
            <?php       
          $first_name = get_user_meta($current_user->ID,'first_name',true);      
          $last_name = get_user_meta($current_user->ID,'last_name',true);      
          $full_namee = $first_name.' '.$last_name;   ?>
          <h1><?php echo $full_namee; ?></h1>
       

        <div id="card">

            <form method="post" enctype="multipart/form-data">  

                <div class="uploadmytextleft">

                    <h6>Choose Files (Not more than 10) : </h6>

                    <input type="file" id="user_system_files" name="user_system_files[]" multiple="multiple">
                   
                    <input type="hidden" name="user_system_files_submitted">

                    <span style="color: black"><?php echo isset($fileErr) ? $fileErr : ''  ?></span>
    
                    <span class="uploadmyright"><input type="submit" value="submit"></span>

                </div>

                

            </form>

            <!--          <h1>Jhon Smith files</h1>-->

            <div class="table-main">

                <table id="example" class="cell-border" style="width:100%">

                    <thead>

                        <tr>

                            <th>Serial No.</th>

                            <th>Name</th>

                            <th>File Size</th>

                            <th>File Size</th>

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

                                $file = $row->file_location.'/'.$row->file_name;

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