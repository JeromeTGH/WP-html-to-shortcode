<?php

	/*
		Plugin Name: WP-html-to-shortcode
		Plugin URI: https://github.com/JeromeTGH/WP-html-to-shortcode
		Description: Permet de "transformer" des codes HTML en shortcodes, pouvant être utilisé dans les pages/articles
		Author: Jérôme TOMSKI
		Version: 1.0.0
		Author URI: https://github.com/JeromeTGH
	*/

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

	// Définition du script de démarrage pluging
	define('JTGH_WPHTS_ROOT_FILE', __FILE__);

	// Script d'initialisation plugin
	require(dirname(JTGH_WPHTS_ROOT_FILE).'/init.php');

?>