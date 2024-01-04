<?php 
    if(!defined('ABSPATH'))
	    exit;
	
    add_shortcode('wphts','wphts_display_content');		    // pour activer les shortcodes du plugin
    add_filter('widget_text', 'do_shortcode');              // pour activer les shortcodes dans les widgets de type texte

    function wphts_display_content($wphts_shortcode_parameters){
        global $wpdb;
        
        if(is_array($wphts_shortcode_parameters) && isset($wphts_shortcode_parameters['blockname'])) {
            
            $blockname = $wphts_shortcode_parameters['blockname'];
            $resultat = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."wphts WHERE title=%s", $blockname));
            
            if(empty($resultat)) {
                // Si shortcode inexistant
                return '';
            } else {
                foreach ($resultat as $champs_du_resultat) {
                    if($champs_du_resultat->status == 1) {
                        // Si 'blocHTML' actif
                        return do_shortcode($champs_du_resultat->content) ;
                    } else {
                        // Si 'blocHTML' inactif
                        return '';
                    }
                }
            }            
        }
    }

?>