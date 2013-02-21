<?php
/*
Plugin Name: Move Plugins - Envato Check
Plugin URI: http://moveplugins.com
Description: Check if a customer has a registered code with Envato. If so, let them register for WP.
Version: 1.0
Author: Phil Johnston
Author URI: http://moveplugins.com
Text Domain: mp_envato_check
Domain Path: languages
License: GPL2
*/

/*  Copyright 2012  Phil Johnston  (email : phil@moveplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Move Plugins Core.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Move Plugins Core, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
// Plugin version
if( !defined( 'MP_ENVATO_CHECK_VERSION' ) )
	define( 'MP_ENVATO_CHECK_VERSION', '1.0.0.0' );

// Plugin Folder URL
if( !defined( 'MP_ENVATO_CHECK_PLUGIN_URL' ) )
	define( 'MP_ENVATO_CHECK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Folder Path
if( !defined( 'MP_ENVATO_CHECK_PLUGIN_DIR' ) )
	define( 'MP_ENVATO_CHECK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Root File
if( !defined( 'MP_ENVATO_CHECK_PLUGIN_FILE' ) )
	define( 'MP_ENVATO_CHECK_PLUGIN_FILE', __FILE__ );

/*
|--------------------------------------------------------------------------
| GLOBALS
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| INTERNATIONALIZATION
|--------------------------------------------------------------------------
*/

function mp_envato_check_textdomain() {

	// Set filter for plugin's languages directory
	$mp_envato_check_lang_dir = dirname( plugin_basename( MP_ENVATO_CHECK_PLUGIN_FILE ) ) . '/languages/';
	$mp_envato_check_lang_dir = apply_filters( 'mp_envato_check_languages_directory', $mp_envato_check_lang_dir );


	// Traditional WordPress plugin locale filter
	$locale        = apply_filters( 'plugin_locale',  get_locale(), 'mp-envato-check' );
	$mofile        = sprintf( '%1$s-%2$s.mo', 'mp-envato-check', $locale );

	// Setup paths to current locale file
	$mofile_local  = $mp_envato_check_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/mp-envato-check/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/mp_envato_check folder
		load_textdomain( 'mp_envato_check', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/message_bar/languages/ folder
		load_textdomain( 'mp_envato_check', $mofile_local );
	} else {
		// Load the default language files
		load_plugin_textdomain( 'mp_envato_check', false, $mp_envato_check_lang_dir );
	}

}
add_action( 'init', 'mp_envato_check_textdomain', 1 );

/*
|--------------------------------------------------------------------------
| INCLUDES
|--------------------------------------------------------------------------
*/

/**
 * If mp_core isn't active, stop and install it now
 */
if (!function_exists('mp_core_textdomain')){
	
	/**
	 * Include Plugin Checker
	 */
	require( MP_ENVATO_CHECK_PLUGIN_DIR . 'includes/plugin-checker/plugin-checker.php' );
	
	/**
	 * Check if wp_core in installed
	 */
	require( MP_ENVATO_CHECK_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-core-check.php' );
	
}
/**
 * Otherwise, if mp_core is active, carry out the plugin's functions
 */
else{

		
	/**
	 * Create Metabox for HTML repeater for posts
	 */
	require( MP_ENVATO_CHECK_PLUGIN_DIR . 'includes/settings/mp-envato-check-options/mp-envato-check-options.php' );
	
	/**
	 * Create Metabox for HTML repeater for posts
	 */
	require( MP_ENVATO_CHECK_PLUGIN_DIR . 'includes/misc-functions/envato-check.php' );
	
}