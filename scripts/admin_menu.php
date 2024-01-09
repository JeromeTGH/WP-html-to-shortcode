<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "admin menu"
    add_action('admin_menu', 'JTGH_WPHTS_genere_admin_menu');

    // Fonction de génération du menu admin
    function JTGH_WPHTS_genere_admin_menu() {

        // Généralités
        $main_slug = JTGH_WPHTS_MAIN_SLUG;

        // Menu principal
        $page_title = 'WP Html To Shortcode';
        $menu_title = 'WP Html To Shortcode';
        $capability = 'manage_options';
        $menu_slug = $main_slug;
        $callback = 'JTGH_WPHTS_page_list_and_manage';
        $icon_url = JTGH_WPHTS_ROOT_DIRECTORY.'/images/logo_wp_purple_32x32.png';           // ou = plugins_url('images/logo.png', ...)); par exemple
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url);

        // Sous-menu #1
        $parent_slug = $main_slug;
        $menu_title = 'HTML blocks';
        $page_title = JTGH_WPHTS_PAGE_TITLE.$menu_title;
        $capability = 'manage_options';
        $menu_slug = $main_slug;
        $callback = 'JTGH_WPHTS_page_list_and_manage';
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback);

        // Sous-menu #2
        $parent_slug = $main_slug;
        $menu_title = 'Settings';
        $page_title = JTGH_WPHTS_PAGE_TITLE.$menu_title;
        $capability = 'manage_options';
        $menu_slug = 'jtgh-wphts-settings';
        $callback = 'JTGH_WPHTS_page_settings';
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback);

        // Sous-menu #3
        $parent_slug = $main_slug;
        $menu_title = 'About';
        $page_title = JTGH_WPHTS_PAGE_TITLE.$menu_title;
        $capability = 'manage_options';
        $menu_slug = 'jtgh-wphts-about';
        $callback = 'JTGH_WPHTS_page_about';
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback);

    }

?>