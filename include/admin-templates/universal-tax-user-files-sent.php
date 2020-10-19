<?php
if (isset($_GET['file-delete'])) {
    if (!empty($_GET['file-id'])) {
        $file_id = $_GET['file-id'];
        global $wpdb;
        $table       = 'user_system_files'; //$wpdb->prefix.'univarsal_tax_files';
        // Using where formatting.
        $file_status = $wpdb->delete($table, array(
            'id' => intval($file_id)
        ), array(
            '%d'
        ));
        if ($file_status != false) {
            if (!empty($_GET['file-name-delete'])) {
                $file           = $_GET['file-name-delete'];
                $file_to_delete = ABSPATH . 'wp-content/uploads/user_system_admin' . $file; // file to be downloaded
                if (!unlink($file_to_delete)) {
                    echo ("Error deleting $file");
                } else {
                    $page    = $_GET['page'];
                    $user_id = $_GET['user_id'];
                    wp_redirect(admin_url('admin.php?page=' . $page . '&user_id=' . $user_id));
                    exit();
                }
            }
        }
    }
}
?>
<div class="container-fluid">
   <div class="row">
      <?php
if (isset($_GET['user_id'])) {
    $user = get_user_by('ID', $_GET['user_id']);
}
?>
      <div class="col">
         <h2>Sent Files to <?php
echo isset($_GET['user_id']) ? $user->data->user_login : '';
?></h2>
      </div>
      <div class="col">
         <a style="text-decoration: none;" class="btn btn-primary" href="<?php
echo home_url('/wp-admin/admin.php?page=user-system-user&user_id=') . $_GET['user_id'];
?>">BACK</a>
      </div>
      <div class="col"></div>
   </div>
   <div class="row">
      <?php
global $wpdb;
$table          = 'user_system_files'; //$wpdb->prefix.'yuliza_files';
$query          = "SELECT * from " . $table . " where sender_id =1001 and receiver_id =" . $user->ID . " and status=1";
$total_query    = "SELECT COUNT(*) FROM " . $table . " where sender_id =1001 and receiver_id =" . $user->ID . " and status=1";
$total          = $wpdb->get_var($total_query);
$items_per_page = 10;
$page           = isset($_GET['user-files']) ? abs((int) $_GET['user-files']) : 1;
$offset         = ($page * $items_per_page) - $items_per_page;
$result         = $wpdb->get_results($query . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}");
$totalPage      = ceil($total / $items_per_page);
if ($total != null && $total > 0) {
?>
      <div class="col">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">File Name</th>
                  <th scope="col">File Size</th>
                  <th scope="col">Download File</th>
                  <th scope="col">Delete File</th>
               </tr>
            </thead>
            <tbody>
               <?php
    foreach ($result as $row) {
        $file_status = $row->status;
        if ($file_status == 0) {
            $fstatus = 'table-success';
        } elseif ($file_status == 1) {
            $fstatus = 'table-active';
        } elseif ($file_status == 2) {
            $fstatus = 'table-danger';
        }
?>
               <tr class="<?php
        echo $fstatus;
?>">
                  <th scope="row"><?php
        echo $offset + 1;
        $offset++;
?></th>
                  <td><?php
        echo $row->file_name;
?></td>
                  <td><?php
        echo $row->file_size;
?> <b>bytes</b></td>
                  <td>
                     <?php
        $file = $row->file_location . '/' . $row->file_name;
?>
                     <?php
        $file             = $row->file_name;
        $file_to_download = 'wp-content/uploads/user_system_admin/' . $file;
?>
                     <a class="btn btn-info" href="<?php
        echo home_url('/' . $file_to_download);
?>" download>
                     DOWNLOAD
                     </a>
                  </td>
                  <td>
                     <?php
        $file = $row->file_location . '/' . $row->file_name;
?>
                     <form>
                        <input type="hidden" name="page" value="user-system-files-sent">
                        <input type="hidden" name="user_id" value="<?php
        echo $user->ID;
?>">
                        <input type="hidden" name="file-name-delete" value="<?php
        echo $file;
?>">
                        <input type="hidden" name="file-id" value="<?php
        echo $row->id;
?>" >
                        <button name="file-delete" type="submit" class="btn btn-danger">Delete</button>
                     </form>
                  </td>
               </tr>
               <?php
    }
?>
            </tbody>
            <tfoot>
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">File Name</th>
                  <th scope="col">File Size</th>
                  <th scope="col">Download File</th>
                  <th scope="col">Delete File</th>
               </tr>
            </tfoot>
         </table>
         <?php
    if ($totalPage > 1) {
        $customPagHTML = '<div><span>Page ' . $page . ' of ' . $totalPage . '</span> &nbsp;&nbsp;&nbsp;' . paginate_links(array(
            'base' => add_query_arg('user-files', '%#%'),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => $totalPage,
            'current' => $page
        )) . '</div>';
    }
    echo $customPagHTML;
?>
      </div>
      <?php
} else {
?>
      <div class="col">
         <div class="alert alert-dismissible alert-warning">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4 class="alert-heading">Warning!</h4>
            <p class="mb-0">Files are not available with this user.</p>
         </div>
      </div>
      <?php
}
?>
   </div>
   <br>
</div>