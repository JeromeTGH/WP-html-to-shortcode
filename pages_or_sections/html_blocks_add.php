<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Si il y a quelque chose de posté ici, alors on traite
    if(isset($_POST) && isset($_POST['btnValidationFormulaire'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'add')) {
            wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'add');
            exit;
        }

        // Récupération des contenus mis à jour (nouveaux champs)
        $new_shortcode = str_replace(' ', '-', $_POST['shortcode']);
        $new_htmlcode = $_POST['codeHtml'];
        
        // Vérification du bon remplissage des champs
        if($new_shortcode != "" && $new_htmlcode != "") {

            // Vérification de format ultérieur
            $check_if_alphanumeric_characters = str_replace(' ', '', $_POST['shortcode']);
            $check_if_alphanumeric_characters = str_replace('-', '', $check_if_alphanumeric_characters);
            if(ctype_alnum($check_if_alphanumeric_characters)) {

                // On regarde si ce shortcode n'est pas déjà enregistré en BDD
                $doublon = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.' WHERE shortcode=%s', $new_shortcode));
                
                if($doublon == 0) {
                    // On insère ce nouveau code html + shortcode dans notre BDD
                    $wpdb->insert($wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME, array('shortcode' => $new_shortcode, 'htmlCode' => $new_htmlcode, 'bActif' => '1'), array('%s', '%s', '%s'));

                    // Et on revient à la page "principale"
                    header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
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

<h2>Add an HTML Block</h2>
<form method="post">
    <?php
        wp_nonce_field(JTGH_WPHTS_NONCE_BASE.'add');
    ?>
    <div class="jtgh_wphts_form_layout">
        <table class="jtgh_wphts_add_table_layout">
            <tr>
                <td class="jtgh_wphts_add_table_1stcolumn_layout">&nbsp;&nbsp;&nbsp;Shortcode&nbsp;:&nbsp;<span class="jtgh_wphts_red_color">*</span></td>
                <td>
                    <input class="jtgh_wphts_add_table_input_layout" type="text" name="shortcode" id="shortcode" placeholder="Shortcode"
                    value="<?php if(isset($_POST['shortcode'])) { echo esc_attr($_POST['shortcode']); }?>">
                </td>
            </tr>
            <tr>
                <td class="jtgh_wphts_add_table_1stcolumn_layout">&nbsp;&nbsp;&nbsp;HTML&nbsp;code&nbsp;:&nbsp;<span class="jtgh_wphts_red_color">*</span></td>
                <td>
                    <textarea name="codeHtml" class="jtgh_wphts_add_table_textarea_layout" placeholder="Your HTML code here..." ><?php
                        if(isset($_POST['codeHtml'])) { echo esc_textarea($_POST['codeHtml']); }
                    ?></textarea>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input class="button-primary" type="submit" name="btnValidationFormulaire" value="Create">   
                </td> 
            </tr>
        </table>
    </div>
</form>