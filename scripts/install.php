<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "activation hook"
    register_activation_hook(JTGH_WPHTS_ROOT_FILE, 'JTGH_WPHTS_install');

    // Fonction d'installation
    function JTGH_WPHTS_install() {

        // Vérifie si le plugin est bien actif, au moment de l'installation
        $pluginName = basename(JTGH_WPHTS_ROOT_FILE, ".php").'/'.basename(JTGH_WPHTS_ROOT_FILE);    // = xxx/xxx.php       
        if (is_plugin_active($pluginName)) {
            wp_die("is_plugin_active");
        }
    
        // Enregistrement de la "date d'activation du plugin" (dans la table des options de la BDD de WP)
        $wphts_activation_date = get_option(JTGH_WPHTS_OPTION_PREFIX.'activation_date');
        if($wphts_activation_date != false)
            update_option(JTGH_WPHTS_OPTION_PREFIX.'activation_date', time());
        else
            add_option(JTGH_WPHTS_OPTION_PREFIX.'activation_date', time());

        // Enregistrement du "nbre d'éléments à afficher par page dans tableau" (dans la table des options de la BDD de WP)
        $wphts_show_limit = get_option(JTGH_WPHTS_OPTION_PREFIX.'show_limit');
        if($wphts_show_limit != false)
            update_option(JTGH_WPHTS_OPTION_PREFIX.'show_limit', 100);
        else
            add_option(JTGH_WPHTS_OPTION_PREFIX.'show_limit', 100);

        // Enregistrement de "ce sur quoi sera fait le tri, lors d'un affichage tableau" (dans la table des options de la BDD de WP)
        $wphts_sort_by = get_option(JTGH_WPHTS_OPTION_PREFIX.'sort_by');
        if($wphts_sort_by != false)
            update_option(JTGH_WPHTS_OPTION_PREFIX.'sort_by', 'id');
        else
            add_option(JTGH_WPHTS_OPTION_PREFIX.'sort_by', 'id');

        // Enregistrement du "sens de tri, lors d'un affichage tableau" (dans la table des options de la BDD de WP)
        $wphts_sort_direction = get_option(JTGH_WPHTS_OPTION_PREFIX.'sort_direction');
        if($wphts_sort_direction != false)
            update_option(JTGH_WPHTS_OPTION_PREFIX.'sort_direction', 'desc');
        else
            add_option(JTGH_WPHTS_OPTION_PREFIX.'sort_direction', 'desc');


        // Création de la table, si inexsitante
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $create_table_rqt = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME." (
            `id` INT NOT NULL AUTO_INCREMENT,
            `shortcode` VARCHAR(256) NOT NULL,
            `htmlCode` LONGTEXT NOT NULL,
            `bActif` TINYINT NOT NULL,
            PRIMARY KEY (`id`)
        ) ".$charset_collate;
        $wpdb->query($create_table_rqt);

    }

?>