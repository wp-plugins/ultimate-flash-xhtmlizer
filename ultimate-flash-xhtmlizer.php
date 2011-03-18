<?php
/*
Plugin Name: Ultimate Flash XHTMLizer
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Turns all your nasty Flash objects into well-formed XHTML.  It works by escaping Flash objects on the server side and unescaping them on the client side using JavaScript.
Version: 0.1
Author: László Monda
Author URI: http://monda.hu
License: GPL3
*/

function ufx_replace_flash_object($matches)
{
    $object_tag = $matches[1];
    return '<div class="flash">' . htmlspecialchars($object_tag) . '</div>';
}

function ufx_replace_flash_objects($html)
{
    return preg_replace_callback('/(<object[ \t\n]+.*>.*<\/object>)/i', 'ufx_replace_flash_object', $html);
}

define('ufx_PLUGIN_URL', plugin_dir_url( __FILE__ ));

if (false) {  // This should depend on the value of the settings checkbox.
    wp_register_style('ultimate-flash-xhtmlizer.css', ufx_PLUGIN_URL . 'ultimate-flash-xhtmlizer.css');
    wp_enqueue_style('ultimate-flash-xhtmlizer.css');
    wp_register_script('ultimate-flash-xhtmlizer.js', ufx_PLUGIN_URL . 'ultimate-flash-xhtmlizer.js', array('jquery'));
    wp_enqueue_script('ultimate-flash-xhtmlizer.js');
}

add_filter('the_content', 'ufx_replace_flash_objects');

//////////////////////////////////////// Option handling ////////////////////////////////////////

function ufx_add_option_page()
{
?>
<div class="wrap">
<h2>Ultimate Flash XHTMLizer</h2>
<form method="post" action="<? print $_SERVER['REQUEST_URI'] ?>">
<?php settings_fields('ufx_options'); ?>
<?php do_settings_sections('ufx'); ?>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?> " />
</p>
</form>
</div>
<?php
}

function ufx_admin_menu()
{
    add_options_page('Ultimate Flash XHTMLizer', 'Ultimate Flash XHTMLizer', 'manage_options', 'ufx_options', 'ufx_add_option_page');
}

function ufx_section_text()
{
    print "You may don't want to include (the very small) CSS and JavaScript code from external " .
          "files and rather directly include their content to your theme to decrease the number " .
          "of HTTP requests and speed up your site.  If so, uncheck the next option.";
}

function ufx_print_option()
{
    print '<input type="checkbox" name="do_include_css_and_js" value="' . get_option('do_include_css_and_js') . '" />';
}

function ufx_validate_options($options)
{
    return $options;
}

function ufx_admin_init()
{
    register_setting('ufx_options', 'ufx_options', 'ufx_validate_options');
    add_settings_section('ufx_main', 'Main Settings', 'ufx_section_text', 'ufx');
    add_settings_field('do_include_css_and_js', 'Include CSS and JavaScript', 'ufx_print_option', 'ufx', 'ufx_main');
}


if (is_admin()) {
    add_action('admin_menu', 'ufx_admin_menu');
    add_action('admin_init', 'ufx_admin_init');
}

?>
