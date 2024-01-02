<?php

    function wphts_uninstall() {

        global $wpdb;
        delete_option("wphts_installation_date");
        //$wpdb->query("DROP TABLE ".$wpdb->prefix."wphts");            // Efface la table en désinstallant le plugin, si souhaité

    }

?>