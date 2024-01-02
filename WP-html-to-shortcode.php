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

// Activation/désactivation du plugin
require(dirname(__FILE__).'/admin/install.php');
register_activation_hook(__FILE__, 'wphts_install');
require(dirname(__FILE__).'/admin/uninstall.php');
register_uninstall_hook(__FILE__, 'wphts_uninstall' );

// Inclusion des autres fichiers de l'application
require(dirname(__FILE__).'/admin/pages_manager.php');
require(dirname(__FILE__).'/admin/menu.php');

?>