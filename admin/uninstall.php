<?php

    register_uninstall_hook(JTGH_WPHTS_ROOT_FILE, 'wphts_uninstall' );

    function wphts_uninstall() {

        global $wpdb;
        delete_option("wphts_installation_date");
        delete_option("wphts_nb_displayed_blocks_in_admin_page");
        //$wpdb->query("DROP TABLE ".$wpdb->prefix."wphts");            // Efface la table en désinstallant le plugin, si souhaité

    }

?>