<?php

    if(!defined('ABSPATH'))
	    exit;

    global $wpdb;

    $_POST = stripslashes_deep($_POST);
    $_GET = stripslashes_deep($_GET);
    $bloc_id = intval($_GET['entry_id']);
    $new_status = intval($_GET['new_status']);

    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wphts-change-status_'.$bloc_id)) {
        wp_nonce_ays('wphts-change-status_'.$bloc_id);
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
            $wpdb->update($wpdb->prefix.'wphts', array('bActif' => $new_status), array('id' => $bloc_id));
            header("Location:".admin_url('admin.php?page=wphts-blocksHTML&appmsg=2'));
            exit();
        }
    }

?>