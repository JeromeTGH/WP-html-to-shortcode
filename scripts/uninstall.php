<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "uninstall hook"
    register_uninstall_hook(JTGH_WPHTS_ROOT_FILE, 'wphts_uninstall' );

    // Fonction de désinstallation
    function wphts_uninstall() {

        // Effacement des options
        delete_option(JTGH_WPHTS_OPTION_PREFIX.'activation_date');
        delete_option(JTGH_WPHTS_OPTION_PREFIX.'show_limit');
        delete_option(JTGH_WPHTS_OPTION_PREFIX.'sort_by');
        delete_option(JTGH_WPHTS_OPTION_PREFIX.'sort_direction');

        // Suppression de la table en BDD
        global $wpdb;
        $wpdb->query("DROP TABLE ".$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME);            // Efface la table en désinstallant le plugin, si souhaité

    }

?>