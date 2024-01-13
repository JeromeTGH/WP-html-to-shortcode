<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_GET = stripslashes_deep($_GET);
    $_POST = stripslashes_deep($_POST);

    // Récupération du "numéro" de message à afficher, si il y a
    $app_msg = '';
    if(isset($_GET['appmsg'])) {
        $app_msg = intval($_GET['appmsg']);
    }

    // Affichage du message demandé, au besoin
    if($app_msg == 1) { ?>
        <div class="jtgh_wphts_notice_alert">→ ID not found, sorry...</div><br><?php
    }
    if($app_msg == 2) { ?>
        <div class="jtgh_wphts_notice_success">→ Status successfully changed !</div><br><?php
    }
    if($app_msg == 3) { ?>
        <div class="jtgh_wphts_notice_success">→ Record successfully deleted !</div><br><?php
    }

?>
<input class="jtgh_wphts_btn_add_new_record" type="button" value="Add New HTML Block" onClick='document.location.href="<?php echo admin_url('admin.php?page=wphts-blocksHTML&action=add-block');?>"'>
<br>
<br>
<?php
    // Gestion des actions de type "bulk" (actions groupées, donc)
    if (isset($_POST['jtgh_wphts_apply_bulk_actions'])) {
        if (isset($_POST['jtgh_wphts_bulk_actions'])) {
            $jtgh_wphts_bulk_actions = $_POST['jtgh_wphts_bulk_actions'];
            // -1 = Nothing
            // 0 = Bulk Deactivate
            // 1 = Bulk Activate
            // 2 = Bulk Delete
            if(isset($_POST['jtgh_wphts_block_ids'])) {
                $jtgh_wphts_block_ids = $_POST['jtgh_wphts_block_ids'];
                if (!empty($jtgh_wphts_block_ids)) {

                    // Passage en revue de toutes les ID sélectionnées, une par une
                    foreach ($jtgh_wphts_block_ids as $jtgh_wphts_block_id) {

                        // Vérification si numérique (si pb, on saute à l'ID suivant)
                        if($jtgh_wphts_block_id == "" || !is_numeric($jtgh_wphts_block_id))
                            continue;

                        // Si option "Bulk deactivate" choisie : mise à jour du status (passage à 0 "forcé")
                        if ($jtgh_wphts_bulk_actions == 0)
                            $wpdb->update($wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME, array('bActif' => 0), array('id' => $jtgh_wphts_block_id));

                        // Si option "Bulk activate" choisie : mise à jour du status (passage à 1 "forcé")
                        if ($jtgh_wphts_bulk_actions == 1)
                            $wpdb->update($wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME, array('bActif' => 1), array('id' => $jtgh_wphts_block_id));

                        // Si option "Bulk delete" choisie : effacement de l'enregistrement
                        if ($jtgh_wphts_bulk_actions == 2) {
                            $wpdb->query($wpdb->prepare('DELETE FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.' WHERE id=%d', $jtgh_wphts_block_id));
                            // À désactiver par sécurité, si souhaité
                        }

                    }

                    // Et retour à la "page d'accueil admin", une fois toutes les ID parcourues
                    header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
                    exit();
                }
            }
        }
    }

    // Récupération des options
    $limit = get_option('wphts_show_limit');
    $sort_by = get_option('wphts_sort_by');
    $sort_direction = get_option('wphts_sort_direction');

    // Récupération/calcul pagination
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $offset = ($pagenum - 1) * $limit;





    
    $search_txt = '';
    $search_txt_for_sql = '';
    if(isset($_POST['wphts_search_txt'])) {
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'global_form')) {
            wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'global_form');
            exit;
        }
    }
    if(isset($_POST['wphts_search_txt']) && !isset($_POST['wphts_reset_search_btn'])) {
        $search_txt = sanitize_text_field($_POST['wphts_search_txt']);
        $search_txt_for_sql = esc_sql($search_txt);
    }
    
    $entries = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME." WHERE title LIKE '%".$search_txt_for_sql."%' ORDER BY $sort_by $sort_direction LIMIT $offset, $limit");
?>
<form action="" method="post">
    <?php wp_nonce_field(JTGH_WPHTS_NONCE_BASE.'global_form');?>
    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
        <div>
            <span>With Selected : </span>
            <select name="jtgh_wphts_bulk_actions" id="jtgh_wphts_bulk_actions">
                <option value="-1">Bulk Actions</option>
                <option value="0">Deactivate</option>
                <option value="1">Activate</option>
                <option value="2">Delete</option>
            </select>
            <input type="submit" name="jtgh_wphts_apply_bulk_actions" value="Apply">		
        </div>
        <div>
            <div style="float: right; padding: 0.4rem;">
                <input type="text" id="wphts_search_txt" name="wphts_search_txt" value= "<?php if(isset($search_txt)) {echo esc_attr($search_txt);}?>" placeholder="Search">
                <input type="submit" id="wphts_submit_search_btn" name="wphts_submit_search_btn" value="Search" />
                <input type="submit" id="wphts_reset_search_btn" name="wphts_reset_search_btn" value="Reset" />
            </div>	
            <div style="clear: both;"></div>
        </div>
    </div>
    <table class="widefat" style="width: 99%; margin: 0 auto;">
        <thead>
            <tr>
                <th scope="col" style="width: 1%;"><input type="checkbox" id="selectAllRows" /></th>
                <th scope="col" style="width: 1%;">ID</th>
                <th scope="col" style="width: 15%;">Block name</th>
                <th scope="col" style="width: 40%;">Shortcode</th>
                <th scope="col" style="width: 5%;">Status</th>
                <th scope="col" style="width: 30%; text-align: center;" colspan="3">Action</th>
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
                        <input type="checkbox" class="chk" value="<?php echo intval($entry->id); ?>" name="jtgh_wphts_block_ids[]" id="jtgh_wphts_block_ids" />
                    </td>
                    <td style="vertical-align: middle!important; text-align: right;"><?php echo intval($entry->id);?></td>
                    <td style="vertical-align: middle!important;"><?php echo esc_html($entry->title);?></td>
                    <td style="vertical-align: middle!important;"><?php 
                        if($entry->bActif == 0) {
                            echo '-';
                        } else {
                            echo '[wphts blockname="'.esc_html($entry->title).'"]';
                        }?>
                    </td>
                    <td style="vertical-align: middle!important;">
                        <?php 
                            if($entry->bActif == 0) {
                                echo "Inactive";
                            } elseif ($entry->bActif == 1) {
                                echo "Active";	
                            }
                        ?>
                    </td>
                    <?php 
                        if($entry->bActif == 0) {
                            $activate_url = admin_url('admin.php?page=wphts-blocksHTML&action=change-status&entry_id='.$entry_id.'&new_status=1');
                        ?>
                            <td style="vertical-align: middle!important; text-align: center;">
                                <a href='<?php echo wp_nonce_url($activate_url, JTGH_WPHTS_NONCE_BASE.'change_status'.$entry_id); ?>'>
                                    <img class="jtgh_wphts_main_tbl_img2" title="Activate block" src="<?php echo plugins_url('images/off_icon_32x32.png', WPHTS_ROOT_PLUGIN_FILE)?>">
                                </a>
                            </td>
                        <?php 
                        } elseif ($entry->bActif == 1){
                            $desactivate_url = admin_url('admin.php?page=wphts-blocksHTML&action=change-status&entry_id='.$entry_id.'&new_status=0');
                        ?>
                            <td style="vertical-align: middle!important; text-align: center;">
                                <a href='<?php echo wp_nonce_url($desactivate_url, JTGH_WPHTS_NONCE_BASE.'change_status'.$entry_id); ?>'>
                                    <img class="jtgh_wphts_main_tbl_img2" title="Desactivate block" src="<?php echo plugins_url('images/on_icon_32x32.png', WPHTS_ROOT_PLUGIN_FILE)?>">
                                </a>
                            </td>		
                        <?php 	
                        }
                    ?>
                    <td style="vertical-align: middle!important; text-align: center;">
                        <a href='<?php echo admin_url('admin.php?page=wphts-blocksHTML&action=edit-block&entry_id='.$entry_id); ?>'>
                            <img class="jtgh_wphts_main_tbl_img1" title="Edit block" src="<?php echo plugins_url('images/edit_icon_32x32.png', WPHTS_ROOT_PLUGIN_FILE)?>">
                        </a>
                    </td>
                    <?php
                        $delete_url = admin_url('admin.php?page=wphts-blocksHTML&action=delete-block&entry_id='.$entry_id);
                        ?>
                        <td style="vertical-align: middle!important; text-align: center;" >
                            <a href='<?php echo wp_nonce_url($delete_url, JTGH_WPHTS_NONCE_BASE.'delete'.$entry_id); ?>' onclick="javascript: return confirm('Please click \'OK\' to confirm ');">
                                <img class="jtgh_wphts_main_tbl_img1" title="Delete block" src="<?php echo plugins_url('images/delete_icon_32x32.png', WPHTS_ROOT_PLUGIN_FILE)?>">
                            </a>
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
    $total = $wpdb->get_var( "SELECT COUNT(`id`) FROM ".$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.);
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