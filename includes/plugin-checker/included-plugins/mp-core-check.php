<?php
/**
 * Install mp_core Plugin
 *
 */
 if (!function_exists('mp_core_plugin_check')){
	function mp_core_plugin_check() {
		$args = array(
			'plugin_name' => __('Move Plugins Core', 'mp_envato_check'), 
			'plugin_message' => __('You require the Move Plugins Core plugin. Install it here.', 'mp_envato_check'), 
			'plugin_slug' => 'mp_core', 
			'plugin_subdirectory' => 'mp-core/', 
			'plugin_filename' => 'mp-core.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://moveplugins.com'
		);
		$mp_core_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( 'admin_init', 'mp_core_plugin_check' );

