<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    global $wpdb;

    $_POST = stripslashes_deep($_POST);
    $_GET = stripslashes_deep($_GET);
    $bloc_id = intval($_GET['entry_id']);

    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'delete'.$bloc_id)) {
        wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'delete'.$bloc_id);
        exit;
    } else {
        if($bloc_id == "" || !is_numeric($bloc_id)){
            header("Location:".admin_url('admin.php?page=wphts-blocksHTML'));
            exit();
        }
        $resultat = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wphts WHERE id=%d LIMIT 0,1', $bloc_id)) ;
        if($resultat == 0){
            header("Location:".admin_url('admin.php?page=wphts-blocksHTML&appmsg=1'));
            exit();
        } else {
            $wpdb->query($wpdb->prepare('DELETE FROM '.$wpdb->prefix.'wphts WHERE id=%d', $bloc_id));
            header("Location:".admin_url('admin.php?page=wphts-blocksHTML&appmsg=3'));
            exit();
        }
    }

?>