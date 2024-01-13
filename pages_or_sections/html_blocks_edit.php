<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_GET = stripslashes_deep($_GET);
    $_POST = stripslashes_deep($_POST);

    // On sort si jamais l'ID est manquant dans l'URL
    if(!isset($_GET['entry_id'])) {
        ?>
            <div class="jtgh_wphts_notice_alert">Data missing, sorry...</div>
        <?php	
        exit;
    }

    // Récupération de l'ID dans l'URL
    $blockID = intval($_GET['entry_id']);

    // Récupération du "numéro" de message à afficher, si il y a
    $app_msg = '';
    if(isset($_GET['appmsg'])){
        $app_msg = intval($_GET['appmsg']);
    }
    if($app_msg == 1) { ?>
        <div class="jtgh_wphts_notice_success">HTML block successfully updated !</div> <?php
    }

    // Récupération des valeurs AVANT modification, si formulaire de modif non encore transmis    
    $champs_de_ce_block = [];
    if(isset($_POST) && !isset($_POST['btnValidationFormulaire']) && isset($_GET['entry_id'])) {
        $resultat_lecture = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.' WHERE id=%d LIMIT 0,1', $blockID));
        $champs_de_ce_block = $resultat_lecture[0];
    }

    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnValidationFormulaire'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'update'.$blockID)) {
            wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'update'.$blockID);
            exit;
        }

        // On sort si jamais certains champs sont manquants
        if(!isset($_POST['shortcode']) || !isset($_POST['codeHtml'])) {
            ?>
                <div class="jtgh_wphts_notice_alert">Form data missing, sorry...</div>
            <?php	
            exit;
        }

        // Récupération des données postées
        $new_shortcode = str_replace(' ', '-', $_POST['shortcode']);
        $new_htmlcode = $_POST['codeHtml'];
        
        // Vérification qu'aucune donnée ne soit vide
        if($new_shortcode != "" && $new_htmlcode != "") {

            // Vérification de format de shortcode
            $check_title = str_replace(' ', '', $_POST['shortcode']);
            $check_title = str_replace('-', '', $check_title);
            if(ctype_alnum($check_title)) {

                // On regarde si ce shortcode n'est pas déjà enregistré en BDD
                $doublon = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.' WHERE id!=%d AND shortcode=%s', $blockID, $new_shortcode));
                
                if($doublon == 0) {
                    // On met à jour notre enregistrement en BDD, avec les nouveaux shortcode/codeHtml
                    $wpdb->update($wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME, array('shortcode' => $new_shortcode,'htmlCode' => $new_htmlcode, array('id' => $blockID)));

                    // Et on revient à la page "principale", avec le code message '1' à faire afficher
                    header("Location:".admin_url('admin.php?page=wphts-blocksHTML&action=edit-block&entry_id='.$blockID.'&appmsg=1'));
                } else {
                    ?>
                    <div class="jtgh_wphts_notice_alert">This shortcode already exists, sorry...</div>
                    <?php	
                }
            } else {
                ?>
                <div class="jtgh_wphts_notice_alert">The shortcode should have only alphanumerics characters, sorry...</div>
                <?php
            }
        } else {
            ?>		
            <div class="jtgh_wphts_notice_alert">Fill all mandatory fields, please !</div>
            <?php 
        }
    }
?>

<h2>Update an HTML Block</h2>
<form method="post" action="admin.php?page=wphts-blocksHTML&action=edit-block&entry_id=<?php echo $blockID; ?>">
    <?php
        wp_nonce_field(JTGH_WPHTS_NONCE_BASE.'update'.$blockID);
    ?>
    <div class="jtgh_wphts_form_layout">
        <table class="jtgh_wphts_edit_table_layout">
            <tr>
                <td class="jtgh_wphts_edit_table_1stcolumn_layout">&nbsp;&nbsp;&nbsp;Shortcode&nbsp;:&nbsp;<span class="jtgh_wphts_red_color">*</span></td>
                <td>
                    <input class="jtgh_wphts_edit_table_input_layout" type="text" name="shortcode" id="shortcode"
                    value="<?php if(isset($_POST['shortcode'])) { echo esc_attr($_POST['shortcode']); } else { echo esc_attr($champs_de_ce_block->shortcode); }?>">
                </td>
            </tr>
            <tr>
                <td class="jtgh_wphts_edit_table_1stcolumn_layout">&nbsp;&nbsp;&nbsp;HTML&nbsp;code&nbsp;:&nbsp;<span class="jtgh_wphts_red_color">*</span></td>
                <td>
                    <textarea name="codeHtml" class="jtgh_wphts_add_table_textarea_layout"><?php if(isset($_POST['codeHtml'])) { echo esc_textarea($_POST['codeHtml']); } else { echo esc_attr($champs_de_ce_block->htmlCode); }?></textarea>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input class="button-primary" type="submit" name="btnValidationFormulaire" value="Update">   
                </td> 
            </tr>
        </table>
    </div>
</form>