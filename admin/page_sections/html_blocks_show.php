<input id="wphts_add_new_block" style="cursor: pointer; margin-bottom:10px; margin-left:8px;" type="button" name="wphts_add_new_block" value="Add New HTML Block" onClick='document.location.href="<?php echo admin_url('admin.php?page=wphts-blocksHTML&action=block-add');?>"'>
<br>
<br>
<?php
    global $wpdb;

    if (isset($_POST['wphts_apply_bulk_actions'])) {
        if (isset($_POST['wphts_bulk_actions'])) {
            $wphts_bulk_actions=$_POST['wphts_bulk_actions'];
            // -1 = Nothing
            // 0 = Bulk Deactivate
            // 1 = Bulk Activate
            // 2 = Bulk Delete
            if(isset($_POST['wphts_block_ids'])) {
                $wphts_block_ids = $_POST['wphts_block_ids'];
                if (!empty($wphts_block_ids)) {
                    // Bulk "deactivate"
                    if ($wphts_bulk_actions == 0) {
                        foreach ($wphts_block_ids as $wphts_block_id)
                            $wpdb->update($wpdb->prefix.'wphts', array('status' => 0), array('id' => $wphts_block_id));
                        header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
                        exit();
                    }
                    // Bulk "activate"
                    if ($wphts_bulk_actions == 1) {
                        foreach ($wphts_block_ids as $wphts_block_id)
                            $wpdb->update($wpdb->prefix.'wphts', array('status' => 1), array('id' => $wphts_block_id));
                        header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
                        exit();
                    }
                    // Bulk "delete"
                    if ($wphts_bulk_actions == 2) {
                        // foreach ($wphts_block_ids as $wphts_block_id)
                        //     $wpdb->query($wpdb->prepare('DELETE FROM '.$wpdb->prefix.'wphts WHERE id=%d', $wphts_block_id));
                                    // Désactivé par sécurité, pour l'instant
                        header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
                        exit();
                    }
                }
            }
        }
    }

    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $limit = get_option('wphts_show_limit');
    $offset = ($pagenum - 1) * $limit;

    $sort_by = get_option('wphts_sort_by');
    $sort_direction = get_option('wphts_sort_direction');

    $search_txt = '';
    $search_txt_for_sql = '';
    if(isset($_POST['wphts_submit_search_btn']) || isset($_POST['wphts_reset_search_btn'])) {
        if(!isset($_REQUEST['_wpnonce'])||!wp_verify_nonce($_REQUEST['_wpnonce'],'wphts_global_form') ){
            wp_nonce_ays( 'wphts_global_form' );
            exit;
        }
        if(isset($_POST['wphts_search_txt']) && !isset($_POST['wphts_reset_search_btn'])) {
            $search_txt = sanitize_text_field($_POST['wphts_search_txt']);
            $search_txt_for_sql = esc_sql($search_txt);
        }
    }

    $entries = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix."wphts WHERE title LIKE '%".$search_txt_for_sql."%' ORDER BY $sort_by $sort_direction LIMIT $offset, $limit");
?>
<form action="" method="post">
    <?php wp_nonce_field('wphts_global_form');?>
    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
        <!-- <form method="post" style="padding-left: 0.5rem;"> -->
        <div>
            <span>With Selected : </span>
            <select name="wphts_bulk_actions" id="wphts_bulk_actions">
                <option value="-1">Bulk Actions</option>
                <option value="0">Deactivate</option>
                <option value="1">Activate</option>
                <option value="2">Delete</option>
            </select>
            <input type="submit" name="wphts_apply_bulk_actions" value="Apply">		
        <!-- </form> -->
        </div>
        <div>
        <!-- <form name="blocks_filter" action="" method="post"> -->
            <div style="float: right; padding: 0.4rem;">
                <input type="text" name="wphts_search_txt" value= "<?php if(isset($search_txt)) {echo esc_attr($search_txt);}?>" placeholder="Search">
                <input type="submit" name="wphts_submit_search_btn" value="Search" />
                <input type="submit" name="wphts_reset_search_btn" value="Reset" />
            </div>	
            <div style="clear: both;"></div>
        <!-- </form> -->
        </div>
    </div>
    <table class="widefat" style="width: 99%; margin: 0 auto;">
        <thead>
            <tr>
                <th scope="col"><input type="checkbox" id="selectAllRows" /></th>
                <th scope="col">ID</th>
                <th scope="col">Block name</th>
                <th scope="col">Shortcode</th>
                <th scope="col">Status</th>
                <th scope="col" colspan="3" style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(!empty($entries)) {
                    $count = 1;
                    $class = '';
                    foreach($entries as $entry) {
                        $class = ( $count % 2 == 0 ) ? ' class="alternate"' : '';
                        $entry_id=intval($entry->id);
                ?>
                <tr <?php echo $class; ?>>
                    <td style="vertical-align: middle!important; padding-left: 18px;">
                        <input type="checkbox" class="chk" value="<?php echo intval($entry->id); ?>" name="wphts_block_ids[]" id="wphts_block_ids" />
                    </td>
                    <td><?php echo intval($entry->id);?></td>
                    <td><?php echo esc_html($entry->title);?></td>
                    <td><?php 
                        if($entry->status == 0) {
                            echo '(inactive)';
                        } else {
                            echo '[wphts blockname="'.esc_html($entry->title).'"]';
                        }?>
                    </td>
                    <td>
                        <?php 
                            if($entry->status == 0) {
                                echo "Inactive";
                            } elseif ($entry->status == 1) {
                                echo "Active";	
                            }
                        ?>
                    </td>
                    <?php 
                        if($entry->status == 0) {
                            $activate_url = admin_url('admin.php?page=wphts-blocksHTML&action=change-status&entry_id='.$entry_id.'&new_status=1');
                        ?>
                            <td style="text-align: center;">
                                <a href='<?php echo wp_nonce_url($activate_url, 'wphts-change-status_'.$entry_id); ?>'>Activate</a>
                            </td>
                        <?php 
                        } elseif ($entry->status == 1){
                            $desactivate_url = admin_url('admin.php?page=wphts-blocksHTML&action=change-status&entry_id='.$entry_id.'&new_status=0');
                        ?>
                            <td style="text-align: center;">
                                <a href='<?php echo wp_nonce_url($desactivate_url, 'wphts-change-status_'.$entry_id); ?>'>Desactivate</a>
                            </td>		
                        <?php 	
                        }
                    ?>
                    <td style="text-align: center;">
                        <a href='<?php echo admin_url('admin.php?page=wphts-blocksHTML&action=edit-block&entry_id='.$entry_id); ?>'>Edit block</a>
                    </td>
                    <?php
                        $delete_url = admin_url('admin.php?page=wphts-blocksHTML&action=delete-block&entry_id='.$entry_id);
                        ?>
                        <td style="text-align: center;" >
                            <a href='<?php echo wp_nonce_url($delete_url, 'wphts-delete_'.$entry_id); ?>' onclick="javascript: return confirm('Please click \'OK\' to confirm ');">Delete block</a>
                        </td>
                </tr>
                <?php
                    $count++;
                }
            } else { ?>
            <tr>
                <td colspan="6" >No HTML block found</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
<?php
    $total = $wpdb->get_var( "SELECT COUNT(`id`) FROM ".$wpdb->prefix."wphts" );
    $num_of_pages = ceil($total / $limit);

    $page_links = paginate_links(array(
            'base' => add_query_arg('pagenum','%#%'),
            'format' => '',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'total' => $num_of_pages,
            'current' => $pagenum
    ) );

    if ($page_links) {
        echo '<div style="text-align: right; padding: 0.4rem; font-size: 1rem;">'.$page_links.'</div>';
    }
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#selectAllRows").click(function() {
            jQuery(".chk").prop("checked",jQuery("#selectAllRows").prop("checked"));
        }); 
    });
</script>