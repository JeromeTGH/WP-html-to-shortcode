<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

	// Définition du répertoire racine du plugin
	define('JTGH_WPHTS_ROOT_DIRECTORY', dirname(JTGH_WPHTS_ROOT_FILE));



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