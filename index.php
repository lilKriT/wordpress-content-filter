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
        $mainPageHook = add_menu_page("Words to Filter", "Word Filter", "manage_options", "wordfilter", array($this, "wordFilterPage"), "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+Cg==", 100);
        // It might seem redundant to do this, but this way we can change the sidebar name of the first link.
        add_submenu_page("wordfilter", "Words to Filter", "Words List", "manage_options", "wordfilter", array($this, "wordFilterPage"));

        // menu to add to, document title, sidebar text, permissions, slug, html function
        add_submenu_page("wordfilter", "Word Filter Options", "Options", "manage_options", "wordfilter-options", array($this, "optionsSubPage"));

        // Another way to include the icon:
        //add_menu_page("Words to Filter", "Word Filter", "manage_options", "wordfilter", array($this, "wordFilterPage"), plugin_dir_url(__FILE__) . "custom.svg", 100);

        // CSS
        // insert the hook name from inserting the main page of this plugin
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets()
    {
        wp_enqueue_style("filterAdminCSS", plugin_dir_url(__FILE__) . "styles.css");
    }

    function wordFilterPage()
    { ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <form method="POST">
                <label for="pluginWordsToFilter">
                    <p>Enter a <strong>comma separated</strong> list of words to filter from content.</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="pluginWordsToFilter" id="pluginWordsToFilter" placeholder="bad, mean"></textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
    <?php }

    function optionsSubPage()
    { ?>
        Hello Subpage
<?php }
}

$contentFilter = new ContentFilter();
