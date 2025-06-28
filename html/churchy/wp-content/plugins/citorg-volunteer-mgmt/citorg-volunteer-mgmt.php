<?php
/**
 * Plugin Name:       My Awesome Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       A brief description of what my awesome plugin does.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Your Name
 * Author URI:        https://your-website.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-awesome-plugin
 * Domain Path:       /languages
 */

require_once( plugin_dir_path(__FILE__) . 'includes/activation.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/shortcodes.php' );

register_activation_hook( __FILE__, 'volunteer_mgmt_create_database_tables' );
register_activation_hook( __FILE__, 'register_volunteering_shortcodes' );