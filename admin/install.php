<?php

    function wphts_install() {

        global $wpdb;
        $pluginName = 'WP-html-to-shortcode/WP-html-to-shortcode.php';

        if (is_plugin_active($pluginName)) {
            wp_die("is_plugin_active");
        }
    
        $wphts_installation_date = get_option('wphts_installation_date');
        if($wphts_installation_date == "") {
            $wphts_installation_date = time();
            update_option('wphts_installation_date', $wphts_installation_date);
        }

        $wphts_nb_displayed_blocks_in_admin_page = get_option('wphts_nb_displayed_blocks_in_admin_page');
        if($wphts_nb_displayed_blocks_in_admin_page == "") {
            $wphts_nb_displayed_blocks_in_admin_page = 500;
            update_option('wphts_nb_displayed_blocks_in_admin_page', $wphts_nb_displayed_blocks_in_admin_page);
        }
            
        $charset_collate = $wpdb->get_charset_collate();
        $queryInsertHtml = "CREATE TABLE IF NOT EXISTS  ".$wpdb->prefix."wphts (
                `id` int NOT NULL AUTO_INCREMENT,
                `title` varchar(512) NOT NULL,
                `content` longtext NOT NULL,
                `short_code` varchar(256) NOT NULL,
                `status` int NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB ".$charset_collate." AUTO_INCREMENT=1";
        $wpdb->query($queryInsertHtml);

    }

?>