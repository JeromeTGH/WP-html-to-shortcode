<?php

    function wphts_add_css_and_js_scripts() {
        
        // Ajout du CSS
        wp_register_style('wphts_style', plugins_url('css/wphts.css', WPHTS_ROOT_PLUGIN_FILE));
        wp_enqueue_style('wphts_style');
                        
        // Ajout du JS 
        wp_register_script('wphts_js_script', plugins_url('js/script.js', WPHTS_ROOT_PLUGIN_FILE));
        wp_enqueue_script( 'wphts_js_script' );

    }

    add_action('admin_enqueue_scripts', 'wphts_add_css_and_js_scripts');

?>