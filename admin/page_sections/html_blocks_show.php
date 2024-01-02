<input id="wphts_add_new_block" style="cursor: pointer; margin-bottom:10px; margin-left:8px;" type="button" name="wphts_add_new_block" value="Add New HTML Block" onClick='document.location.href="<?php echo admin_url('admin.php?page=wphts-blocksHTML&action=block-add');?>"'>
<br>
<?php
    global $wpdb;
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $limit = get_option('wphts_nb_displayed_blocks_in_admin_page');
    $offset = ($pagenum - 1) * $limit;
    $entries = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix."wphts LIMIT $offset,$limit");
?>
<table class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;">
    <thead>
        <tr>
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
                        $activate_url = admin_url('admin.php?page=wphts-blocksHTML&action=block-status&entry_id='.$entry_id.'&status=1&pageno='.$pagenum);
                    ?>
                        <td style="text-align: center;">
                            <a href='<?php echo wp_nonce_url($activate_url, 'wphts-activate_'.$entry_id); ?>'>Activate</a>
                        </td>
                    <?php 
                    } elseif ($entry->status == 1){
                        $desactivate_url = admin_url('admin.php?page=wphts-blocksHTML&action=block-status&entry_id='.$entry_id.'&status=0&pageno='.$pagenum);
                    ?>
                        <td style="text-align: center;">
                            <a href='<?php echo wp_nonce_url($desactivate_url, 'wphts-desactivate_'.$entry_id); ?>'>Desactivate</a>
                        </td>		
                    <?php 	
                    }
                ?>
                <td style="text-align: center;">
                    <a href='<?php echo admin_url('admin.php?page=wphts-blocksHTML&action=block-edit&entry_id='.$entry_id.'&pageno='.$pagenum); ?>'>Edit block</a>
                </td>
                <?php
                    $delete_url = admin_url('admin.php?page=wphts-blocksHTML&action=block-delete&entry_id='.$entry_id.'&pageno='.$pagenum);
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