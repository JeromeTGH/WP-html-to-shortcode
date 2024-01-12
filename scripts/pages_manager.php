<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;
    
    // Gestionnaire de page "LISTING ET GESTION des blocs html"
    function JTGH_WPHTS_page_list_and_manage() {
        $page_d_accueil = true;

        if(isset($_GET['action']) && $_GET['action']=='add-block') {
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/header.php');
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/html_blocks_add.php');
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/footer.php');
            $page_d_accueil = false;
        }

        if(isset($_GET['action']) && $_GET['action']=='change-status') {
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/html_blocks_change_status.php');
            $page_d_accueil = false;
        }

        if(isset($_GET['action']) && $_GET['action']=='edit-block') {
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/header.php');
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/html_blocks_edit.php');
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/footer.php');
            $page_d_accueil = false;
        }

        if(isset($_GET['action']) && $_GET['action']=='delete-block') {
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/html_blocks_delete.php');
            $page_d_accueil = false;
        }

        if($page_d_accueil) {
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/header.php');
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/html_blocks_show.php');
            require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/footer.php');
        }
    }

    // Gestionnaire de page "PARAMÈTRES"
    function JTGH_WPHTS_page_settings() {
        echo '<h1>Settings !</h1>';
        require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/footer.php');
    }

    // Gestionnaire de page "À PROPOS"
    function JTGH_WPHTS_page_about() {
        echo '<h1>About !</h1>';
        require(JTGH_WPHTS_ROOT_DIRECTORY.'/pages_or_sections/footer.php');
    }

?>