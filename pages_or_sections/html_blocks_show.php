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
<input class="jtgh_wphts_btn_add_new_record" type="button" value="Add New HTML Block" onClick='document.location.href="<?php echo admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&action=add-block'); ?>"'>
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
                    header("Location:".admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.''));
                    exit();
                }
            }
        }
    }

    // Récupération des options
    $limit = get_option(JTGH_WPHTS_OPTION_PREFIX.'show_limit');
    $sort_by = get_option(JTGH_WPHTS_OPTION_PREFIX.'sort_by');
    $sort_direction = get_option(JTGH_WPHTS_OPTION_PREFIX.'sort_direction');

    // Récupération/calcul pagination
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $offset = ($pagenum - 1) * $limit;

    // Partie filtrage / recherche
    $search_shortcode = '';
    $search_shortcode_in_sql = '';
    if(isset($_POST['jtgh_wphts_search_shortcode'])) {
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'global_form')) {
            wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'global_form');
            exit;
        }
    }
    if(isset($_POST['jtgh_wphts_search_shortcode']) && !isset($_POST['jtgh_wphts_reset_search_btn'])) {
        $search_shortcode = sanitize_text_field($_POST['jtgh_wphts_search_shortcode']);
        $search_shortcode_in_sql = esc_sql($search_shortcode);
    }
    $entries = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME." WHERE shortcode LIKE '%".$search_shortcode_in_sql."%' ORDER BY $sort_by $sort_direction LIMIT $offset, $limit");

?>
<form action="" method="post">
    <?php wp_nonce_field(JTGH_WPHTS_NONCE_BASE.'global_form'); ?>
    <div class="jtgh_wphts_bulk_and_search_section">
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
            <div class="jtgh_wphts_search_div">
                <input type="text" id="jtgh_wphts_search_shortcode" name="jtgh_wphts_search_shortcode" value= "<?php if(isset($search_shortcode)) { echo esc_attr($search_shortcode); } ?>" placeholder="Search">
                <input type="submit" id="jtgh_wphts_submit_search_btn" name="jtgh_wphts_submit_search_btn" value="Search" />
                <input type="submit" id="jtgh_wphts_reset_search_btn" name="jtgh_wphts_reset_search_btn" value="Reset" />
            </div>	
            <div style="clear: both;"></div>
        </div>
    </div>
    <table class="widefat" class="jtgh_wphts_main_form_layout">
        <thead>
            <tr>
                <th scope="col" width="1%"><input type="checkbox" id="selectAllRows" /></th>
                <th scope="col" width="1%">ID</th>
                <th scope="col" width="15%">Block name</th>
                <th scope="col" width="40%">Shortcode</th>
                <th scope="col" width="5%">Status</th>
                <th scope="col" width="30%" class="jtgh_wphts_text_align_center" colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(!empty($entries)) {
                    // Gestion lignes alternées, dans tableau
                    $count = 1;
                    $alternateClass = '';
                    foreach($entries as $entry) {
                        $alternateClass = ( $count % 2 == 0 ) ? 'class="alternate"' : '';
                        $entry_id = intval($entry->id);
                ?>
                <tr <?php echo $alternateClass; ?>>
                    <td><input type="checkbox" class="chk" value="<?php echo intval($entry->id); ?>" name="jtgh_wphts_block_ids[]" id="jtgh_wphts_block_ids" /></td>
                    <td><?php echo intval($entry->id);?></td>
                    <td><?php echo esc_html($entry->shortcode); ?></td>
                    <td><?php 
                        if($entry->bActif == 0) {
                            echo '-';
                        } else {
                            $encoded_shortcode = str_replace("???", esc_html($entry->shortcode), JTGH_WPHTS_SHORTCODE_PROTOTYPE);
                            echo $encoded_shortcode;
                        } ?>
                    </td>
                    <td>
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
                            $activate_url = admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&action=change-status&entry_id='.$entry_id.'&new_status=1');
                        ?>
                            <td>
                                <a href="<?php echo wp_nonce_url($activate_url, JTGH_WPHTS_NONCE_BASE.'change_status'.$entry_id); ?>">
                                    <img class="jtgh_wphts_main_tbl_img2" title="Activate block" src="<?php echo plugins_url('images/toggle_off_icon_32x32.png', JTGH_WPHTS_ROOT_FILE); ?>">
                                </a>
                            </td>
                        <?php 
                        } elseif ($entry->bActif == 1){
                            $desactivate_url = admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&action=change-status&entry_id='.$entry_id.'&new_status=0');
                        ?>
                            <td>
                                <a href="<?php echo wp_nonce_url($desactivate_url, JTGH_WPHTS_NONCE_BASE.'change_status'.$entry_id); ?>">
                                    <img class="jtgh_wphts_main_tbl_img2" title="Desactivate block" src="<?php echo plugins_url('images/toggle_on_icon_32x32.png', JTGH_WPHTS_ROOT_FILE); ?>">
                                </a>
                            </td>		
                        <?php
                        }
                    ?>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&action=edit-block&entry_id='.$entry_id); ?>">
                            <img class="jtgh_wphts_main_tbl_img1" title="Edit block" src="<?php echo plugins_url('images/edit_icon_32x32.png', JTGH_WPHTS_ROOT_FILE); ?>">
                        </a>
                    </td>
                    <?php
                        $delete_url = admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&action=delete-block&entry_id='.$entry_id);
                        ?>
                        <td>
                            <a href="<?php echo wp_nonce_url($delete_url, JTGH_WPHTS_NONCE_BASE.'delete'.$entry_id); ?>" onclick="javascript: return confirm('Please click \'OK\' to confirm ');">
                                <img class="jtgh_wphts_main_tbl_img1" title="Delete block" src="<?php echo plugins_url('images/delete_icon_32x32.png', JTGH_WPHTS_ROOT_FILE); ?>">
                            </a>
                        </td>
                </tr>
                <?php
                    $count++;
                }
            } else { ?>
            <tr>
                <td colspan="6" class="jtgh_wphts_text_align_center">No HTML block found</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
<?php
    // Récupération du nombre d'enregistrement, pour calcul pagination
    $total = $wpdb->get_var('SELECT COUNT(`id`) FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME);
    $nbre_of_pages = ceil($total / $limit);

    // Construction des liens de pagination
    $page_links = paginate_links(array(
        'base' => add_query_arg('pagenum','%#%'),
        'format' => '',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'total' => $nbre_of_pages,
        'current' => $pagenum
    ) );

    // Affichage des liens de pagination, si nécessaire
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