<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

	// Définition des constantes du plugin
	define('JTGH_WPHTS_VERSION', '2.0');
	define('JTGH_WPHTS_SHORTCODE_PROTOTYPE', '[jtgh_wphts shortcode=???]');
	define('JTGH_WPHTS_BDD_TBL_NAME', 'jtgh_wphts');
	define('JTGH_WPHTS_OPTION_PREFIX', 'jtgh_wphts_');
	define('JTGH_WPHTS_MAIN_SLUG', 'jtgh-wphts-main');
	define('JTGH_WPHTS_PAGE_TITLE', 'JTGH WPHTS - ');
	define('JTGH_WPHTS_NONCE_BASE', 'jtgh_wphts_');

	// Mise en mémoire tampon des données qui suivront (hormis headers)
	ob_start();

	// Scripts à lancer
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/install.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/uninstall.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/pages_manager.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/admin_menu.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/shortcode-handler.php');
	require(JTGH_WPHTS_ROOT_DIRECTORY.'/scripts/add-css-and-js.php');

?>