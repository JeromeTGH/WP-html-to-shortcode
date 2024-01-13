<?php 

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;
	
    // Ajout du shortcode principal du plugin (les options/paramètres de ce shortcode permettra la sélection du "bon" bloc html à retourner)
    add_shortcode(JTGH_WPHTS_SHORTCODE_NAME,'JTGH_WPHTS_display_content');      // Pour activer les shortcodes du plugin
    add_filter('widget_text', 'do_shortcode');                                  // Pour activer les shortcodes dans les widgets de type texte

    // Fonction qui retourne le "bon" bloc de code HTML, en fonction de l'appel fait dans le shortcode
    function JTGH_WPHTS_display_content($shortcode_parameters) {

        // Récupération lien BDD
        global $wpdb;
        
        // Test la présence de paramètres sur cet appel à notre shortcode (nota : ici, le paramètre s'appelle aussi 'shortcode', attention à ne pas se mélanger !)
        if(is_array($shortcode_parameters) && isset($shortcode_parameters['shortcode'])) {
            
            // Récupération du shortcode appelé
            $shortcode = $shortcode_parameters['shortcode'];

            // Récupération de l'enregistrement en BDD correspondant à ce shortcode
            $resultat = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix.JTGH_WPHTS_BDD_TBL_NAME." WHERE shortcode=%s", $shortcode));
            
            if(empty($resultat)) {
                // Si shortcode inexistant
                return '';
            } else {
                // Nota : boucle ici, mais normalement, tout shortcode doit être unique (si non bypassé directement, via bdd)
                foreach ($resultat as $champs_du_resultat) {
                    if($champs_du_resultat->bActif == 1) {
                        // Si shortcode actif, alors on affiche le bloc HTML correspondant
                        return do_shortcode($champs_du_resultat->htmlCode);
                    } else {
                        // Si shortcode inactif, alors on n'affiche rien
                        return '';
                    }
                }
            }            
        }
    }

?>