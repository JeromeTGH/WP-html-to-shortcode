<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    global $wpdb;
    $_GET = stripslashes_deep($_GET);
    $_POST = stripslashes_deep($_POST);

    $blockID = intval($_GET['entry_id']);
    $app_msg = '';
    if(isset($_GET['appmsg'])){
        $app_msg = intval($_GET['appmsg']);
    }
    if($app_msg == 1) { ?>
        <div style="color: green; font-weight: bold;">HTML block successfully updated !</div> <?php
    }

    $champs_de_ce_block = [];
    if(isset($_POST) && !isset($_POST['updateBlock']) && isset($_GET['entry_id'])) {
        $resultat_lecture = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wphts WHERE id=%d LIMIT 0,1', $blockID));
        $champs_de_ce_block = $resultat_lecture[0];
    }


    if(isset($_POST) && isset($_POST['updateBlock'])) {
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wphts-update_')) {
            wp_nonce_ays('wphts-update_');
            exit;
        }

        $check_title = str_replace(' ', '', $_POST['blockTitle']);
        $check_title = str_replace('-', '', $check_title);
        $new_title = str_replace(' ', '-', $_POST['blockTitle']);
        $new_content = $_POST['blockContent'];
        
        if($new_title != "" && $new_content != "") {
            if(ctype_alnum($check_title)) {
                $doublon = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wphts WHERE id!=%d AND title=%s', $blockID, $new_title));
                
                if($doublon == 0) {
                    $new_shortcode = '[wphts blockname="'.$new_title.'"]';
                    $wpdb->update($wpdb->prefix.'wphts', array('title' => $new_title,'content' => $new_content,'short_code' => $new_shortcode), array('id' => $blockID));
                    header("Location:".admin_url('admin.php?page=wphts-blocksHTML&action=edit-block&entry_id='.$blockID.'&appmsg=1'));
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

<h2>Update an HTML Block</h2>
<div>
	<fieldset style="width: 99%; border: 1px solid #F7F7F7; padding: 10px 0px;">
		<form method="post" action="admin.php?page=wphts-blocksHTML&action=edit-block&entry_id=<?php echo $blockID; ?>">
			<?php
                wp_nonce_field('wphts-update_');
			?>
			<div>
                <table style="width: 99%; background-color: #F9F9F9; border: 1px solid #E4E4E4; border-width: 1px;margin: 0 auto; border-spacing: 1rem;">
                    <tr>
                        <td style="border-bottom: none; width:10%;">&nbsp;&nbsp;&nbsp;Block&nbsp;name&nbsp;:&nbsp;<span style="color: red">*</span></td>
                        <td>
                            <input style="width:30%;" type="text" name="blockTitle" id="blockTitle"
                            value="<?php if(isset($_POST['blockTitle'])) {echo esc_attr($_POST['blockTitle']);} else {echo esc_attr($champs_de_ce_block->title);}?>">
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: none; width:10%;">&nbsp;&nbsp;&nbsp;HTML&nbsp;code&nbsp;:&nbsp;<span style="color: red">*</span></td>
                        <td>
                            <textarea name="blockContent" style="width:90%; height:300px;"><?php if(isset($_POST['blockContent'])) {echo esc_textarea($_POST['blockContent']);} else {echo esc_attr($champs_de_ce_block->content);}?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input class="button-primary" style="cursor: pointer;" type="submit" name="updateBlock" value="Update">   
                        </td> 
                    </tr>
                </table>
			</div>
		</form>
	</fieldset>
</div>