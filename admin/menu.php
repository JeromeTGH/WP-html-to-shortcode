<?php

    add_action('admin_menu', 'WPhts_menu');

    function WPhts_menu() {

        // Généralités
        $main_slug = 'wphts-blocksHTML';

        // Menu principal
        $page_title = 'WP Html To Shortcode';
        $menu_title = 'WP Html To Shortcode';
        $capability = 'manage_options';
        $menu_slug = $main_slug;
        $callback = 'wphts_HTML_blocks';
        $icon_url = '';                                 // ou = plugins_url('images/logo.png', ...)); par exemple
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url);

        // 1er sous-menu
        $parent_slug = $main_slug;
        $page_title = 'WPHTS - HTML blocks';
        $menu_title = 'HTML blocks';
        $capability = 'manage_options';
        $menu_slug = $main_slug;
        $callback = 'wphts_HTML_blocks';
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback);

        // 2ème sous-menu
        $parent_slug = $main_slug;
        $page_title = 'WPHTS - Settings';
        $menu_title = 'Settings';
        $capability = 'manage_options';
        $menu_slug = 'wphts-settings';
        $callback = 'wphts_settings';
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback);

        // 3ème sous-menu
        $parent_slug = $main_slug;
        $page_title = 'WPHTS - About';
        $menu_title = 'About';
        $capability = 'manage_options';
        $menu_slug = 'wphts-about';
        $callback = 'wphts_about';
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback);

    }

?>