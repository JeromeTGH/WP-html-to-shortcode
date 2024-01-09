<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

	// Définition des constantes du plugin
	define('JTGH_WPHTS_BDD_TBL_NAME', 'jtgh_wphts_');
	define('JTGH_WPHTS_SHORTCODE_FORMAT', '[wphts shortcut=???]');
	define('JTGH_WPHTS_VERSION', '2.0');
	define('JTGH_WPHTS_OPTION_PREFIX', 'jtgh_wphts_');

	// Mise en mémoire tampon des données qui suivront (hormis headers)
	ob_start();

	// Scripts à lancer
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/install.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/uninstall.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/pages_manager.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/menu.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/shortcode-handler.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/add-css-and-js.php');

?>