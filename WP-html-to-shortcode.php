<?php

	/*
		Plugin Name: WP-html-to-shortcode
		Plugin URI: https://github.com/JeromeTGH/WP-html-to-shortcode
		Description: Permet de "transformer" des codes HTML en shortcodes, pouvant être utilisé dans les pages/articles
		Author: Jérôme TOMSKI
		Version: 1.0.0
		Author URI: https://github.com/JeromeTGH
	*/

	if (!defined('ABSPATH'))
		exit;

	ob_start();

	define('WPHTS_ROOT_PLUGIN_FILE', __FILE__);

	// Activation/désactivation du plugin
	require(dirname(WPHTS_ROOT_PLUGIN_FILE).'/admin/install.php');
	register_activation_hook(WPHTS_ROOT_PLUGIN_FILE, 'wphts_install');
	require(dirname(WPHTS_ROOT_PLUGIN_FILE).'/admin/uninstall.php');
	register_uninstall_hook(WPHTS_ROOT_PLUGIN_FILE, 'wphts_uninstall' );

	// Inclusion des autres fichiers de l'application à lancer
	require(dirname(WPHTS_ROOT_PLUGIN_FILE).'/admin/pages_manager.php');
	require(dirname(WPHTS_ROOT_PLUGIN_FILE).'/admin/menu.php');
	require(dirname(WPHTS_ROOT_PLUGIN_FILE).'/shortcode-handler.php');
	require(dirname(WPHTS_ROOT_PLUGIN_FILE).'/add-css-and-js.php');

?>