<?php

    function wphts_HTML_blocks() {
        $page_d_accueil = true;

        if(isset($_GET['action']) && $_GET['action']=='block-add') {
            require(dirname(__FILE__).'/page_sections/header.php');
            require(dirname(__FILE__).'/page_sections/html_blocks_add.php');
            require(dirname(__FILE__).'/page_sections/footer.php');
            $page_d_accueil = false;
        }

        if(isset($_GET['action']) && $_GET['action']=='change-status') {
            require(dirname(__FILE__).'/page_sections/html_blocks_change_status.php');
            $page_d_accueil = false;
        }

        if($page_d_accueil) {
            require(dirname(__FILE__).'/page_sections/header.php');
            require(dirname(__FILE__).'/page_sections/html_blocks_show.php');
            require(dirname(__FILE__).'/page_sections/footer.php');
        }
    }

    function wphts_settings() {
        echo '<h1>Settings !</h1>';
    }

    function wphts_about() {
        echo '<h1>About !</h1>';
    }

?>