<?php

    function wphts_install() {

        global $wpdb;
        $pluginName = 'WP-html-to-shortcode/WP-html-to-shortcode.php';

        if (is_plugin_active($pluginName)) {
            wp_die("is_plugin_active");
        }
    
        // Option : date d'activation
        $wphts_activation_date = get_option('wphts_activation_date');
        if($wphts_activation_date != false)
            update_option('wphts_activation_date', time());
        else
            add_option('wphts_activation_date', time());

        // Option : nbre d'éléments à afficher par page
        $wphts_show_limit = get_option('wphts_show_limit');
        if($wphts_show_limit != false)
            update_option('wphts_show_limit', 100);
        else
            add_option('wphts_show_limit', 100);

        // Option : tri par ... (id, par défaut)
        $wphts_sort_by = get_option('wphts_sort_by');
        if($wphts_sort_by != false)
            update_option('wphts_sort_by', 'id');
        else
            add_option('wphts_sort_by', 'id');

        // Option : tri ascendant (par défaut), ou descendant
        $wphts_sort_direction = get_option('wphts_sort_direction');
        if($wphts_sort_direction != false)
            update_option('wphts_sort_direction', 'desc');
        else
            add_option('wphts_sort_direction', 'desc');


        // Création de la table, si inexsitante
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