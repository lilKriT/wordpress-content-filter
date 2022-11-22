<?php

/*
    Plugin Name: Content Filter
    Description: Finds and replaces a provided list of words.
    Version: 1.0
    Author: lilKriT
    Author URI: https://lilKriT.dev
*/

if (!defined('ABSPATH')) exit;  // Don't allow to access directly.

class ContentFilter
{
    function __construct()
    {
        add_action("admin_menu", array($this, "addMenu"));
    }

    function addMenu()
    {
        // Document title, admin sidebar name, permissions you need, slug, html function, icon, where does it appear (like priority. bigger number, lower position)
        add_menu_page("Words to Filter", "Word Filter", "manage_options", "wordfilter", array($this, "wordFilterPage"), "dashicons-smiley", 100);
        // menu to add to, document title, sidebar text, permissions, slug, html function
        add_submenu_page("wordfilter", "Word Filter Options", "Options", "manage_options", "wordfilter-options", array($this, "optionsSubPage"));
    }

    function wordFilterPage()
    { ?>
        Hello Plugin
    <?php }

    function optionsSubPage()
    { ?>
        Hello Subpage
<?php }
}

$contentFilter = new ContentFilter();
