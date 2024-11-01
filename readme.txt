=== WP Head Footer ===
Contributors: francescotaurino
Tags: header, footer, wp_head, wp_footer, head, post, scripts, html, css, js, simple, fast, secure
Requires at least: 3.3.0
Tested up to: 6.2
Requires PHP: 5.2.4
Stable tag: 1.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WP Head Footer allows you to easily add custom code to the header and/or footer of any post, 
page, or custom post type on your WordPress site without modifying theme files. 
You can add JavaScript, HTML, CSS, and unleash your creativity to take your website to the next level.

= Installation =
To install the WP Head Footer plugin, follow these steps:

Upload the WP Head Footer plugin to the /wp-content/plugins/ directory of your WordPress site, or install it through the WordPress admin area.
Activate the plugin from the WordPress plugins screen.


= Usage =
To start adding your custom code at a global level or for individual posts/pages, follow these steps:

Go to the WP Head Footer settings page.
Add your custom code in the provided fields.

= Notes =
If the metabox is not displayed, you can manually add support by using the "add_post_type_support" 
function with the key "wp-head-footer". Here's an example:
`add_action('init', 'my_custom_post_type_support');
function my_custom_post_type_support() {
    add_post_type_support('your_custom_post_type', 'wp-head-footer');
}`

If you are using third-party plugins like ACF or CPT UI to create the post type, 
you can directly add support for WP Head Footer there.
Please note that using this plugin requires "manage_options" capability. Also, exercise caution when adding custom code to your site, as the plugin does not provide input validation or filtering.

= Support =
If you encounter any issues or have questions, please contact our support team at:
https://wordpress.org/support/plugin/wp-head-footer

== Changelog ==

For a list of all plugin changes, please refer to the CHANGELOG.md file.