<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des données qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Si il y a quelque chose de posté ici, alors on traite
    if(isset($_POST) && isset($_POST['btnValidationFormulaire'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'add')) {
            wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'wphts-add');
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
                $doublon = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.' WHERE title=%s', $new_shortcode));
                
                if($doublon == 0) {
                    $new_shortcode = '[wphts blockname="'.$new_shortcode.'"]';
                    $wpdb->insert($wpdb->prefix.'wphts', array('title' => $new_shortcode, 'content' => $new_htmlcode, 'short_code' => $new_shortcode, 'bActif' => '1'), array('%s','%s','%s','%d'));
                    header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
                } else {
                    ?>
                    <div style="color: red; font-weight: bold;">This HTML title already exists, sorry...</div>
                    <?php	
                }
            } else {
                ?>
                <div style="color: red; font-weight: bold;">HTML title should have only alphanumerics characters, sorry...</div>
                <?php
            }
        } else {
            ?>		
            <div style="color: red; font-weight: bold;">Fill all mandatory fields, please !</div>
            <?php 
        }
    }
?>

<h2>Add an HTML Block</h2>
<div>
	<fieldset style="width: 99%; border: 1px solid #F7F7F7; padding: 10px 0px;">
		<form method="post">
			<?php
                wp_nonce_field(JTGH_WPHTS_NONCE_BASE.'add');
			?>
			<div>
                <table style="width: 99%; background-color: #F9F9F9; border: 1px solid #E4E4E4; border-width: 1px;margin: 0 auto; border-spacing: 1rem;">
                    <tr>
                        <td style="border-bottom: none; width:10%;">&nbsp;&nbsp;&nbsp;Shortcode&nbsp;:&nbsp;<span style="color: red">*</span></td>
                        <td>
                            <input style="width:30%;" type="text" name="shortcode" id="shortcode" placeholder="Shortcode"
                            value="<?php if(isset($_POST['shortcode'])) {echo esc_attr($_POST['shortcode']);}?>">
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: none; width:10%;">&nbsp;&nbsp;&nbsp;HTML&nbsp;code&nbsp;:&nbsp;<span style="color: red">*</span></td>
                        <td>
                            <textarea name="codeHtml" style="width:90%; height:300px;" placeholder="Your HTML code here..." ><?php if(isset($_POST['codeHtml'])) {echo esc_textarea($_POST['codeHtml']);}?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input class="button-primary" style="cursor: pointer;" type="submit" name="btnValidationFormulaire" value="Create">   
                        </td> 
                    </tr>
                </table>
			</div>
		</form>
	</fieldset>
</div>