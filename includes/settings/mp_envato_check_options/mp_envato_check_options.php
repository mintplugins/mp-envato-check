<?php 

function mp_envato_check_settings(){
	
	//Set args for new administration menu
	$args = array('title' => __('Envato Check', 'mp_core'), 'slug' => 'mp_envato_check_settings', 'type' => 'options');
	
	//Initialize settings class
	global $mp_envato_check_settings_class;
	$mp_envato_check_settings_class = new MP_CORE_Settings($args);
	
	
	//Include other option tabs
	include_once( 'settings-tab-general.php' );
}
add_action('plugins_loaded', 'mp_envato_check_settings');