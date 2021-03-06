<?php
/**
 * Plugin Name: PC Builder
 * Plugin URI: #
 * Description: Choose all your specs to customize your budget, by pc builder you can add all pc products with it's brands.
 * Version: 1.0.0
 * Author: Ahmed-Elzarzamony
 * Author URI: #
 * License: GPLv2 or later
 * Text Domain: pcbuilder
 */


if (!function_exists( 'add_action')) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';exit;
}

define( 'PCBUILDER_VERSION', '1.0.0' );
define( 'PCBUILDER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); 
define( 'PCBUILDER_MIN_BUDGET_FOR_EXTRA', 1000 ); 
define( 'PCBUILDER_MIN_BUDGET', 400 ); 
define( 'PCBUILDER_MAX_BUDGET', 5000 ); 

$pcbuilder_groups = [
	'CPU' => 'CPU',
	'GPU' => 'GPU',
	'RAM' => 'RAM',
	'MB' => 'MB',
	'HDD' => 'HDD',
	'PSU' => 'PSU',
];

register_activation_hook( __FILE__, ['PCBUILDER', 'pluginActivation'] );
register_deactivation_hook( __FILE__, ['PCBUILDER', 'pluginDeactivation'] );
register_uninstall_hook( __FILE__, ['PCBUILDER', 'pluginUninstall'] );

add_action( 'init', ['PCBUILDER', 'init'] );
add_action( 'init', ['Shortcode', 'init'] );
add_action( 'init', ['Customfields', 'init'] );



require_once( PCBUILDER__PLUGIN_DIR . 'class.pc-builder.php' );
require_once( PCBUILDER__PLUGIN_DIR . 'pcbuilder.custom-fields.php' );
require_once( PCBUILDER__PLUGIN_DIR . 'pcbuilder.shortcode.php' );

