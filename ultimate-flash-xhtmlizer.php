<?php
/*
Plugin Name: Ultimate Flash XHTMLizer
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Turns Flash embed code into well-formed XHTML by escaping object tags on the server side and unescaping them on the client side using JavaScript.
Version: 0.2
Author: László Monda
Author URI: http://monda.hu
License: GPL3
*/

define('UFX_PLUGIN_URL', plugin_dir_url( __FILE__ ));

function ufx_replace_flash_object($matches)
{
    $object_tag = $matches[1];
    return '<div class="flash">' . htmlspecialchars($object_tag) . '</div>';
}

function ufx_replace_flash_objects($html)
{
    if (is_search()) {
        // The search page probably contains excerpts which would display
        // the escaped object tags as they are which is not intended.
        return $html;
    } else {
        return preg_replace_callback('/(<object[ \t\n]+.*>.*<\/object>)/i',
                                     'ufx_replace_flash_object', $html);
    }
}

$options = get_option('ufx_options');
if (isset($options['do_include_css_and_js'])) {
    wp_register_style('ultimate-flash-xhtmlizer.css', UFX_PLUGIN_URL . 'ultimate-flash-xhtmlizer.css');
    wp_enqueue_style('ultimate-flash-xhtmlizer.css');
    wp_register_script('ultimate-flash-xhtmlizer.js', UFX_PLUGIN_URL . 'ultimate-flash-xhtmlizer.js',
                       array('jquery'));
    wp_enqueue_script('ultimate-flash-xhtmlizer.js');
}

add_filter('the_content', 'ufx_replace_flash_objects');

///////////////////////////////////////// Option handling /////////////////////////////////////////

function ufx_add_option_page()
{
?>
<div class="wrap">
<h2>Ultimate Flash XHTMLizer</h2>
<form method="post" action="options.php">
<?php settings_fields('ufx_options'); ?>
<?php do_settings_sections(__FILE__); ?>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?> " />
</p>
</form>
</div>
<?php
}

function ufx_admin_menu()
{
    add_options_page('Ultimate Flash XHTMLizer', 'Ultimate Flash XHTMLizer', 'manage_options',
                     'ufx_options', 'ufx_add_option_page');
}

function ufx_section_text()
{
    $plugin_directory = get_bloginfo('wpurl') . '/wp-content/plugins/ultimate-flash-xhtmlizer';

    print "You may not want to include (the very small) CSS and JavaScript code from external " .
          "files and rather directly insert their content into your theme to decrease the number " .
          "of HTTP requests and speed up your site.<br /><br />" .
          "To do so:<br />" .
          "1) Put the content of <a href=\"$plugin_directory\ultimate-flash-xhtmlizer.css\" " .
          "target=\"_blank\">ultimate-flash-xhtmlizer.css</a> into the stylesheet of your blog.<br />" .
          "2) Put the content of <a href=\"$plugin_directory\ultimate-flash-xhtmlizer.js\" " .
          "target=\"_blank\">ultimate-flash-xhtmlizer.js</a> into the template of your blog, " .
          "directly before &lt;/body&gt;, wrapped inside &lt;script&gt; and &lt;/script&gt;tags. " .
          "Also, don't forget to include jQuery which is utilized by this script.<br />" .
          "3) Uncheck the following option.";
}

function ufx_print_option()
{
    $options = get_option('ufx_options');
    $checked = isset($options['do_include_css_and_js']) ? 'checked="checked"' :'';
    print '<input id="do_include_css_and_js" type="checkbox" ' .
          'name="ufx_options[do_include_css_and_js]" ' . $checked . '" />';
}

function ufx_admin_init()
{
    register_setting('ufx_options', 'ufx_options');
    add_settings_section('ufx_main', 'Main Settings', 'ufx_section_text', __FILE__);
    add_settings_field('do_include_css_and_js', 'Include CSS and JavaScript',
                       'ufx_print_option', __FILE__, 'ufx_main');
}

function ufx_add_defaults() {
    $options = get_option('ufx_options');
    if (!isset($options)) {
        update_option('ufx_options', array('do_include_css_and_js' => 'on'));
    }
}

if (is_admin()) {
    add_action('admin_menu', 'ufx_admin_menu');
    add_action('admin_init', 'ufx_admin_init');
    register_activation_hook(__FILE__, 'ufx_add_defaults');
}

?>
