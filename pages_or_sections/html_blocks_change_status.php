<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_GET = stripslashes_deep($_GET);

    // On sort si jamais certaines données sont manquantes
    if(!isset($_GET['entry_id']) || !isset($_GET['new_status'])) {
        ?>
            <div class="jtgh_wphts_notice_alert">Data missing, sorry...</div>
        <?php	
        exit;
    }

    // Récupération des données transmises dans l'URL
    $bloc_id = intval($_GET['entry_id']);
    $new_status = intval($_GET['new_status']);

                        // echo 'Bloc ID='.$bloc_id;
                        // echo 'New status='.$new_status;
                        // exit();

    // Vérification du NONCE
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHTS_NONCE_BASE.'change_status'.$bloc_id)) {
        wp_nonce_ays(JTGH_WPHTS_NONCE_BASE.'change_status'.$bloc_id);
        exit;
    } else {
        // Vérifie que l'ID est bien numérique (sinon, retour à la page "principale")
        if($bloc_id == "" || !is_numeric($bloc_id)) {
            header("Location:".admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.''));
            exit();
        }

        // Récupère les données relatives à cet ID, en base de données
        $resultat = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME.' WHERE id=%d', $bloc_id)) ;

        if($resultat == 0) {
            // Si aucun résultat trouvé avec cet ID, alors on retourne à la page principale (avec "message 1" en retour demandé)
            header("Location:".admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&appmsg=1'));
            exit();
        } else {
            // Si ID bien trouvé en BDD, alors mise à jour de son status
            $wpdb->update($wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME, array('bActif' => $new_status), array('id' => $bloc_id));
            // Et retour à la page principale (avec "message 2" en retour demandé)
            header("Location:".admin_url('admin.php?page='.JTGH_WPHTS_MAIN_SLUG.'&appmsg=2'));
            exit();
        }
    }

?>