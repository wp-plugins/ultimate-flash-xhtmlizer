=== Ultimate Flash XHTMLizer ===
Contributors:
Donate link:
Tags: flash, xhtml, converter
Requires at least: 2.7
Tested up to: 3.4
Stable tag: 0.2

Turns Flash embed code into well-formed XHTML by escaping object tags on the server side and
unescaping them on the client side using JavaScript.

== Description ==

Have you ever tried to validate a WordPress page that is full of Flash content and experienced some
major pain throughout the process?  If so, then this plugin is just for you!

Ultimate Flash XMTMLizer turns Flash embed code into well-formed XHTML by escaping object tags on
the server side and unescaping them on the client side using JavaScript.

== Installation ==

1. Extract the plugin.
1. Upload the `ultimate-flash-xhtmlizer` directory to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. You may want to go to the settings menu of the plugin and follow the directions there to minimize
   the number of HTTP requests involved.

== Changelog ==

= 0.1 =
* First release.

= 0.2 =
* Don't escape object tags on search pages because the excerpts that are featured on those pages
  by most themes would display the escaped content as it is.