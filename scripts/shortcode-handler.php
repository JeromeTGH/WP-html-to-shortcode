<?php 

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;
	
    // Ajout du shortcode principal du plugin (les options/paramètres de ce shortcode permettra la sélection du "bon" bloc html à retourner)
    add_shortcode('wphts','JTGH_WPHTS_display_content');        // Pour activer les shortcodes du plugin
    add_filter('widget_text', 'do_shortcode');                  // Pour activer les shortcodes dans les widgets de type texte

    // Fonction qui retourne le "bon" bloc de code HTML, en fonction de l'appel fait dans le shortcode
    function JTGH_WPHTS_display_content($shortcode_parameters) {

        global $wpdb;
        
        if(is_array($shortcode_parameters) && isset($shortcode_parameters['shortcode'])) {
            
            $shortcode = $shortcode_parameters['shortcode'];
            $resultat = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME." WHERE shortcode=%s", $shortcode));
            
            if(empty($resultat)) {
                // Si shortcode inexistant
                return '';
            } else {
                foreach ($resultat as $champs_du_resultat) {
                    if($champs_du_resultat->bActif == 1) {
                        // Si 'blocHTML' actif
                        return do_shortcode($champs_du_resultat->htmlContent);
                    } else {
                        // Si 'blocHTML' inactif
                        return '';
                    }
                }
            }            
        }
    }

?>