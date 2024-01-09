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

	// Mémorisation de l'adresse du script et de son répertoire de lancement
	define('JTGH_WPHTS_ROOT_FILE', __FILE__);
	define('JTGH_WPHTS_ROOT_DIRECTORY', JTGH_WPHTS_ROOT_DIRECTORY);

	// Script d'initialisation plugin
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/init.php');

?>