<?php
/**
 * @package WP-html-to-shortcode
 * @version 1.0.0
 */
/*
Plugin Name: WP-html-to-shortcode
Plugin URI: https://github.com/JeromeTGH/WP-html-to-shortcode
Description: Permet de "transformer" des codes HTML en shortcodes, pouvant être utilisé dans les pages/articles
Author: Jérôme TOMSKI
Version: 1.0.0
Author URI: https://github.com/JeromeTGH
*/

// ============================================
// Blocage des appels directs à cette extension
// ============================================
if (!function_exists('add_action')) {
	echo 'Ce plugin ne peut être appelé directement !';
	exit;
}
