<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "admin enqueue scripts"
    add_action('admin_enqueue_scripts', 'JTGH_WPHTS_add_css_and_js_scripts');

    function JTGH_WPHTS_add_css_and_js_scripts() {
        
        // Ajout du CSS
        wp_register_style('jtgh_wphts_style', plugins_url('css/style.css', JTGH_WPHTS_ROOT_FILE));
        wp_enqueue_style('jtgh_wphts_style');
                        
        // Ajout du JS 
        wp_register_script('jtgh_wphts_js_script', plugins_url('js/script.js', JTGH_WPHTS_ROOT_FILE));
        wp_enqueue_script( 'jtgh_wphts_js_script' );

    }

?>