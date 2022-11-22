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
        add_filter("the_content", array($this, "filterLogic"));
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

    function handleForm()
    {
        // Check if the nonce is ok
        if (wp_verify_nonce($_POST["filterNonce"], "saveFilterWords") && current_user_can("manage_options")) {
            // name of option, value
            update_option("plugin_words_to_filter", sanitize_text_field($_POST['pluginWordsToFilter'])); ?>
            <!-- "updated" class is built in WP -->
            <div class="updated">
                <p>Your filtered words have been saved.</p>
            </div>
        <?php
        } else { ?>
            <div class="error">
                <p>Sorry, you don't have permissions to perform that action.</p>
            </div>
        <?php
        }
    }

    function wordFilterPage()
    { ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <!-- $_POST is whatever the browser just posted to the server -->
            <?php if ($_POST['justSubmitted'] == "true") $this->handleForm(); ?>
            <form method="POST">
                <!-- This is to know whether user has submitted or not -->
                <input type="hidden" name="justSubmitted" value="true">
                <!-- This is for security -->
                <?php wp_nonce_field("saveFilterWords", "filterNonce"); ?>
                <label for="pluginWordsToFilter">
                    <p>Enter a <strong>comma separated</strong> list of words to filter from content.</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="pluginWordsToFilter" id="pluginWordsToFilter" placeholder="bad, mean"><?php echo esc_textarea(get_option("plugin_words_to_filter")); ?></textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
    <?php }

    function optionsSubPage()
    { ?>
        Hello Subpage
<?php }

    function filterLogic($content)
    {
        if (get_option("plugin_words_to_filter")) {
            $wordsToFilter = explode(",", get_option("plugin_words_to_filter"));
            $wordsToFilterTrimmed = array_map("trim", $wordsToFilter);
            return str_ireplace($wordsToFilterTrimmed, "****", $content);
        }
    }
}

$contentFilter = new ContentFilter();
