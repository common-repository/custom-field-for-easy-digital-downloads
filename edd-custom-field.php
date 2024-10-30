<?php
/**
 * Plugin Name: Custom Field For Easy Digital Downloads
 * Description: Custom field can be add in Easy Digital Downloads like text field , select field
 * Version:     1.0
 * Author:      Gravity Master
 * License:     GPLv2 or later
 * Text Domain: cfedd
 */

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/* All constants should be defined in this file. */
if ( ! defined( 'CFEDD_PREFIX' ) ) {
	define( 'CFEDD_PREFIX', 'cfedd' );
}
if ( ! defined( 'CFEDD_PLUGINDIR' ) ) {
	define( 'CFEDD_PLUGINDIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CFEDD_PLUGINBASENAME' ) ) {
	define( 'CFEDD_PLUGINBASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'CFEDD_PLUGINURL' ) ) {
	define( 'CFEDD_PLUGINURL', plugin_dir_url( __FILE__ ) );
}

/* Auto-load all the necessary classes. */
if( ! function_exists( 'cfedd_class_auto_loader' ) ) {
	
	function cfedd_class_auto_loader( $class ) {
		
	 	$includes = CFEDD_PLUGINDIR . 'includes/' . $class . '.php';
		
		if( is_file( $includes ) && ! class_exists( $class ) ) {
			include_once( $includes );
			return;
		}
		
	}
}
spl_autoload_register('cfedd_class_auto_loader');

new CFEDD_Admin();
new CFEDD_Frontend();
?>